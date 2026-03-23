<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class IzibizCustomerController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $page = max(0, (int) $request->query('page', 0));
        $size = (int) $request->query('size', 20);
        $size = max(1, min($size, 200));

        $refresh = $request->boolean('refresh', false);
        $search = trim((string) $request->query('q', ''));

        $cacheKey = 'izibiz_customers_full';

        if ($refresh || ! Cache::has($cacheKey)) {
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

            $customersUrl = Config::get('services.izibiz.customers_url');

            $allCustomers = [];

            // İlk sayfa (0) ile toplam sayfa sayısını al
            $firstResponse = Http::withToken($accessToken)
                ->withHeaders([
                    'Accept-Language' => 'tr-TR',
                ])
                ->get($customersUrl, [
                    'page' => 0,
                ]);

            if (! $firstResponse->successful()) {
                return response()->json([
                    'message' => 'Müşteriler çekilirken hata oluştu',
                    'page' => 0,
                    'error' => $firstResponse->json(),
                ], $firstResponse->status());
            }

            $firstData = $firstResponse->json('data');
            $firstContents = $firstData['contents'] ?? [];
            $pageable = $firstData['pageable'] ?? null;
            $totalPagesFromApi = isset($pageable['totalPages']) ? (int) $pageable['totalPages'] : 1;

            if (! empty($firstContents) && is_array($firstContents)) {
                $allCustomers = array_merge($allCustomers, $firstContents);
            }

            // Kalan sayfaları dolaş (1..totalPages-1)
            for ($p = 1; $p < $totalPagesFromApi; $p++) {
                $response = Http::withToken($accessToken)
                    ->withHeaders([
                        'Accept-Language' => 'tr-TR',
                    ])
                    ->get($customersUrl, [
                        'page' => $p,
                    ]);

                if (! $response->successful()) {
                    return response()->json([
                        'message' => 'Müşteriler çekilirken hata oluştu',
                        'page' => $p,
                        'error' => $response->json(),
                    ], $response->status());
                }

                $data = $response->json('data');
                $contents = $data['contents'] ?? [];

                if (! empty($contents) && is_array($contents)) {
                    $allCustomers = array_merge($allCustomers, $contents);
                }
            }

            Cache::put($cacheKey, $allCustomers, now()->addMinutes(15));
        }

        $allCustomers = Cache::get($cacheKey, []);

        // Arama filtresi
        if ($search !== '') {
            $q = mb_strtolower($search, 'UTF-8');

            $allCustomers = array_values(array_filter($allCustomers, function ($customer) use ($q) {
                $parts = [
                    $customer['commercialName'] ?? null,
                    $customer['firstName'] ?? null,
                    $customer['lastName'] ?? null,
                    $customer['vknTckno'] ?? null,
                    $customer['taxOffice'] ?? null,
                ];

                $haystack = mb_strtolower(implode(' ', array_filter($parts)), 'UTF-8');

                return $haystack !== '' && str_contains($haystack, $q);
            }));
        }

        $totalElements = count($allCustomers);
        $totalPages = (int) ceil($totalElements / $size);

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        if ($page >= $totalPages) {
            $page = $totalPages - 1;
        }

        $offset = $page * $size;
        $pageItems = array_slice($allCustomers, $offset, $size);

        return response()->json([
            'data' => array_values($pageItems),
            'pageable' => [
                'page' => $page,
                'size' => $size,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
            ],
        ]);
    }
}

