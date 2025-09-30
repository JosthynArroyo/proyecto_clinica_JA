<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Especialidad;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'telefono',
        'dni',
        'direccion',
        'fecha_nacimiento',
        'sexo',
        'avatar',
        // NUEVO:
        'precio_consulta',
        'moneda',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'fecha_nacimiento'  => 'date',
        // NUEVO:
        'precio_consulta'   => 'decimal:2',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'doctor_especialidad', 'user_id', 'especialidad_id')
            ->withTimestamps();
    }
}
