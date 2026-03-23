<?php

use App\Http\Controllers\IzibizAuthController;
use App\Http\Controllers\IzibizApiDocsController;
use App\Http\Controllers\IzibizCustomerController;
use App\Http\Controllers\IzibizTariffApiController;
use App\Http\Controllers\IzibizTariffController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tikoportal_api_home');
});

Route::get('/tiko_izibiz_yonetim', function () {
    return view('tiko_izibiz_yonetim');
});

Route::get('/izibiz_api', [IzibizApiDocsController::class, 'ui']);
Route::get('/izibiz_api/openapi.json', [IzibizApiDocsController::class, 'spec']);
