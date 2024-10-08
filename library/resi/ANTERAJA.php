<?php

namespace App\Library\Resi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class ANTERAJA
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
            [
                "codes" => $data['WAYBILL_NUMBER']
            ]
        ];

        $requestData = json_encode($requestData);

        $requestHeader = [
            "mv: 1.2",
            "source: aca_android",
            "content-type: application/json; charset=UTF-8",
            "user-agent: okhttp/3.10.0"
        ];
        
        // Send request and get response
        $response = Curl::request($_ENV['ANTERAJA_RESI'], 'POST', $requestData, $requestHeader);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);

            if ($response['status'] != 200) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'WAYBILL_NUMBER'    => $data['WAYBILL_NUMBER'],
                'EXPEDITION'        => [
                    'NAME'          => $data['EXPEDITION'],
                    'SERVICE'       => $response['content'][0]['detail']['service_code'],
                    'PRICE'         => (int) $response['content'][0]['detail']['actual_amount'],
                    // 'METHOD'        => $response,
                    'ESTIMATE_DELIVERY_DAYS' => $response['content'][0]['detail']['estimated_date'],
                    // 'ORIGIN'        => $response,
                    // 'DESTINATION'   => $response,
                ],
                'WEIGHT'            => (int) $response['content'][0]['detail']['weight'],
                // 'DESCRIPTION'       => $response,
                'SEND_DATE'         => ANTERAJA::formatDateTime(end($response['content'][0]['history'])['timestamp']),
                'SENDER'            => [
                    'NAME'          => $response['content'][0]['detail']['sender']['name'],
                    'ADDRESS'       => $response['content'][0]['detail']['sender_address']['address'],
                ],
                'RECEIVER'          => [
                    'NAME'          => $response['content'][0]['detail']['receiver']['name'],
                    'ADDRESS'       => $response['content'][0]['detail']['receiver']['address'],
                    // 'DESCRIPTION'   => $response,
                    // 'DATE_TIME'     => preg_replace('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', '$1-$2-$3 $4:$5:$6', $response),
                    // 'IMG'           => $response,
                ],
                'COURIER'           => [
                    // 'DELIVERY'      => $response,
                    // 'PICKUP'        => $response,
                ],
                // 'STATUS'            => $response,
                'DATE_TIME'         => ANTERAJA::formatDateTime($response['content'][0]['history'][0]['timestamp']),
                'TRACK_HISTORY'     => array_map(function ($track) {
                    return [
                        'DATE_TIME' => ANTERAJA::formatDateTime($track['timestamp']),
                        'STATUS'    => $track['tracking_code'],
                        'DESCRIPTION' => $track['message']['id'],
                    ];
                }, $response['content'][0]['history'] ?? []),
            ];
            
            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
