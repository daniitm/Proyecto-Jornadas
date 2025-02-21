<?php

namespace App\Observers;

use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PagoCompletado;

class PagoObserver
{
    public function updated(Pago $pago)
    {
        if ($pago->estado === 'completado') { // Asegúrate de que el estado es 'completado'
            $user = User::find($pago->user_id);

            if ($user) {
                Mail::to($user->email)->send(new PagoCompletado($pago));
            }
        }
    }
}