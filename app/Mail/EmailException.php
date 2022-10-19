<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailException extends Mailable
{
    use Queueable, SerializesModels;

    public $Exception;
    public $Ubicacion;


    public function __construct(string $Exception,string $Ubicacion)
    {
        $this->Exception = $Exception;
        $this->Ubicacion = $Ubicacion;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@seguridadunica.com','Exception en '.$this->Ubicacion)->view('emails.exceptions.exception');
    }
}
