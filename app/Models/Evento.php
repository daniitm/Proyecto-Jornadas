<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'tipo', 'fecha', 'hora_inicio', 'hora_fin', 'cupo_maximo', 'ponente_id'
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
    ];

    public function ponente()
    {
        return $this->belongsTo(Ponente::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class);
    }

    public static function fechasDisponibles()
    {
        return ['2025-02-20', '2025-02-21'];
    }

    public static function horasDisponibles()
    {
        return ['09:00', '10:00', '11:00', '12:00', '13:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
    }

    public static function calcularHoraFin($horaInicio)
    {
        return Carbon\Carbon::parse($horaInicio)->addMinutes(55)->format('H:i');
    }
}