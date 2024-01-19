<?php

namespace App\Utils;

class FormatNumber
{
    public static function format(?float $number = null, int $decimal = 2, string $separator = ','): string
    {
        return number_format($number, $decimal, $separator);
    }
}