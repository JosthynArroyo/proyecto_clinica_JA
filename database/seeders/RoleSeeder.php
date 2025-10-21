<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            Role::ADMINISTRADOR => 'Acceso completo a la plataforma y configuración de la clínica.',
            Role::DOCTOR        => 'Gestiona pacientes, agenda y recetas médicas.',
            Role::PACIENTE      => 'Accede a su historial y agendamiento de citas.',
        ];

        foreach ($roles as $name => $description) {
            Role::updateOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
    }
}
