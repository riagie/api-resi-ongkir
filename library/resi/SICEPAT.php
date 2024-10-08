<?php

namespace App\Library\Resi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class SICEPAT
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
            'waybill' => $data['WAYBILL_NUMBER']
        ];

        $requestHeader = [
            'api-key: ' . $_ENV['SICEPAT_KEY'],
        ];
        
        // Send request and get response
        $response = Curl::request($_ENV['SICEPAT_RESI'], 'GET', $requestData, $requestHeader);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);

            if ($response['sicepat']['status']['code'] != 200) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'WAYBILL_NUMBER'    => $data['WAYBILL_NUMBER'],
                'EXPEDITION'        => [
                    'NAME'          => $data['EXPEDITION'],
                    'SERVICE'       => $response['sicepat']['result']['service'],
                    'PRICE'         => (int) $response['sicepat']['result']['totalprice'],
                    // 'METHOD'        => $response,
                    // 'ESTIMATE_DELIVERY_DAYS' => $response,
                    'ORIGIN'        => $response['sicepat']['result']['kodeasal'],
                    'DESTINATION'   => $response['sicepat']['result']['kodetujuan'],
                ],
                'WEIGHT'            => ((int) $response['sicepat']['result']['weight']) * 1000,
                // 'DESCRIPTION'       => $response,
                'SEND_DATE'         => SICEPAT::formatDateTime($response['sicepat']['result']['send_date']),
                'SENDER'            => [
                    'NAME'          => $response['sicepat']['result']['sender'],
                    'ADDRESS'       => $response['sicepat']['result']['sender_address'],
                ],
                'RECEIVER'          => [
                    'NAME'          => $response['sicepat']['result']['receiver_name'],
                    'ADDRESS'       => $response['sicepat']['result']['receiver_address'],
                    'DESCRIPTION'   => $response['sicepat']['result']['POD_receiver'],
                    'DATE_TIME'     => SICEPAT::formatDateTime($response['sicepat']['result']['POD_receiver_time']),
                    'IMG'           => $response['sicepat']['result']['pod_img_path'],
                ],
                'COURIER'           => [
                    // 'DELIVERY'      => $response,
                    // 'PICKUP'        => $response,
                ],
                'STATUS'            => $response['sicepat']['result']['last_status']['status'],
                'DATE_TIME'         => SICEPAT::formatDateTime($response['sicepat']['result']['last_status']['date_time']),
                'TRACK_HISTORY'     => array_map(function ($track) {
                    return [
                        'DATE_TIME' => SICEPAT::formatDateTime($track['date_time']),
                        'STATUS'    => $track['status'],
                        'DESCRIPTION' => $track['city'],
                    ];
                }, $response['sicepat']['result']['track_history'] ?? []),
            ];            

            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
