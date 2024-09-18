<?php

namespace App\Library\Resi;

use App\Helpers\Curl;
use App\Helpers\ResponseValidator;

class IDEXPRESS
{
    public static function process(array $data): ?array
    {
        // Prepare request data
        $requestData = [
            "waybillNo" => $data['WAYBILL_NUMBER']
        ];
        
        // Send request and get response
        $response = Curl::request($_ENV['IDEXPRESS_RESI'], 'GET', $requestData);
        
        // Check if response is valid
        if ($response && !is_null($response) && $response !== false) {
            $response = json_decode($response, true);

            if ($response['total'] == 0) {
                return null;
            }
            
            // Map extracted data to response data
            $responseData = [
                'WAYBILL_NUMBER'    => $data['WAYBILL_NUMBER'],
                'EXPEDITION'        => [
                    'NAME'          => $data['EXPEDITION'],
                    'SERVICE'       => $response['data'][0]['serviceType'],
                    // 'PRICE'         => (int) $response,
                    // 'METHOD'        => $response,
                    // 'ESTIMATE_DELIVERY_DAYS' => $response,
                    'ORIGIN'        => $response['data'][0]['senderDistrictName'],
                    'DESTINATION'   => $response['data'][0]['recipientDistrictName'],
                ],
                // 'WEIGHT'            => (int) $response['weight'],
                // 'DESCRIPTION'       => $response,
                'SEND_DATE'         => (end($response['data'][0]['scanLineVOS'])['operationType'] == '00')? 
                                        preg_replace(
                                            '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                            '$1-$2-$3 $4:$5:$6', 
                                            end($response['data'][0]['scanLineVOS'])['recordTime']
                                        ) : '',
                'SENDER'            => [
                    'NAME'          => $response['data'][0]['senderName'],
                    'ADDRESS'       => $response['data'][0]['senderCityName'] .' '. $response['data'][0]['senderDistrictName'],
                ],
                'RECEIVER'          => [
                    'NAME'          => $response['data'][0]['recipientName'],
                    'ADDRESS'       => $response['data'][0]['recipientCityName'] .' '. $response['data'][0]['recipientDistrictName'],
                    // 'DESCRIPTION'   => $response,
                    'DATE_TIME'     => ($response['data'][0]['scanLineVOS'][0]['operationType'] == '10')? 
                                        preg_replace(
                                            '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                            '$1-$2-$3 $4:$5:$6', 
                                            $response['data'][0]['scanLineVOS'][0]['recordTime']
                                        ) : '',
                    'IMG'           => ($response['data'][0]['scanLineVOS'][0]['operationType'] == '10')? 
                                        $response['data'][0]['scanLineVOS'][0]['photoUrl'] : '',
                ],
                'COURIER'           => [
                    'DELIVERY'      => ($response['data'][0]['scanLineVOS'][0]['operationType'] == '10')? 
                                        $response['data'][0]['scanLineVOS'][0]['operationUserName'] : '',
                    'PICKUP'        => end($response['data'][0]['scanLineVOS'])['operationUserName'],
                ],
                'STATUS'            => $response['data'][0]['waybillStatus'],
                'DATE_TIME'         => preg_replace(
                                        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                        '$1-$2-$3 $4:$5:$6', 
                                        $response['data'][0]['scanLineVOS'][0]['operationTime']
                                    ),
                'TRACK_HISTORY'     => array_map(function ($track) {
                    return [
                        'DATE_TIME' => preg_replace(
                                        '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', 
                                        '$1-$2-$3 $4:$5:$6', 
                                        $track['recordTime']
                                    ),
                        'STATUS'    => $track['operationType'],
                        'DESCRIPTION' => $track['operationBranchName'] .' '. $track['previousBranchName'] .' '. $track['nextLocationName'],
                    ];
                }, $response['data'][0]['scanLineVOS'] ?? []),
            ];
            
            // Validate and return response data
            return ResponseValidator::validate($responseData);
        }

        return null;
    }
}
