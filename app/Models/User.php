<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tipo_inscripcion',
        'estudiante',
        'admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isEstudiante()
    {
        return $this->estudiante || str_ends_with($this->email, '@franciscoayala.es');
    }

    public function isAdmin()
    {
        return $this->admin === 1;
    }

    public function getRoleAttribute()
    {
        if ($this->isAdmin()) {
            return 'admin';
        } elseif ($this->isEstudiante()) {
            return 'estudiante';
        } else {
            return 'normal';
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (str_ends_with($user->email, '@franciscoayala.es')) {
                $user->estudiante = true;
            }
        });
    }
}
