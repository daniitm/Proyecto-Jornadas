<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'fotografia', 'areas_experiencia', 'redes_sociales'
    ];

    protected $casts = [
        'areas_experiencia' => 'array',
    ];

    public function eventos()
    {
        return $this->hasMany(Evento::class);
    }
}