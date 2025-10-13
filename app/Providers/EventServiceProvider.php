<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CitaAgendada;
use App\Listeners\NotificarDoctorListener;
use App\Listeners\CrearFacturaBorrador;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Los listeners para los eventos de la aplicación.
     */
    protected $listen = [
        CitaAgendada::class => [
            NotificarDoctorListener::class,
            CrearFacturaBorrador::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
