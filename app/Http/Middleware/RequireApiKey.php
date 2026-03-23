<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        // Yerel ortamda geliştirme kolaylığı opsiyonel:
        // TIKO_PORTAL_API_KEY_BYPASS_LOCAL=true ise yalnızca localhost/127.0.0.1 isteklerini bypass eder.
        if (app()->environment('local') && env('TIKO_PORTAL_API_KEY_BYPASS_LOCAL') === 'true') {
            $host = $request->getHost();
            if ($host === '127.0.0.1' || $host === 'localhost') {
                return $next($request);
            }
        }

        $secret = env('TIKO_PORTAL_API_KEY_SECRET');

        if ($secret === null || $secret === '') {
            return response()->json([
                'message' => 'API key secret tanımlı değil. Lütfen .env içine `TIKO_PORTAL_API_KEY_SECRET` ekleyin.',
            ], 500);
        }

        // Desteklenen başlıklar:
        // - X-API-KEY: <secret>
        // - Authorization: Bearer <secret>
        $provided = $request->header('X-API-KEY');
        if (! $provided) {
            $bearer = $request->bearerToken();
            if ($bearer) {
                $provided = $bearer;
            }
        }

        if (! $provided || ! hash_equals((string) $secret, (string) $provided)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}

