<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class rutunique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $rut;
    protected $tabla;
    protected $campo;

    public function __construct($rut, $tabla, $campo)
    {
        $this->rut = $rut;
        $this->tabla = $tabla;
        $this->campo = $campo;
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
        $this->rut = trim($this->rut);
        // 2-Quitar puntos
        $this->rut = str_replace(".", "", $this->rut);
        // 3- Separar rut e dv
        $rutCompleto = explode("-", $this->rut);
        $this->rut=$rutCompleto[0];
        
        $existeDato = DB::table($this->tabla)->where($this->campo, $this->rut)->exists();

        if ($existeDato) {
            return false;
        } else {
           return true;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El R.U.N. ya está en uso.';
    }
}
