<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cita;
use App\Models\Horario;
use Carbon\Carbon;

class DoctorSlotController extends Controller
{
    public function __invoke(User $doctor, string $fecha)
    {
        $date = Carbon::parse($fecha)->toDateString();

        $horario = Horario::where('doctor_id', $doctor->id)
            ->whereDate('fecha', $date)
            ->first();

        if (!$horario) {
            return response()->json(['slots' => []]);
        }

        $inicio = Carbon::parse("{$date} {$horario->hora_inicio}");
        $fin    = Carbon::parse("{$date} {$horario->hora_fin}");

        $slots = [];
        for ($t = $inicio->copy(); $t < $fin; $t->addMinutes(30)) {
            $ocupada = Cita::where('doctor_id', $doctor->id)
                ->whereDate('fecha', $date)
                ->whereTime('hora', $t->format('H:i'))
                ->exists();

            if (!$ocupada) $slots[] = $t->format('H:i');
        }

        return response()->json(['slots' => $slots]);
    }
}
