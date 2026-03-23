<?php

namespace App\Http\Controllers;

use App\Services\IzibizTariffService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IzibizTariffApiController extends Controller
{
    public function __construct(
        protected IzibizTariffService $service
    ) {
    }

    public function customerPrepaid(int $customerId, Request $request): JsonResponse
    {
        $result = $this->service->getCustomerPrepaidTariffs($customerId, $request->query());

        return response()->json($result['body'], $result['status']);
    }

    public function waitingApproval(): JsonResponse
    {
        $result = $this->service->getWaitingApprovalTariffs();

        return response()->json($result['body'], $result['status']);
    }

    public function approve(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string',
            'approved' => 'required|boolean',
        ]);

        $result = $this->service->approveTariff($payload);

        return response()->json($result['body'], $result['status']);
    }

    public function assignToCustomer(int $customerId, Request $request): JsonResponse
    {
        $payload = $request->all();

        $result = $this->service->assignTariffToCustomer($customerId, $payload);

        return response()->json($result['body'], $result['status']);
    }

    public function purchase(Request $request): JsonResponse
    {
        $payload = $request->all();

        $result = $this->service->purchaseTariff($payload);

        return response()->json($result['body'], $result['status']);
    }

    public function upgrade(int $currentTariffId, Request $request): JsonResponse
    {
        $payload = $request->all();

        $result = $this->service->upgradeTariff($currentTariffId, $payload);

        return response()->json($result['body'], $result['status']);
    }

    public function upgradablePlans(int $currentTariffId): JsonResponse
    {
        $result = $this->service->getUpgradablePlans($currentTariffId);

        return response()->json($result['body'], $result['status']);
    }

    public function prepaidPlans(): JsonResponse
    {
        $result = $this->service->getPrepaidPlans();

        return response()->json($result['body'], $result['status']);
    }

    public function eledgerPlans(): JsonResponse
    {
        $result = $this->service->getEledgerArchivePlans();

        return response()->json($result['body'], $result['status']);
    }

    public function postpaidPlans(): JsonResponse
    {
        $result = $this->service->getPostpaidPlans();

        return response()->json($result['body'], $result['status']);
    }

    public function payAsYouUsagePlans(): JsonResponse
    {
        $result = $this->service->getPayAsYouUsagePlans();

        return response()->json($result['body'], $result['status']);
    }
}

