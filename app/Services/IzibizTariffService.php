<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class IzibizTariffService
{
    protected function getAccessToken(): ?string
    {
        $authUrl = Config::get('services.izibiz.auth_url');

        $response = Http::asJson()->post($authUrl, [
            'username' => Config::get('services.izibiz.username'),
            'password' => Config::get('services.izibiz.password'),
        ]);

        if (! $response->successful()) {
            return null;
        }

        return $response->json('data.accessToken');
    }

    protected function baseRequest(array $headers = []): ?PendingRequest
    {
        $token = $this->getAccessToken();

        if (! $token) {
            return null;
        }

        return Http::withToken($token)
            ->withHeaders(array_merge([
                'Accept-Language' => 'tr-TR',
            ], $headers));
    }

    protected function baseUrl(string $path): string
    {
        $base = rtrim(Config::get('services.izibiz.base_url'), '/');
        $version = trim(Config::get('services.izibiz.version', 'v1'), '/');

        return $base.'/'.$version.'/'.ltrim($path, '/');
    }

    public function getCustomerPrepaidTariffs(int $customerId, array $query = []): array
    {
        $request = $this->baseRequest([
            'X-On-Behalf-Of-Channel' => Config::get('services.izibiz.channel_id'),
            'X-On-Behalf-Of-Customer' => $customerId,
        ]);

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $response = $request->get($this->baseUrl('tariffs/prepaid'), $query);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function getWaitingApprovalTariffs(): array
    {
        $request = $this->baseRequest([
            'X-On-Behalf-Of-Channel' => Config::get('services.izibiz.channel_id'),
        ]);

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        // İzibiz tarafında WAIT_APPROVAL filtrelemesi en stabil şekilde /tariffs üzerinden çalışıyor
        $response = $request->get($this->baseUrl('tariffs'), [
            'sortProperty' => 'createDate',
            'page' => 0,
            'sort' => 'desc',
            'status' => 'WAIT_APPROVAL',
            'pageSize' => 250,
        ]);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function approveTariff(array $payload): array
    {
        $request = $this->baseRequest([
            'X-On-Behalf-Of-Channel' => Config::get('services.izibiz.channel_id'),
        ]);

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $response = $request->post($this->baseUrl('tariffs/approval'), $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function assignTariffToCustomer(int $customerId, array $payload): array
    {
        $request = $this->baseRequest([
            'X-On-Behalf-Of-Customer' => $customerId,
            'X-On-Behalf-Of-Channel' => Config::get('services.izibiz.channel_id'),
        ]);

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $response = $request->post($this->baseUrl('tariffs'), $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function purchaseTariff(array $payload): array
    {
        $request = $this->baseRequest();

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $response = $request->post($this->baseUrl('tariffs/purchase'), $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function upgradeTariff(int $currentTariffId, array $payload): array
    {
        $request = $this->baseRequest();

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $path = 'tariffs/PREPAID/upgrade/'.$currentTariffId;
        $response = $request->post($this->baseUrl($path), $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function getUpgradablePlans(int $currentTariffId): array
    {
        $request = $this->baseRequest();

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $path = 'tariff-plans/prepaid/'.$currentTariffId.'/upgradable';
        $response = $request->get($this->baseUrl($path));

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function getPrepaidPlans(): array
    {
        $request = $this->baseRequest([
            'X-On-Behalf-Of-Channel' => Config::get('services.izibiz.channel_id'),
        ]);

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $response = $request->get($this->baseUrl('tariff-plans/PREPAID'), [
            'visible' => 'false',
        ]);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }

    public function getEledgerArchivePlans(): array
    {
        return $this->simplePlansGet('tariff-plans/EledgerArchive');
    }

    public function getPostpaidPlans(): array
    {
        return $this->simplePlansGet('tariff-plans/postpaid');
    }

    public function getPayAsYouUsagePlans(): array
    {
        return $this->simplePlansGet('tariff-plans/payasyouusage');
    }

    protected function simplePlansGet(string $path): array
    {
        $request = $this->baseRequest([
            'X-On-Behalf-Of-Channel' => Config::get('services.izibiz.channel_id'),
        ]);

        if (! $request) {
            return [
                'ok' => false,
                'status' => 500,
                'body' => [
                    'message' => 'Token alınamadı',
                ],
            ];
        }

        $response = $request->get($this->baseUrl($path));

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json(),
        ];
    }
}

