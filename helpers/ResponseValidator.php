<?php

namespace App\Helpers;

class ResponseValidator implements ValidatorInterface
{
    private static $requiredParameters = [
        'WAYBILL_NUMBER',
        'EXPEDITION',
        'WEIGHT',
        'DESCRIPTION',
        'SEND_DATE',
        'SENDER',
        'RECEIVER',
        'COURIER',
        'STATUS',
        'DATE_TIME',
        'TRACK_HISTORY',
    ];

    private static $nestedParameters = [
        'EXPEDITION' => ['NAME', 'SERVICE', 'PRICE', 'METHOD', 'ESTIMATE_DELIVERY_DAYS', 'ORIGIN', 'DESTINATION'],
        'SENDER'     => ['NAME', 'ADDRESS'],
        'RECEIVER'   => ['NAME', 'ADDRESS', 'DESCRIPTION', 'DATE_TIME', 'IMG'],
        'COURIER'    => ['DELIVERY', 'PICKUP'],
        'TRACK_HISTORY' => ['DATE_TIME', 'STATUS', 'DESCRIPTION'],
    ];

    private static function validateNested(array $data, array $requiredFields): array
    {
        if (isset($data[0]) && is_array($data[0])) {
            return array_map(function ($item) use ($requiredFields) {
                return self::validateNested($item, $requiredFields);
            }, $data);
        }

        $validatedNestedData = array_fill_keys($requiredFields, '');

        foreach ($requiredFields as $field) {
            if (isset($data[$field])) {
                $validatedNestedData[$field] = $data[$field];
            } else {
                $validatedNestedData[$field] = '';
            }
        }

        return $validatedNestedData;
    }

    public static function validate(array $data): array
    {
        $validatedData = array_fill_keys(self::$requiredParameters, '');

        foreach (self::$requiredParameters as $param) {
            if (isset($data[$param])) {
                if (is_array($data[$param]) && isset(self::$nestedParameters[$param])) {
                    $validatedData[$param] = self::validateNested($data[$param], self::$nestedParameters[$param]);
                } else {
                    $validatedData[$param] = $data[$param];
                }
            } else {
                $validatedData[$param] = '';
            }
        }

        return $validatedData;
    }
}
