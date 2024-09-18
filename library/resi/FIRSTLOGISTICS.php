<?php

namespace App\Library\Resi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class FIRSTLOGISTICS
{
    public static function getDaysDifference($dateTime) {
        list($startDate, $endDate) = explode(' - ', $dateTime);

        return (new \DateTime($startDate))->diff(new \DateTime($endDate))->days;
    }

    public static function formatDateTime($dateTime) {
        return preg_replace_callback(
            '/(\d{2})-(\w{3})-(\d{4})(?: (\d{2}):(\d{2}):(\d{2}))?/',
            function ($matches) {
                $months = [
                    'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04',
                    'May' => '05', 'Jun' => '06', 'Jul' => '07', 'Aug' => '08',
                    'Sep' => '09', 'Oct' => '10', 'Nov' => '11', 'Dec' => '12'
                ];
                
                $month = $months[$matches[2]];
                
                if (isset($matches[4])) {
                    return $matches[3] . '-' . $month . '-' . $matches[1] . ' ' . $matches[4] . ':' . $matches[5] . ':' . $matches[6];
                } else {
                    return $matches[3] . '-' . $month . '-' . $matches[1];
                }
            },
            $dateTime
        );
    }

    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            "trc" => $data['WAYBILL_NUMBER']
        ];
        
        // Send request and get response
        $response = Curl::request($_ENV['FIRSTLOGISTICS_RESI'], 'GET', $requestData);
        
        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);

            if ($response['Error']) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'WAYBILL_NUMBER'    => $data['WAYBILL_NUMBER'],
                'EXPEDITION'        => [
                    'NAME'          => $data['EXPEDITION'],
                    'SERVICE'       => $response['service'],
                    // 'PRICE'         => (int) $response,
                    // 'METHOD'        => $response,
                    'ESTIMATE_DELIVERY_DAYS' => FIRSTLOGISTICS::getDaysDifference($response['dlv_estimate']),
                    'ORIGIN'        => $response['consignee']['origin_code'],
                    'DESTINATION'   => $response['consignee']['destination_code'],
                ],
                'WEIGHT'            => (int) $response['weight'],
                // 'DESCRIPTION'       => $response,
                'SEND_DATE'         => FIRSTLOGISTICS::formatDateTime($response['consignee']['send_date']),
                'SENDER'            => [
                    'NAME'          => $response['shipper'],
                    // 'ADDRESS'       => $response,
                ],
                'RECEIVER'          => [
                    'NAME'          => $response['consignee']['consignee_name'],
                    'ADDRESS'       => $response['consignee']['address'] .' '. 
                                        $response['consignee']['destination_district'] .' '. 
                                        $response['consignee']['destination_city'],
                    // 'DESCRIPTION'   => $response,
                    'DATE_TIME'     => FIRSTLOGISTICS::formatDateTime($response['consignee']['last_status']['date_time']),
                    // 'IMG'           => $response,
                ],
                'COURIER'           => [
                    // 'DELIVERY'      => $response,
                    // 'PICKUP'        => $response,
                ],
                'STATUS'            => $response['consignee']['last_status']['status'],
                'DATE_TIME'         => FIRSTLOGISTICS::formatDateTime($response['consignee']['last_status']['track_history'][0]['date_time']),
                'TRACK_HISTORY'     => array_map(function ($track) {
                    return [
                        'DATE_TIME' => FIRSTLOGISTICS::formatDateTime($track['date_time']),
                        'STATUS'    => $track['receiver'],
                        'DESCRIPTION' => $track['city'] .' '. $track['status'],
                    ];
                }, $response['consignee']['last_status']['track_history'] ?? []),
            ];
            
            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
