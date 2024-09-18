<?php

namespace App\Library\Resi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class NINJA
{
    public static function getDaysDifference($dateTime) {
        list($startDate, $endDate) = explode(' - ', $dateTime);

        return (new \DateTime($startDate))->diff(new \DateTime($endDate))->days;
    }

    public static function formatDateTime($dateTime) {
        return preg_replace_callback(
            '/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z/',
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
            "tracking_id" => $data['WAYBILL_NUMBER']
        ];
        
        // Send request and get response
        $response = Curl::request($_ENV['NINJA_RESI'], 'GET', $requestData);

        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);

            if ($response['error']) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'WAYBILL_NUMBER'    => $data['WAYBILL_NUMBER'],
                'EXPEDITION'        => [
                    'NAME'          => $data['EXPEDITION'],
                    'SERVICE'       => $response['service_type'],
                    'PRICE'         => (int) $response['goods_amount'],
                    // 'METHOD'        => $response,
                    // 'ESTIMATE_DELIVERY_DAYS' => $response,
                    // 'ORIGIN'        => $response,
                    // 'DESTINATION'   => $response,
                ],
                // 'WEIGHT'            => (int) $response['weight'],
                // 'DESCRIPTION'       => $response,
                'SEND_DATE'         => NINJA::formatDateTime($response['created_at']),
                'SENDER'            => [
                    'NAME'          => $response['shipper_short_name'],
                    // 'ADDRESS'       => $response,
                ],
                'RECEIVER'          => [
                    'NAME'          => $response['pods'][0]['name'],
                    // 'ADDRESS'       => $response,
                    // 'DESCRIPTION'   => $response,
                    'DATE_TIME'     => (end($response['events'])['type'] == 'DELIVERY_SUCCESS')? 
                                        NINJA::formatDateTime(end($response['events'])['time']) : '',
                    'IMG'           => $response['pods'][0]['url'],
                ],
                'COURIER'           => [
                    // 'DELIVERY'      => $response,
                    // 'PICKUP'        => $response,
                ],
                'STATUS'            => $response['granular_status'],
                'DATE_TIME'         => NINJA::formatDateTime($response['delivery_end_date']),
                'TRACK_HISTORY'     => array_map(function ($track) {
                    return [
                        'DATE_TIME' => preg_replace(
                                        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                        '$1-$2-$3 $4:$5:$6', 
                                        NINJA::formatDateTime($track['time'])
                                    ),
                        'STATUS'    => $track['type'],
                        'DESCRIPTION' => $track['tags'][0] .' '. $track['tags'][1] .' '. $track['data']['hub_name'],
                    ];
                }, $response['events'] ?? []),
            ];
            
            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
