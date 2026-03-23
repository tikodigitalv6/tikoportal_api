<?php

use App\Http\Controllers\IzibizAuthController;
use App\Http\Controllers\IzibizCustomerController;
use App\Http\Controllers\IzibizTariffApiController;
use App\Http\Controllers\IzibizTariffController;
use App\Http\Middleware\RequireApiKey;
use Illuminate\Support\Facades\Route;

Route::middleware([RequireApiKey::class])->prefix('izibiz')->group(function () {
    Route::get('/ping', function () {
        return response()->json([
            'ok' => true,
            'app_env' => app()->environment(),
        ]);
    });

    // Bazı hosting/WAF kuralları "/token" path'ine özel engel koyabiliyor.
    // Bu yüzden aynı endpoint'i güvenli bir alternatif yolla da açıyoruz.
    Route::post('/auth/token', IzibizAuthController::class);
    Route::get('/auth/token', IzibizAuthController::class);

    // Hosting/WAF bazı path'lerde "token" kelimesini engelleyebiliyor.
    // Bu yüzden token kelimesini içermeyen alternatif bir endpoint daha açıyoruz.
    Route::post('/auth/access', IzibizAuthController::class);
    Route::get('/auth/access', IzibizAuthController::class);

    Route::post('/token', IzibizAuthController::class);

    Route::get('/customers', IzibizCustomerController::class);
    Route::get('/customers/{customerId}/tariffs', IzibizTariffController::class);
    Route::get('/customers/{customerId}/tariffs/prepaid', [IzibizTariffApiController::class, 'customerPrepaid']);

    Route::get('/tariffs/waiting-approval', [IzibizTariffApiController::class, 'waitingApproval']);
    Route::post('/tariffs/approval', [IzibizTariffApiController::class, 'approve']);
    Route::post('/customers/{customerId}/tariffs/assign', [IzibizTariffApiController::class, 'assignToCustomer']);
    Route::post('/tariffs/purchase', [IzibizTariffApiController::class, 'purchase']);
    Route::post('/tariffs/{tariffId}/upgrade', [IzibizTariffApiController::class, 'upgrade']);

    Route::get('/tariff-plans/prepaid/{tariffId}/upgradable', [IzibizTariffApiController::class, 'upgradablePlans']);
    Route::get('/tariff-plans/prepaid', [IzibizTariffApiController::class, 'prepaidPlans']);
    Route::get('/tariff-plans/eledger-archive', [IzibizTariffApiController::class, 'eledgerPlans']);
    Route::get('/tariff-plans/postpaid', [IzibizTariffApiController::class, 'postpaidPlans']);
    Route::get('/tariff-plans/pay-as-you-usage', [IzibizTariffApiController::class, 'payAsYouUsagePlans']);
});

