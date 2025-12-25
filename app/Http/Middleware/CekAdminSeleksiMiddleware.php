<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AdminSeleksi;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAdminSeleksiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (izinkanAkses('admin')) {
            return $next($request);
        }

        $beasiswa_id =
            $request->route('beasiswa_id')
            ?? $request->input('beasiswa_id');

        if (!$beasiswa_id) {
            return response()->json([
                'status' => false,
                'message' => 'Beasiswa tidak ditemukan'
            ], 403);
        }

        $isAdminSeleksi = adminSeleksi($beasiswa_id);

        if (!$isAdminSeleksi) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke beasiswa ini'
            ], 403);
        }

        return $next($request);
    }
}
