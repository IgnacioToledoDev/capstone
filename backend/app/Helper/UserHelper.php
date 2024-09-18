<?php

namespace App\Helper;

class UserHelper
{

    public function validateRut($rut): bool
    {
        $value = str_replace('.', '', $value); // remove char .
        $value = str_replace('-', '', $value); // remove char -
        $dv = substr($value, -1);

        $value = substr($value, 0, -1); // remove verified digit

        $acc = 1;
        $count = 0;
        while ($value != 0) {
            $acc = ($acc + (intval($value) % 10) * (9 - $count++ % 6)) % 11;
            $value = (int)($value / 10);
        }

        $dvCalculate = $acc ? chr($acc + 47) : 'K';
        $cleanDv = str_replace('/', '', $dvCalculate);

        if (strtolower($dv) != strtolower($cleanDv)) {
            return false;
        }

        return true;
    }
}
