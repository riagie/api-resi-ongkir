<?php

namespace App\Helpers;

class Expedition
{
    private static $expeditions = [
        [
            "EXPEDITION" => "SICEPAT",
            "CODE" => ["004"]
        ],
        [
            "EXPEDITION" => "ANTERAJA",
            "CODE" => []
        ],
        [
            "EXPEDITION" => "NINJA",
            "CODE" => []
        ]
    ];

    public static function all()
    {
        return self::$expeditions;
    }

    public static function find(string $code): ?array
    {
        foreach (self::$expeditions as $expedition) {
            if (in_array($code, $expedition['CODE'])) {
                return $expedition;
            }
        }

        return null;
    }
}
