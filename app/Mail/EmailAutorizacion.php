<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Country;
use App\Models\Autorizaciones;


class EmailAutorizacion extends Mailable
{
    use Queueable, SerializesModels;

    public $country;
    public $Autorizaciones;

    public function __construct(Country $country, Autorizaciones $Autorizaciones )
    {
        $this->country = $country;
        $this->Autorizaciones = $Autorizaciones;



    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.autorizacion')
        ->from('noreply@seguridadunica.com','Autorizacion Confirmada')
        ->subject('Autorizacion Confirmada');
    }
}
