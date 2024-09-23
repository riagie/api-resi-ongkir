<?php

namespace App\Library\Resi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class JNTCARGO
{
    public static function getDaysDifference($dateTime) {
        list($startDate, $endDate) = explode(' - ', $dateTime);

        return (new \DateTime($startDate))->diff(new \DateTime($endDate))->days;
    }

    public static function formatDateTime($dateTime) {
        return preg_replace_callback(
            '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/',
            function ($matches) {
                if (isset($matches[4])) {
                    return $matches[1] . '-' . $matches[2] . '-' . $matches[3] . ' ' . $matches[4] . ':' . $matches[5] . ':' . $matches[6];
                } else {
                    return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
                }
            },
            $dateTime
        );
    }

    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            "waybillNo" => $data['WAYBILL_NUMBER'],
            "searchWaybillOrCustomerOrderId" => 'searchWaybillOrCustomerOrderId'
        ];

        $requestData = json_encode($requestData);

        $requestHeader = [
            "Content-Type: application/json"
        ];

        // Send request and get response
        $response = Curl::request($_ENV['JNTCARGO_RESI'], 'POST', $requestData, $requestHeader);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);

            if (!$response['data'][0]['details']) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'WAYBILL_NUMBER'    => $data['WAYBILL_NUMBER'],
                'EXPEDITION'        => [
                    'NAME'          => $data['EXPEDITION'],
                    'SERVICE'       => $response['data'][0]['expressTypeName'],
                    // 'PRICE'         => (int) $response,
                    'METHOD'        => $response['data'][0]['sendName'],
                    // 'ESTIMATE_DELIVERY_DAYS' => $response,
                    'ORIGIN'        => $response['data'][0]['senderCityName'],
                    'DESTINATION'   => $response['data'][0]['receiverCityName'],
                ],
                'WEIGHT'            => ((int) $response['data'][0]['packageTotalWeight']) * 1000,
                // 'DESCRIPTION'       => $response,
                'SEND_DATE'         => JNTCARGO::formatDateTime($response['data'][0]['collectTime']),
                'SENDER'            => [
                    'NAME'          => $response['data'][0]['senderName'],
                    'ADDRESS'       => $response['data'][0]['senderDetailedAddress'],
                ],
                'RECEIVER'          => [
                    'NAME'          => $response['data'][0]['receiverName'],
                    'ADDRESS'       => $response['data'][0]['receiverDetailedAddress'],
                    // 'DESCRIPTION'   => $response,
                    'DATE_TIME'     => JNTCARGO::formatDateTime($response['data'][0]['details'][0]['scanTime']),
                    'IMG'           => $response['data'][0]['details'][0]['picUrl'][0],
                ],
                'COURIER'           => [
                    'DELIVERY'      => ($response['data'][0]['details'][0]['code'] == '100')? 
                                        $response['data'][0]['details'][0]['scanByName'] : '',
                    'PICKUP'        => end($response['data'][0]['details'])['scanByName'],
                ],
                // 'STATUS'            => $response,
                'DATE_TIME'         => JNTCARGO::formatDateTime($response['data'][0]['dispatchTime']),
                'TRACK_HISTORY'     => array_map(function ($track) {
                    return [
                        'DATE_TIME' => JNTCARGO::formatDateTime($track['scanTime']),
                        'STATUS'    => $track['change'],
                        'DESCRIPTION' => $track['customerTracking'],
                    ];
                }, $response['data'][0]['details'] ?? []),
            ];
            
            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
