<?php

namespace App\Rules;

use App\Models\Autorizaciones;
use Illuminate\Contracts\Validation\Rule;

class EventoDateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($form ,$to )
    {
        $this->form = $form;
        $this->to = $to;

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

        if($this->to==null || $this->form == null)
        return false;
      //  dd(\Carbon\Carbon::parse($this->to)->format('Y-m-d H:i') );
        $aut_evento = Autorizaciones::where('aut_tipo','=',4)
        ->where('aut_fecha_evento',"<",\Carbon\Carbon::parse($this->to)->format('Y-m-d H:i'))
        ->where('aut_fecha_evento_hasta',">", \Carbon\Carbon::parse($this->form)->format('Y-m-d H:i'))
        ->get();

        if($aut_evento->count()>0){
     //  dd($aut_evento);

            return false;
        }
        return true;


    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Rango de Fecha y hora invalido (no pueden cruzarse ).';
    }
}
