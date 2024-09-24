<?php

namespace App\Rules;

use App\Helper\UserHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateRut implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
       $helper = new UserHelper();
       $isValid = $helper->validateRut($value);

       if(!$isValid) {
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
