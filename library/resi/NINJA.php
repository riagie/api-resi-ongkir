<?php

namespace App\Library\Resi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class NINJA
{
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
                'SEND_DATE'         => preg_replace(
                                        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                        '$1-$2-$3 $4:$5:$6', 
                                        (new \DateTime($response['created_at']))->format('Y-m-d H:i:s')
                                    ),
                'SENDER'            => [
                    'NAME'          => $response['shipper_short_name'],
                    // 'ADDRESS'       => $response,
                ],
                'RECEIVER'          => [
                    'NAME'          => $response['pods'][0]['name'],
                    // 'ADDRESS'       => $response,
                    // 'DESCRIPTION'   => $response,
                    'DATE_TIME'     => (end($response['events'])['type'] == 'DELIVERY_SUCCESS')? 
                                        preg_replace(
                                            '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                            '$1-$2-$3 $4:$5:$6', 
                                            (new \DateTime(end($response['events'])['time']))->format('Y-m-d H:i:s')
                                        ) : 
                                        '',
                    'IMG'           => $response['pods'][0]['url'],
                ],
                'COURIER'           => [
                    // 'DELIVERY'      => $response,
                    // 'PICKUP'        => $response,
                ],
                'STATUS'            => $response['granular_status'],
                'DATE_TIME'         => preg_replace(
                                        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                        '$1-$2-$3 $4:$5:$6', 
                                        (new \DateTime($response['delivery_end_date']))->format('Y-m-d H:i:s')
                                    ),
                'TRACK_HISTORY'     => array_map(function ($track) {
                    return [
                        'DATE_TIME' => preg_replace(
                                        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                        '$1-$2-$3 $4:$5:$6', 
                                        (new \DateTime($track['time']))->format('Y-m-d H:i:s')
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
