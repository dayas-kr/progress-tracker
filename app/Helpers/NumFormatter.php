<?php

namespace App\Helpers;

class NumFormatter
{
    public static function format(int $input, array $options = ['format' => ['k' => 'k', 'm' => 'm', 'b' => 'b']]): string
    {
        $format = $options['format'];

        if ($input < 1000) {
            return (string) $input;
        }

        if ($input < 1000000) {
            return self::formatNumber($input, 1000, $format['k']);
        }

        if ($input < 1000000000) {
            return self::formatNumber($input, 1000000, $format['m']);
        }

        return self::formatNumber($input, 1000000000, $format['b']);
    }

    private static function formatNumber(int $number, int $divisor, string $suffix): string
    {
        $value = $number / $divisor;
        return ($value < 10 ? number_format($value, 1) : (int) $value) . $suffix;
    }
}
