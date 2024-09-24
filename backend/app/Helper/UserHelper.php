<?php

namespace App\Helper;

class UserHelper
{

    public function validateRut($rut): bool
    {
        $rut = str_replace('.', '', $rut); // remove char .
        $rut = str_replace('-', '', $rut); // remove char -
        $dv = substr($rut, -1);

        $rut = substr($rut, 0, -1); // remove verified digit

        $acc = 1;
        $count = 0;
        while ($rut != 0) {
            $acc = ($acc + (intval($rut) % 10) * (9 - $count++ % 6)) % 11;
            $rut = (int)($rut / 10);
        }

        $dvCalculate = $acc ? chr($acc + 47) : 'K';
        $cleanDv = str_replace('/', '', $dvCalculate);

        if (strtolower($dv) != strtolower($cleanDv)) {
            return false;
        }

        return true;
    }
}
