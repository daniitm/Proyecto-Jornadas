<?php

namespace App\Mail;

use App\Models\Pago;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PagoCompletado extends Mailable
{
    use Queueable, SerializesModels;

    public $pago;

    public function __construct(Pago $pago)
    {
        $this->pago = $pago;
    }

    public function build()
    {
        return $this->subject('Pago Completado')
                    ->markdown('emails.pago_completado')
                    ->with(['pago' => $this->pago]);
    }
}