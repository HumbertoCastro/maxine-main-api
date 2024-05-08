<?php

namespace App\Util;

class formatedValueToDecimal {

    public static function formatNumber($number) {
        // Replace comma with dot and remove thousands separators
        $number = str_replace(',', '.', str_replace('.', '', $number));

        // Convert to float
        return (float) $number;
    }

}
