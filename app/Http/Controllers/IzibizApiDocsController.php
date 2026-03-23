<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IzibizApiDocsController extends Controller
{
    public function ui(): \Illuminate\View\View
    {
        return view('izibiz_api_docs');
    }

    public function spec(Request $request): JsonResponse
    {
        $baseUrl = rtrim($request->getSchemeAndHttpHost(), '/');

        return response()->json([
            'openapi' => '3.0.3',
            'info' => [
                'title' => 'İzibiz TİKO API',
                'version' => '1.0.0',
                'description' => "Bu doküman, TİKO'nun İzibiz entegrasyonu için geliştirdiği REST API uçlarını içerir.\n\n- Bazı uçlar İzibiz'e proxy eder.\n- Bazı uçlar (müşteriler) performans için 15 dk cache kullanır.\n- Tüm cevaplar JSON'dur.",
            ],
            'tags' => [
                ['name' => 'Müşteriler', 'description' => 'Müşteri listeleme ve müşteri bazlı işlemler.'],
                ['name' => 'Tarifeler', 'description' => 'Tarife/paket atama, onay ve bekleyen kayıtlar.'],
                ['name' => 'Tarife Planları', 'description' => 'Kontör planları ve tarife plan listeleri.'],
            ],
            'servers' => [
                [
                    'url' => $baseUrl,
                ],
            ],
            'paths' => [
                '/api/izibiz/customers' => [
                    'get' => [
                        'tags' => ['Müşteriler'],
                        'operationId' => 'listCustomers',
                        'summary' => 'Müşterileri listele (cache + arama + sayfalama)',
                        'description' => "İlk çağrıda İzibiz'den tüm müşterileri çekip 15 dk cache'ler.\nSonraki çağrılarda arama ve sayfalama cache üzerinden yapılır.\n\nNot: `refresh=true` gönderirsen cache yeniden çekilir.",
                        'parameters' => [
                            [
                                'name' => 'page',
                                'in' => 'query',
                                'description' => '0 tabanlı sayfa numarası.',
                                'schema' => ['type' => 'integer', 'minimum' => 0],
                            ],
                            [
                                'name' => 'size',
                                'in' => 'query',
                                'description' => 'Sayfa başına kayıt sayısı (max 200).',
                                'schema' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 200],
                            ],
                            [
                                'name' => 'q',
                                'in' => 'query',
                                'description' => 'Arama (commercialName/firstName/lastName/vknTckno/taxOffice alanlarında).',
                                'schema' => ['type' => 'string'],
                            ],
                            [
                                'name' => 'refresh',
                                'in' => 'query',
                                'schema' => ['type' => 'boolean'],
                                'description' => 'true ise cache yenilenir (İzibiz’den tekrar full çekilir).',
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => ['type' => 'array', 'items' => ['type' => 'object']],
                                                'pageable' => [
                                                    'type' => 'object',
                                                    'properties' => [
                                                        'page' => ['type' => 'integer'],
                                                        'size' => ['type' => 'integer'],
                                                        'totalElements' => ['type' => 'integer'],
                                                        'totalPages' => ['type' => 'integer'],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/izibiz/auth/token' => [
                    'get' => [
                        'tags' => ['Auth'],
                        'operationId' => 'authTokenGet',
                        'summary' => 'İzibiz erişim token alma (GET alternatif)',
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/auth/access' => [
                    'get' => [
                        'tags' => ['Auth'],
                        'operationId' => 'authAccessGet',
                        'summary' => 'İzibiz erişim alma (GET alternatif)',
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/customers/{customerId}/tariffs' => [
                    'get' => [
                        'tags' => ['Müşteriler', 'Tarifeler'],
                        'operationId' => 'getCustomerTariffHistory',
                        'summary' => 'Müşteri tarife/paket geçmişi (tüm sayfalar)',
                        'description' => 'Seçilen müşteri için tarife/paket geçmişini İzibiz’den çekip tek listede döndürür (pageable.totalPages üzerinden tüm sayfaları dolaşır).',
                        'parameters' => [
                            [
                                'name' => 'customerId',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'İzibiz müşteri ID.',
                                'schema' => ['type' => 'integer'],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => ['type' => 'array', 'items' => ['type' => 'object']],
                                                'total' => ['type' => 'integer'],
                                                'totalPages' => ['type' => 'integer'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/izibiz/tariff-plans/prepaid' => [
                    'get' => [
                        'tags' => ['Tarife Planları'],
                        'operationId' => 'listPrepaidPlans',
                        'summary' => 'PREPAID kontör planlarını listele (visible=false)',
                        'description' => "İzibiz PREPAID planlarını çeker.\nİstek: `/v1/tariff-plans/PREPAID?visible=false` ve `X-On-Behalf-Of-Channel` header'ı kullanılır.",
                        'responses' => [
                            '200' => [
                                'description' => 'OK',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'data' => ['type' => 'array', 'items' => ['type' => 'object']],
                                                'error' => ['nullable' => true],
                                                'warning' => ['nullable' => true],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/izibiz/customers/{customerId}/tariffs/assign' => [
                    'post' => [
                        'tags' => ['Tarifeler'],
                        'operationId' => 'assignTariffToCustomer',
                        'summary' => 'Müşteriye kontör/tarife ata',
                        'description' => "Seçilen müşteri için kontör/tarife ataması yapar.\nGenelde sonuç `WAIT_APPROVAL` olur ve onay süreci bekler.",
                        'parameters' => [
                            [
                                'name' => 'customerId',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'İzibiz müşteri ID.',
                                'schema' => ['type' => 'integer'],
                            ],
                        ],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'id' => ['type' => 'integer', 'description' => 'Tarife plan ID'],
                                            'type' => ['type' => 'string', 'example' => 'PREPAID'],
                                            'name' => ['type' => 'string'],
                                            'amount' => ['type' => 'integer'],
                                            'price' => ['type' => 'number'],
                                            'startDate' => ['type' => 'string', 'format' => 'date'],
                                            'duration' => ['type' => 'integer'],
                                            'note' => ['type' => 'string', 'nullable' => true],
                                            'billed' => ['type' => 'boolean'],
                                            'paymentType' => ['type' => 'string', 'example' => 'CREDIT_CARD'],
                                        ],
                                        'required' => ['id', 'type', 'startDate', 'duration'],
                                    ],
                                    'example' => [
                                        'id' => 5592,
                                        'type' => 'PREPAID',
                                        'startDate' => '2026-03-18',
                                        'duration' => 6,
                                        'amount' => 100,
                                        'price' => 0,
                                        'billed' => false,
                                        'paymentType' => 'OTHER',
                                        'note' => 'Hediye',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariffs/waiting-approval' => [
                    'get' => [
                        'tags' => ['Tarifeler'],
                        'operationId' => 'listWaitingApprovalTariffs',
                        'summary' => 'Onay bekleyen tarifeleri listele',
                        'description' => 'WAIT_APPROVAL durumundaki kayıtları listeler (İzibiz filtrelerine göre).',
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariffs/approval' => [
                    'post' => [
                        'tags' => ['Tarifeler'],
                        'operationId' => 'approveTariff',
                        'summary' => 'Tarife onayla / reddet',
                        'description' => 'Tarife kaydını onaylar veya reddeder.',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'id' => ['type' => 'integer'],
                                            'type' => ['type' => 'string', 'example' => 'PREPAID'],
                                            'approved' => ['type' => 'boolean'],
                                        ],
                                        'required' => ['id', 'type', 'approved'],
                                    ],
                                    'example' => [
                                        'id' => 233012,
                                        'type' => 'PREPAID',
                                        'approved' => true,
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariffs/purchase' => [
                    'post' => [
                        'tags' => ['Tarifeler'],
                        'operationId' => 'purchaseTariff',
                        'summary' => 'Tarife satın alma',
                        'description' => 'Tarife satın alma işlemini başlatır.',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => ['type' => 'object'],
                                    'example' => [
                                        'id' => 261,
                                        'type' => 'PREPAID',
                                        'paymentType' => 'CREDIT_CARD',
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariffs/{tariffId}/upgrade' => [
                    'post' => [
                        'tags' => ['Tarifeler'],
                        'operationId' => 'upgradeTariff',
                        'summary' => 'Tarife yükseltme',
                        'description' => 'Mevcut tarifeyi başka bir tarifeye yükseltir.',
                        'parameters' => [
                            [
                                'name' => 'tariffId',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Mevcut tarife ID.',
                                'schema' => ['type' => 'integer'],
                            ],
                        ],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => ['type' => 'object'],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariff-plans/prepaid/{tariffId}/upgradable' => [
                    'get' => [
                        'tags' => ['Tarife Planları'],
                        'operationId' => 'listUpgradablePlans',
                        'summary' => 'Yükseltilebilecek planları getir',
                        'description' => 'Belirli bir tarife için yükseltilebilecek tarife planlarını listeler.',
                        'parameters' => [
                            [
                                'name' => 'tariffId',
                                'in' => 'path',
                                'required' => true,
                                'description' => 'Tarife ID.',
                                'schema' => ['type' => 'integer'],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariff-plans/eledger-archive' => [
                    'get' => [
                        'tags' => ['Tarife Planları'],
                        'operationId' => 'listEledgerArchivePlans',
                        'summary' => 'E-Defter saklama tarifeleri',
                        'description' => 'E-Defter saklama tarife planlarını listeler.',
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariff-plans/postpaid' => [
                    'get' => [
                        'tags' => ['Tarife Planları'],
                        'operationId' => 'listPostpaidPlans',
                        'summary' => 'Faturalı tarifeler',
                        'description' => 'Faturalı tarife planlarını listeler.',
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
                '/api/izibiz/tariff-plans/pay-as-you-usage' => [
                    'get' => [
                        'tags' => ['Tarife Planları'],
                        'operationId' => 'listPayAsYouUsagePlans',
                        'summary' => 'Serbest kullanım tarifeleri',
                        'description' => 'Kullandıkça öde / serbest kullanım tarife planlarını listeler.',
                        'responses' => [
                            '200' => ['description' => 'OK'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}

