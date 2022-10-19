<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Country;
use App\Models\Autorizaciones;


class EmailIngresos extends Mailable
{
    use Queueable, SerializesModels;

    public $ingr;
    public $type;


    public function __construct($ingr,$type )
    {
        $this->ingr = $ingr;
        $this->type = $type;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
            $subect = $this->type==1 ?'Noti - Ingreso' :'Noti - Egreso';

        return $this->markdown('emails.ingresos.ingresos')
        ->from('noreply@seguridadunica.com',$subect)
        ->subject($subect);
    }
}
