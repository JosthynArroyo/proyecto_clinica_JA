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
        'active',              // legado, no usado para control de acceso
        'telefono',
        'dni',
        'direccion',
        'fecha_nacimiento',
        'sexo',
        'avatar',
        'precio_consulta',
        'moneda',
        // NUEVOS
        'status',
        'last_login_at',
        'last_activity_at',
        'suspended_until',
        'deactivation_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'password'           => 'hashed',
        'fecha_nacimiento'   => 'date',
        'precio_consulta'    => 'decimal:2',
        'last_login_at'      => 'datetime',
        'last_activity_at'   => 'datetime',
        'suspended_until'    => 'datetime',
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

    /** ===== Helpers de estado ===== */
    public function isBlocked(): bool   { return $this->status === 'blocked'; }
    public function isInactive(): bool  { return $this->status === 'inactive'; }
    public function isSuspended(): bool { return $this->suspended_until && now()->lt($this->suspended_until); }
    public function isActive(): bool    { return $this->status === 'active' && !$this->isSuspended(); }

    public function scopeOnlyActive($q)
    {
        return $q->where('status','active')
            ->where(function($qq){
                $qq->whereNull('suspended_until')->orWhere('suspended_until','<=', now());
            });
    }
}
