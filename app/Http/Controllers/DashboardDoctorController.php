<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardDoctorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $metrics = $this->metrics($user->id);
        $agenda = $this->agenda($user->id);

        return view('dashboard.doctor', compact('user', 'metrics', 'agenda'));
    }

    public function data(Request $request)
    {
        $doctorId = $request->user()->id;

        return response()->json([
            'metrics' => $this->metrics($doctorId),
            'agenda'  => $this->agenda($doctorId),
        ]);
    }

    protected function metrics(int $doctorId): array
    {
        $tz = config('app.timezone', 'UTC');
        $today = Carbon::now($tz)->toDateString();
        $lastTwoHours = Carbon::now($tz)->subHours(2);

        $base = Cita::query()->where('doctor_id', $doctorId);

        return [
            'hoy'           => (clone $base)->whereDate('fecha', $today)->count(),
            'realizadas'    => (clone $base)->whereDate('fecha', $today)->where('estado', 'realizada')->count(),
            'pendientes'    => (clone $base)->whereDate('fecha', $today)->where('estado', 'pendiente')->count(),
            'confirmadas_2h' => (clone $base)->where('estado', 'confirmada')->where('updated_at', '>=', $lastTwoHours)->count(),
            'realizadas_2h'  => (clone $base)->where('estado', 'realizada')->where('updated_at', '>=', $lastTwoHours)->count(),
            'canceladas_2h'  => (clone $base)->where('estado', 'cancelada')->where('updated_at', '>=', $lastTwoHours)->count(),
        ];
    }

    protected function agenda(int $doctorId)
    {
        $tz = config('app.timezone', 'UTC');
        $today = Carbon::now($tz)->toDateString();

        return Cita::with(['paciente:id,name'])
            ->where('doctor_id', $doctorId)
            ->whereDate('fecha', '>=', $today)
            ->orderBy('fecha')
            ->orderBy('hora')
            ->limit(10)
            ->get(['id', 'paciente_id', 'estado', 'fecha', 'hora'])
            ->map(fn ($cita) => [
                'paciente' => $cita->paciente->name ?? 'Paciente',
                'estado'   => $cita->estado,
                'fecha'    => $cita->fecha,
                'hora'     => $cita->hora,
            ])
            ->values();
    }
}
