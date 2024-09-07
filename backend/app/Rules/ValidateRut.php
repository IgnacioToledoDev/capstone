<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateRut implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
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
            $fail("El rut ingresado no es válido");
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'El RUT ingresado no es válido.';
    }
}
