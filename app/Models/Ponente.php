<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($ponente) {
            if ($ponente->getRawOriginal('fotografia')) {
                Storage::disk('public')->delete($ponente->getRawOriginal('fotografia'));
            }
        });
    }

    public function setFotografiaAttribute($value)
    {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            if ($this->getRawOriginal('fotografia')) {
                Storage::disk('public')->delete($this->getRawOriginal('fotografia'));
            }
            $this->attributes['fotografia'] = $value->store('img', 'public');
        } elseif (is_null($value)) {
            if ($this->getRawOriginal('fotografia')) {
                Storage::disk('public')->delete($this->getRawOriginal('fotografia'));
            }
            $this->attributes['fotografia'] = null;
        }
    }

    public function getFotografiaAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}