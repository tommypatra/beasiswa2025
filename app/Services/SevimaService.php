<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class SevimaService
{
    protected string $baseUrl;
    protected int $timeout;
    protected int $maxRequest;
    protected int $windowSeconds;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('sevima.base_url'), '/');
        $this->timeout = config('sevima.timeout', 30);
        $this->maxRequest = config('sevima.max_request', 30);
        $this->windowSeconds = config('sevima.window_seconds', 60);
    }

    public function get(string $uri, array $query = []): array
    {
        return $this->request('GET', $uri, $query);
    }

    public function post(string $uri, array $data = []): array
    {
        return $this->request('POST', $uri, $data);
    }

    protected function request(string $method, string $uri, array $data = []): array
    {
        $keyCount = $this->activeKeyCount();

        if ($keyCount === 0) {
            return $this->error(
                503,
                'Tidak ada API Key Sevima yang aktif.'
            );
        }

        $triedKeys = [];

        for ($i = 0; $i < $keyCount; $i++) {
            $key = $this->getAvailableKey($triedKeys);

            if (!$key) {
                $wait = $this->getNextAvailableWait();

                return $this->error(
                    429,
                    'Semua API Key Sevima sedang mencapai batas request.',
                    [
                        'retry_after' => $wait,
                    ]
                );
            }

            $triedKeys[] = $key->id;

            $response = $this->sendRequest(
                $method,
                $uri,
                $data,
                $key
            );

            if ($response['status'] === 429) {
                $this->markRateLimit($key);
                continue;
            }

            return $response;
        }

        $wait = $this->getNextAvailableWait();

        return $this->error(
            429,
            'Semua API Key Sevima sedang mencapai batas request.',
            [
                'retry_after' => $wait,
            ]
        );
    }

    protected function getAvailableKey(array $excludedIds = [])
    {
        return DB::transaction(function () use ($excludedIds) {
            $now = now();

            $keys = DB::table('sevima_api')
                ->where('is_active', 1)
                ->when(
                    !empty($excludedIds),
                    fn ($query) => $query->whereNotIn('id', $excludedIds)
                )
                ->orderBy('usage_count')
                ->orderBy('last_used_at')
                ->orderBy('key_index')
                ->lockForUpdate()
                ->get();

            foreach ($keys as $key) {
                if ($key->cooldown_until) {
                    $cooldown = \Carbon\Carbon::parse(
                        $key->cooldown_until
                    );

                    if ($cooldown->isFuture()) {
                        continue;
                    }
                }

                $windowStart = $key->window_start
                    ? \Carbon\Carbon::parse($key->window_start)
                    : null;

                if (
                    !$windowStart ||
                    $windowStart->copy()
                        ->addSeconds($this->windowSeconds)
                        ->isPast()
                ) {
                    DB::table('sevima_api')
                        ->where('id', $key->id)
                        ->update([
                            'usage_count' => 1,
                            'window_start' => $now,
                            'last_used_at' => $now,
                            'cooldown_until' => null,
                        ]);

                    $key->usage_count = 1;
                    $key->window_start = $now;
                    $key->last_used_at = $now;
                    $key->cooldown_until = null;

                    return $key;
                }

                if ((int) $key->usage_count < $this->maxRequest) {
                    $usage = (int) $key->usage_count + 1;

                    DB::table('sevima_api')
                        ->where('id', $key->id)
                        ->update([
                            'usage_count' => $usage,
                            'last_used_at' => $now,
                            'cooldown_until' => null,
                        ]);

                    $key->usage_count = $usage;
                    $key->last_used_at = $now;
                    $key->cooldown_until = null;

                    return $key;
                }
            }

            return null;
        });
    }

    protected function markRateLimit(object $key): void
    {
        if (!$key->window_start) {
            return;
        }

        $windowEnd = \Carbon\Carbon::parse(
            $key->window_start
        )->addSeconds($this->windowSeconds);

        DB::table('sevima_api')
            ->where('id', $key->id)
            ->update([
                'cooldown_until' => $windowEnd,
            ]);
    }

    protected function getNextAvailableWait(): int
    {
        $now = now();
        $waitTimes = [];

        $keys = DB::table('sevima_api')
            ->where('is_active', 1)
            ->get();

        foreach ($keys as $key) {
            if ($key->cooldown_until) {
                $cooldown = \Carbon\Carbon::parse(
                    $key->cooldown_until
                );

                if ($cooldown->isFuture()) {
                    $waitTimes[] = $now->diffInSeconds($cooldown);
                    continue;
                }
            }

            if (!$key->window_start) {
                return 0;
            }

            if ((int) $key->usage_count < $this->maxRequest) {
                return 0;
            }

            $windowEnd = \Carbon\Carbon::parse(
                $key->window_start
            )->addSeconds($this->windowSeconds);

            if ($windowEnd->isFuture()) {
                $waitTimes[] = $now->diffInSeconds($windowEnd);
            }
        }

        return empty($waitTimes)
            ? 0
            : max(1, min($waitTimes));
    }


protected function sendRequest(string $method, string $uri, array $data, object $key): array
{
    $url = $this->baseUrl . '/' . ltrim($uri, '/');

    try {
        $http = Http::timeout($this->timeout)
            ->withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
            ])
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-App-Key' => $key->app_key,
                'X-Secret-Key' => $key->secret_key,
            ]);

        if (strtoupper($method) === 'GET') {
            $response = $http->get($url, $data);
        } else {
            $response = $http->post($url, $data);
        }

        $body = $response->json();

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'message' => $this->getMessage($body),
            'data' => $body,
        ];
    } catch (Throwable $e) {
        return [
            'success' => false,
            'status' => 500,
            'message' => 'Gagal terhubung ke API Sevima.',
            'data' => null,
            'error' => $e->getMessage(),
        ];
    }
}

    protected function activeKeyCount(): int
    {
        return DB::table('sevima_api')
            ->where('is_active', 1)
            ->count();
    }

    protected function getMessage($data): string
    {
        if (!is_array($data)) {
            return 'Response tidak valid.';
        }

        return $data['message']
            ?? $data['errors']['message']
            ?? $data['errors']['detail']
            ?? 'Request selesai.';
    }

    protected function error(
        int $status,
        string $message,
        array $extra = []
    ): array {
        return array_merge([
            'success' => false,
            'status' => $status,
            'message' => $message,
            'data' => null,
        ], $extra);
    }
}
