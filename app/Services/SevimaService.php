<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SevimaService
{
    protected $baseUrl;
    protected $keys;

    public function __construct()
    {
        $this->baseUrl = config('sevima.base_url');
        $this->keys = config('sevima.keys');
    }

    private function poolPath()
    {
        return storage_path('app/sevima_pool.json');
    }

    private function poolState()
    {
        $file = $this->poolPath();

        if (!file_exists($file)) {
            $init = [
                'active' => 0,
                'cooldowns' => array_fill(0, count($this->keys), 0),
                'usages' => array_fill(0, count($this->keys), 0),
                'window_start' => time()
            ];
            file_put_contents($file, json_encode($init));
        }

        $state = json_decode(file_get_contents($file), true);

        // self repair
        if (count($state['cooldowns']) != count($this->keys)) {
            $state['cooldowns'] = array_fill(0, count($this->keys), 0);
        }

        if (!isset($state['usages']) || count($state['usages']) != count($this->keys)) {
            $state['usages'] = array_fill(0, count($this->keys), 0);
        }

        if (!isset($state['window_start'])) {
            $state['window_start'] = time();
        }

        return $state;
    }

    private function savePoolState($state)
    {
        ksort($state['cooldowns']);
        ksort($state['usages']);
        file_put_contents($this->poolPath(), json_encode($state));
    }

    private function markUsage($index)
    {
        $state = $this->poolState();

        if (time() - $state['window_start'] >= 60) {
            $state['usages'] = array_fill(0, count($this->keys), 0);
            $state['window_start'] = time();
        }

        $state['usages'][$index]++;
        $this->savePoolState($state);
    }

    private function markRateLimited($index, $seconds = 65)
    {
        $state = $this->poolState();
        $state['cooldowns'][$index] = time() + $seconds;
        $this->savePoolState($state);
    }

    private function activeKeyIndex()
    {
        $state = $this->poolState();
        $now = time();
        $total = count($this->keys);
        $start = $state['active'];

        // pass 1: normal
        for ($i = 0; $i < $total; $i++) {
            $idx = ($start + $i) % $total;

            if (
                $state['cooldowns'][$idx] <= $now &&
                $state['usages'][$idx] < 20
            ) {
                $state['active'] = $idx;
                $this->savePoolState($state);
                return $idx;
            }
        }

        // pass 2: fallback least used
        $best = false;
        $bestUsage = PHP_INT_MAX;

        for ($i = 0; $i < $total; $i++) {
            $idx = ($start + $i) % $total;

            if ($state['cooldowns'][$idx] <= $now) {
                if ($state['usages'][$idx] < $bestUsage) {
                    $bestUsage = $state['usages'][$idx];
                    $best = $idx;
                }
            }
        }

        if ($best !== false) {
            $state['active'] = $best;
            $this->savePoolState($state);
            return $best;
        }

        return false;
    }

    private function headers($index)
    {
        return [
            'X-App-Key' => $this->keys[$index]['app_key'],
            'X-Secret-Key' => $this->keys[$index]['secret_key'],
            'Accept' => 'application/json',
        ];
    }

    private function request($url, $method = 'GET', $body = null)
    {
        $maxRetry = count($this->keys) * 5;
        $retry = 0;
        $queueStart = time();

        while (true) {

            $index = $this->activeKeyIndex();

            // semua key sibuk → queue
            if ($index === false) {
                if (time() - $queueStart > 180) {
                    logger()->error('Queue >180 sec');
                }

                usleep(500000);
                continue;
            }

            try {
                $http = Http::withHeaders($this->headers($index))
                    ->timeout(30);

                $response = match ($method) {
                    'POST' => $http->post($url, $body),
                    default => $http->get($url),
                };

            } catch (\Exception $e) {
                logger()->error('Curl error key ' . $index);

                $retry++;
                usleep(500000);
                continue;
            }

            $status = $response->status();

            // 429 rate limit
            if ($status == 429) {
                logger()->error('429 key ' . $index);

                $this->markRateLimited($index, 65);

                $retry++;
                if ($retry < $maxRetry) {
                    usleep(300000);
                    continue;
                }

                $retry = 0;
                usleep(rand(500000, 900000));
                continue;
            }

            // auth fail
            if (in_array($status, [401, 403])) {
                logger()->error('Auth fail key ' . $index);

                $this->markRateLimited($index, 1800);
                usleep(100000);
                continue;
            }

            $decoded = $response->json();

            if (!$decoded) {
                return [
                    'error' => 'Response tidak valid',
                    'raw' => $response->body()
                ];
            }

            // error dari API
            if (isset($decoded['errors'])) {
                $detail = $decoded['errors']['detail'] ?? 'Error';

                if (str_contains($detail, 'API key tidak ditemukan')) {
                    logger()->error('Invalid key ' . $index);

                    $this->markRateLimited($index, 1800);
                    usleep(100000);
                    continue;
                }

                return ['error' => $detail];
            }

            // sukses
            $retry = 0;
            $this->markUsage($index);

            // normalize response
            if (isset($decoded['attributes'])) {
                return $decoded['attributes'];
            }

            if (isset($decoded['data']) && is_array($decoded['data'])) {
                return [
                    'data' => $decoded['data'],
                    'meta' => $decoded['meta'] ?? null,
                    'urls' => $decoded['urls'] ?? null
                ];
            }

            return [
                'message' => 'Format response tidak dikenali',
                'raw' => $decoded
            ];
        }
    }

    public function get($endpoint)
    {
        return $this->request($this->baseUrl . $endpoint);
    }

    public function post($endpoint, $body)
    {
        return $this->request($this->baseUrl . $endpoint, 'POST', $body);
    }

    public function login($email, $password)
    {
        return $this->post('siakadcloud/v1/user/login', [
            'email' => $email,
            'password' => $password
        ]);
    }
}