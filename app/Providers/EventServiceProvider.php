<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Pago;
use App\Observers\PagoObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Aquí puedes registrar eventos y listeners si los usas
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        // Registrar el Observer para el modelo Pago
        Pago::observe(PagoObserver::class);
    }
}