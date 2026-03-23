<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class IzibizAuthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $url = Config::get('services.izibiz.auth_url', env('IZIBIZ_AUTH_URL'));

        $payload = [
            'username' => env('IZIBIZ_USERNAME'),
            'password' => env('IZIBIZ_PASSWORD'),
        ];

        $response = Http::asJson()->post($url, $payload);

        return response()->json($response->json(), $response->status());
    }
}

