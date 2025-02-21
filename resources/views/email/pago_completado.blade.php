@component('mail::message')
# ¡Tu pago ha sido completado!

Hola,

Tu pago con ID **{{ $pago->id }}** ha sido procesado exitosamente.

Gracias por tu confianza.

Saludos,  
{{ config('app.name') }}
@endcomponent