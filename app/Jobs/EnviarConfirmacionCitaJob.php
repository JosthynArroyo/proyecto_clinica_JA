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
use App\Mail\ConfirmacionCitaMail;

class EnviarConfirmacionCitaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function handle()
    {
        Mail::to($this->cita->paciente->email)
            ->cc($this->cita->doctor->email)
            ->send(new ConfirmacionCitaMail($this->cita));

        Log::info(
            "Confirmación enviada a paciente y doctor: " .
            "{$this->cita->paciente->name} ({$this->cita->paciente->email}), " .
            "{$this->cita->doctor->name} ({$this->cita->doctor->email})"
        );
    }
}
