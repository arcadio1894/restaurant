<?php

namespace App\Mail;

use App\Models\Reclamacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CambioEstadoReclamo extends Mailable
{
    use Queueable, SerializesModels;

    public $reclamacion;

    public function __construct(Reclamacion $reclamacion)
    {
        $this->reclamacion = $reclamacion;
    }

    public function build()
    {
        return $this->subject('Actualización de estado de su reclamo')
            ->view('emails.cambio_estado_reclamo');
    }
}
