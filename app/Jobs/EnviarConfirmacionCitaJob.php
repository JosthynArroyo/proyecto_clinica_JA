<?php

namespace App\Jobs;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionCitaMail;              // Paciente
use App\Mail\NotificacionNuevaCitaDoctorMail;   // Doctor (nuevo)

class EnviarConfirmacionCitaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \App\Models\Cita */
    protected $cita;

    /**
     * @param \App\Models\Cita $cita
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    /**
     * Envía correos separados a paciente y doctor.
     */
    public function handle(): void
    {
        
        $cita = Cita::with(['paciente', 'doctor', 'especialidad'])->findOrFail($this->cita->id);

        
        Mail::to($cita->paciente->email)
            ->queue(new ConfirmacionCitaMail($cita));

        
        Mail::to($cita->doctor->email)
            ->queue(new NotificacionNuevaCitaDoctorMail($cita));

        Log::info(sprintf(
            'Correos de cita enviados. Paciente: %s <%s> | Doctor: %s <%s> | Cita ID: %d',
            $cita->paciente->name,
            $cita->paciente->email,
            $cita->doctor->name,
            $cita->doctor->email,
            $cita->id
        ));
    }
}
