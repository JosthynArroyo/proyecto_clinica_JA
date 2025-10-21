<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAdministrativoController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $citas = Cita::with(['paciente:id,name', 'doctor:id,name'])
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->limit(10)
            ->get();

        $metrics = $this->metrics();

        return view('dashboard.administrativo', compact('user', 'citas', 'metrics'));
    }

    public function resumen(Request $request)
    {
        return response()->json($this->metrics());
    }

    protected function metrics(): array
    {
        return [
            'agendadas'   => Cita::count(),
            'pendientes'  => Cita::where('estado', 'pendiente')->count(),
            'realizadas'  => Cita::where('estado', 'realizada')->count(),
            'canceladas'  => Cita::where('estado', 'cancelada')->count(),
            'ingresos'    => (float) Factura::where('estado', Factura::ESTADO_EMITIDA)->sum('total'),
        ];
    }
}
