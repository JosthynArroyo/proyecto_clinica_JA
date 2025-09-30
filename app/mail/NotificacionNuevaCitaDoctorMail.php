<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacionNuevaCitaDoctorMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var \App\Models\Cita */
    public $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function build()
    {
        return $this->subject('Nueva cita asignada - Clínica Don Bosco')
                    ->view('emails.notificacion_cita_doctor')
                    ->with(['cita' => $this->cita]);
    }
}
