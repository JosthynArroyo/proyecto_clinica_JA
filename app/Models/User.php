<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        // Campos de tarifa del doctor
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
        'precio_consulta'   => 'decimal:2',
    ];

    /** Roles */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /** Especialidades (para doctores) */
    public function especialidades()
    {
        return $this->belongsToMany(Especialidad::class, 'doctor_especialidad', 'user_id', 'especialidad_id')
            ->withTimestamps();
    }

    /** Facturación: facturas donde el usuario es paciente */
    public function facturasComoPaciente()
    {
        return $this->hasMany(Factura::class, 'paciente_id');
    }

    /** Facturación: facturas donde el usuario es el doctor */
    public function facturasComoDoctor()
    {
        return $this->hasMany(Factura::class, 'doctor_id');
    }
}
