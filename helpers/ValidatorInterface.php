<?php

namespace App\Helpers;

interface ValidatorInterface
{
    public static function validate(array $data): array;
}
