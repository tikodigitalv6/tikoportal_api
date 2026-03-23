<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
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

        // Yeni eklediğimiz sütunlar dolu değilse (commercialName null vs.) bir kere import atalım.
        $needsImport = $refresh
            || Customer::query()->count() === 0
            || ! Customer::query()->whereNotNull('commercialName')->exists();

        if ($needsImport) {
            try {
                $allCustomers = $this->fetchAllCustomersFromIzibiz();
            } catch (\Throwable $e) {
                $status = (int) $e->getCode();
                if ($status < 100) $status = 500;

                $body = json_decode($e->getMessage(), true);
                if (! is_array($body)) {
                    $body = [
                        'message' => 'Izibiz müşteri import hatası',
                    ];
                }

                return response()->json($body, $status);
            }

            $now = now();
            $rows = array_values(array_filter(array_map(function (array $customer) use ($now) {
                $id = (int) ($customer['id'] ?? 0);
                if ($id <= 0) return null;

                $parts = [
                    $customer['commercialName'] ?? null,
                    $customer['firstName'] ?? null,
                    $customer['lastName'] ?? null,
                    $customer['vknTckno'] ?? null,
                    $customer['taxOffice'] ?? null,
                ];

                $searchText = mb_strtolower(implode(' ', array_filter($parts)), 'UTF-8');

                $statusDate = null;
                if (! empty($customer['statusDate'])) {
                    try {
                        $statusDate = \Carbon\Carbon::parse((string) $customer['statusDate'])->utc()->format('Y-m-d H:i:s');
                    } catch (\Throwable) {
                        $statusDate = null;
                    }
                }

                return [
                    'id' => $id,
                    'data' => json_encode($customer, JSON_UNESCAPED_UNICODE),
                    'search_text' => $searchText,

                    // flat sütunlar
                    'commercialName' => $customer['commercialName'] ?? null,
                    'firstName' => $customer['firstName'] ?? null,
                    'lastName' => $customer['lastName'] ?? null,
                    'vknTckno' => $customer['vknTckno'] ?? null,
                    'website' => $customer['website'] ?? null,
                    'taxOffice' => $customer['taxOffice'] ?? null,
                    'status' => $customer['status'] ?? null,
                    'statusDate' => $statusDate,
                    'statusDesc' => $customer['statusDesc'] ?? null,

                    'customerType' => $customer['customerType'] ?? null,
                    'configType' => $customer['configType'] ?? null,
                    'configTypeDesc' => $customer['configTypeDesc'] ?? null,
                    'companyType' => $customer['companyType'] ?? null,
                    'companyTypeDesc' => $customer['companyTypeDesc'] ?? null,

                    'channelRefId' => $customer['channelRefId'] ?? null,
                    'dealerRefId' => $customer['dealerRefId'] ?? null,
                    'accountRefId' => $customer['accountRefId'] ?? null,
                    'accountRefName' => $customer['accountRefName'] ?? null,
                    'channelRefName' => $customer['channelRefName'] ?? null,
                    'dealerRefName' => $customer['dealerRefName'] ?? null,

                    'sicilNo' => $customer['sicilNo'] ?? null,
                    'isletmeMerkezi' => $customer['isletmeMerkezi'] ?? null,
                    'mersisNo' => $customer['mersisNo'] ?? null,

                    'contractSigned' => array_key_exists('contractSigned', $customer) ? $customer['contractSigned'] : null,
                    'activationReason' => $customer['activationReason'] ?? null,
                    'deactivationReason' => $customer['deactivationReason'] ?? null,
                    'billingCustomerId' => $customer['billingCustomerId'] ?? null,

                    'tariffType' => $customer['tariffType'] ?? null,
                    'email' => $customer['email'] ?? null,

                    'addressList' => ! empty($customer['addressList'])
                        ? json_encode($customer['addressList'], JSON_UNESCAPED_UNICODE)
                        : null,

                    'billingPayerFlag' => $customer['billingPayerFlag'] ?? null,
                    'progressPaymentRate' => $customer['progressPaymentRate'] ?? null,
                    'tempPassword' => $customer['tempPassword'] ?? null,
                    'earchiveMailFlag' => $customer['earchiveMailFlag'] ?? null,
                    'bulkTariffFlag' => array_key_exists('bulkTariffFlag', $customer) ? $customer['bulkTariffFlag'] : null,

                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }, $allCustomers)));

            if (! empty($rows)) {
                $updateColumns = [
                    'data',
                    'search_text',
                    'commercialName',
                    'firstName',
                    'lastName',
                    'vknTckno',
                    'website',
                    'taxOffice',
                    'status',
                    'statusDate',
                    'statusDesc',
                    'customerType',
                    'configType',
                    'configTypeDesc',
                    'companyType',
                    'companyTypeDesc',
                    'channelRefId',
                    'dealerRefId',
                    'accountRefId',
                    'accountRefName',
                    'channelRefName',
                    'dealerRefName',
                    'sicilNo',
                    'isletmeMerkezi',
                    'mersisNo',
                    'contractSigned',
                    'activationReason',
                    'deactivationReason',
                    'billingCustomerId',
                    'tariffType',
                    'email',
                    'addressList',
                    'billingPayerFlag',
                    'progressPaymentRate',
                    'tempPassword',
                    'earchiveMailFlag',
                    'bulkTariffFlag',
                    'updated_at',
                ];

                DB::table('customers')->upsert(
                    $rows,
                    ['id'],
                    $updateColumns
                );
            }
        }

        $query = Customer::query();

        if ($search !== '') {
            $q = mb_strtolower($search, 'UTF-8');
            $likeSearch = '%' . $q . '%';
            $likeRaw = '%' . $search . '%';

            $query->where(function ($qq) use ($likeSearch, $likeRaw) {
                // Eski kayıtlar için (search_text fallback)
                $qq->whereRaw('search_text LIKE ?', [$likeSearch])
                    ->orWhere('commercialName', 'LIKE', $likeRaw)
                    ->orWhere('firstName', 'LIKE', $likeRaw)
                    ->orWhere('lastName', 'LIKE', $likeRaw)
                    ->orWhere('vknTckno', 'LIKE', $likeRaw)
                    ->orWhere('taxOffice', 'LIKE', $likeRaw)
                    ->orWhere('email', 'LIKE', $likeRaw)
                    ->orWhere('statusDesc', 'LIKE', $likeRaw)
                    ->orWhere('isletmeMerkezi', 'LIKE', $likeRaw)
                    ->orWhere('companyTypeDesc', 'LIKE', $likeRaw);
            });
        }

        $totalElements = (clone $query)->count();
        $totalPages = (int) ceil($totalElements / $size);

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        if ($page >= $totalPages) {
            $page = $totalPages - 1;
        }

        $offset = $page * $size;
        $pageItems = $query
            ->orderBy('id', 'asc')
            ->skip($offset)
            ->take($size)
            ->get();

        $data = $pageItems->pluck('data')->values()->all();

        return response()->json([
            'data' => $data,
            'pageable' => [
                'page' => $page,
                'size' => $size,
                'totalElements' => $totalElements,
                'totalPages' => $totalPages,
            ],
        ]);
    }

    /**
     * Izibiz API'den tüm müşterileri sayfa sayfa çeker.
     *
     * @return array<int, array<string, mixed>>
     */
    private function fetchAllCustomersFromIzibiz(): array
    {
        $tokenResponse = Http::asJson()->post(
            Config::get('services.izibiz.auth_url'),
            [
                'username' => Config::get('services.izibiz.username'),
                'password' => Config::get('services.izibiz.password'),
            ]
        );

        if (! $tokenResponse->successful()) {
            throw new \RuntimeException(json_encode([
                'message' => 'Token alınamadı',
                'error' => $tokenResponse->json(),
            ], JSON_UNESCAPED_UNICODE), $tokenResponse->status());
        }

        $tokenData = $tokenResponse->json('data');
        $accessToken = $tokenData['accessToken'] ?? null;

        if (! $accessToken) {
            throw new \RuntimeException(json_encode([
                'message' => 'Access token bulunamadı',
                'response' => $tokenResponse->json(),
            ], JSON_UNESCAPED_UNICODE), 500);
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
            throw new \RuntimeException(json_encode([
                'message' => 'Müşteriler çekilirken hata oluştu',
                'page' => 0,
                'error' => $firstResponse->json(),
            ], JSON_UNESCAPED_UNICODE), $firstResponse->status());
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
                throw new \RuntimeException(json_encode([
                    'message' => 'Müşteriler çekilirken hata oluştu',
                    'page' => $p,
                    'error' => $response->json(),
                ], JSON_UNESCAPED_UNICODE), $response->status());
            }

            $data = $response->json('data');
            $contents = $data['contents'] ?? [];

            if (! empty($contents) && is_array($contents)) {
                $allCustomers = array_merge($allCustomers, $contents);
            }
        }

        return $allCustomers;
    }
}

