<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class datounico implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    protected $dato;
    protected $tabla;
    protected $campo;
    protected $mensaje;

    public function __construct($dato, $tabla, $campo,$mensaje)
    {
        $this->dato = $dato;
        $this->tabla = $tabla;
        $this->campo = $campo;
         $this->mensaje = $mensaje;
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
        $this->dato = trim($this->dato);
       
        
        $existeDato = DB::table($this->tabla)->where($this->campo, $this->dato)->count();
//dd($this->dato,$existeDato);
        if ($existeDato > 0) {
            Log::info($this->campo.' '.$this->dato.'el dato existe');
            return false;
        } else {
            Log::info('el dato NO existe');
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
        return $this->mensaje;
    }
}
