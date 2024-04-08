<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class rut implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       //dd($value);
        if(strlen($value)<1){
            return false;
        }
        if (!str_contains($value, '-')) {
            return false;
        }
        $rutCompleto = explode('-', $value);
        $rutSolo = $rutCompleto[0];
        $dv = strtoupper($rutCompleto[1]);
        if ($dv == 'K') {
            $dv = 10;
        }
        if ($dv == 0) {
            $dv = 11;
        }
        $rutSolo = str_replace('.', '', $rutSolo);
        $rutArray = str_split($rutSolo);
        $suma = 0;
        $secuencia = [2, 3, 4, 5, 6, 7, 2, 3, 4, 5, 6, 7, 2, 3, 4, 5, 6, 7, 2, 3, 4, 5, 6, 7];
        $i = 0;
        for ($x = sizeof($rutArray) - 1; $x >= 0; $x--) {
            $suma = ($rutArray[$x] * $secuencia[$i]) + $suma;
            $i++;
        }
        $modulo = $suma % 11;
        $dvc = 11 - $modulo;
        if ($dvc == $dv) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'RUT inválido';
    }
}
