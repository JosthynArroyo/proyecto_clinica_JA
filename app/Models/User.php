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

    /**
     * === Gestión de Roles ===
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function scopeWithRole($query, string $roleName)
    {
        return $query->whereHas('roles', fn ($q) => $q->where('name', $roleName));
    }

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function hasAnyRole(string ...$roles): bool
    {
        if (empty($roles)) {
            return false;
        }

        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasAllRoles(string ...$roles): bool
    {
        if (empty($roles)) {
            return false;
        }

        $count = $this->roles()->whereIn('name', $roles)->distinct()->count('roles.id');

        return $count === count(array_unique($roles));
    }

    public function assignRole($roles): void
    {
        $roleIds = collect($roles)->map(function ($role) {
            if ($role instanceof Role) {
                return $role->id;
            }

            return Role::where('name', $role)->value('id');
        })->filter()->all();

        if (!empty($roleIds)) {
            $this->roles()->syncWithoutDetaching($roleIds);
        }
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
