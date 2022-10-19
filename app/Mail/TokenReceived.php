<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Country;


class TokenReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $country;

    public function __construct(Country $country,string $token)
    {
        $this->token = $token;
        $this->country = $country;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.token')
        ->from('noreply@seguridadunica.com','Generacion de Token')
        ->subject('Generacion de Token');
    }
}
