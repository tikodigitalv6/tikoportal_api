<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class IzibizTariffController extends Controller
{
    public function __invoke(Request $request, int $customerId): JsonResponse
    {
        $channelId = Config::get('services.izibiz.channel_id');

        $tokenResponse = Http::asJson()->post(
            Config::get('services.izibiz.auth_url'),
            [
                'username' => Config::get('services.izibiz.username'),
                'password' => Config::get('services.izibiz.password'),
            ]
        );

        if (! $tokenResponse->successful()) {
            return response()->json([
                'message' => 'Token alınamadı',
                'error' => $tokenResponse->json(),
            ], $tokenResponse->status());
        }

        $tokenData = $tokenResponse->json('data');
        $accessToken = $tokenData['accessToken'] ?? null;

        if (! $accessToken) {
            return response()->json([
                'message' => 'Access token bulunamadı',
                'response' => $tokenResponse->json(),
            ], 500);
        }

        $tariffsUrl = Config::get('services.izibiz.tariffs_url');

        $allTariffs = [];

        // İlk sayfayı (page=0) çekip toplam sayfa bilgisini alalım
        $firstResponse = Http::withToken($accessToken)
            ->withHeaders([
                'Accept-Language' => 'tr-TR',
                'X-On-Behalf-Of-Channel' => $channelId,
                'X-On-Behalf-Of-Customer' => $customerId,
            ])
            ->get($tariffsUrl, [
                'sortProperty' => 'startDate',
                'page' => 0,
                'sort' => 'desc',
                'status' => 'ALL',
                'pageSize' => 250,
            ]);

        if (! $firstResponse->successful()) {
            return response()->json([
                'message' => 'Tarife geçmişi alınırken hata oluştu',
                'page' => 0,
                'error' => $firstResponse->json(),
            ], $firstResponse->status());
        }

        $firstData = $firstResponse->json('data');
        $firstContents = $firstData['contents'] ?? [];
        $pageable = $firstData['pageable'] ?? null;
        $totalPages = $pageable['totalPages'] ?? 1;

        if (! empty($firstContents) && is_array($firstContents)) {
            $allTariffs = array_merge($allTariffs, $firstContents);
        }

        // Kalan sayfaları (1..totalPages-1) dolaş
        for ($page = 1; $page < $totalPages; $page++) {
            $response = Http::withToken($accessToken)
                ->withHeaders([
                    'Accept-Language' => 'tr-TR',
                    'X-On-Behalf-Of-Channel' => $channelId,
                    'X-On-Behalf-Of-Customer' => $customerId,
                ])
                ->get($tariffsUrl, [
                    'sortProperty' => 'startDate',
                    'page' => $page,
                    'sort' => 'desc',
                    'status' => 'ALL',
                    'pageSize' => 250,
                ]);

            if (! $response->successful()) {
                return response()->json([
                    'message' => 'Tarife geçmişi alınırken hata oluştu',
                    'page' => $page,
                    'error' => $response->json(),
                ], $response->status());
            }

            $data = $response->json('data');
            $contents = $data['contents'] ?? [];

            if (! empty($contents) && is_array($contents)) {
                $allTariffs = array_merge($allTariffs, $contents);
            }
        }

        return response()->json([
            'data' => $allTariffs,
            'total' => count($allTariffs),
            'totalPages' => $totalPages,
        ]);
    }
}

