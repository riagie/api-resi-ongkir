<?php

/*
 * Configure routes
 */
$app->get('/resi/{expedition}/{waybill_number}', function ($expedition, $waybill_number) use ($app) {
    $condition = \App\Helpers\Expedition::find(substr($waybill_number, 0, 3)) ??
        ['EXPEDITION' => strtoupper($expedition)];

    if (empty($_ENV[$condition['EXPEDITION']])) {
        return $app->response->setStatusCode(501)->setJsonContent([
            'RC' => '0501',
            'RCM' => 'NOT IMPLEMENTED',
        ])->send();
    }

    $library = 'App\\Library\\Resi\\' . $condition['EXPEDITION'];
    if (!class_exists($library)) {
        return $app->response->setStatusCode(502)->setJsonContent([
            'RC' => '0502',
            'RCM' => 'BAD GATEWAY',
        ])->send();
    }

    $detail = $library::process([
        'EXPEDITION' => strtoupper($expedition),
        'WAYBILL_NUMBER' => $waybill_number
    ]);

    if (empty($detail)) {
        return $app->response->setStatusCode(502)->setJsonContent([
            'RC' => '0502',
            'RCM' => 'BAD GATEWAY',
        ])->send();
    }

    return $app->response->setStatusCode(200)->setJsonContent([
        'RC' => '0200',
        'RCM' => 'SUCCESS',
        'DATA' => $detail,
    ])->send();
});

$app->get('/service', function () use ($app) {
    $h = array_map(function ($value) {
        $value['STATUS']['RESI'] = !empty($_ENV[$value['EXPEDITION'] . '_RESI']) ? true : false;
        $value['STATUS']['ONGKIR'] = !empty($_ENV[$value['EXPEDITION'] . '_ONGKIR']) ? true : false;
        return $value;
    }, App\Helpers\Expedition::all());

    return $app->response->setStatusCode(200)->setJsonContent([
        'RC' => '0200',
        'RCM' => 'SUCCESS',
        'DATA' => $h,
    ])->send();
});

$app->get('/', function () use ($app) {
    return $app->response->setStatusCode(200)->setJsonContent([
        '/resi/:expedition/:waybill_number' => [
            'method' => 'GET',
            'parameters' => [
                'expedition' => 'JNE',
                'waybill_number' => '004339912354'
            ]
        ],
        '/service' => [
            'method' => 'GET'
        ]
    ])->send();
});

/*
 * 404 Not Found handler
 */
$app->notFound(function () use ($app) {
    return $app->response->setStatusCode(404)->setJsonContent([
        'RC' => '0404',
        'RCM' => 'NOT FOUND'
    ])->send();
});
