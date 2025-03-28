<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Support\Facades\Auth;

class JwtAuthenticateRefresh
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Autentikasi user dari token yang ada
            JWTAuth::parseToken()->authenticate();

            $payload = JWTAuth::getPayload();
            $exp = $payload->get('exp');
            $now = now()->timestamp;
            //79200 = 22 jam jika token masih 22 jam lagi maka refresh token
            //82800 = 23 jam
            // 1 hari = 86400 detik
            // 6 hari = 518400 detik
            // 6 hari 23 jam = 518400 + 82800 = 601200 detik
            if (($exp - $now) < 601200) {
                $newToken = JWTAuth::refresh();
                JWTAuth::setToken($newToken);
                $user = JWTAuth::user();
                Auth::setUser($user);
                $request->headers->set('Authorization', 'Bearer ' . $newToken);
                $response = $next($request);
                return $response->header('Authorization', 'Bearer ' . $newToken);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
