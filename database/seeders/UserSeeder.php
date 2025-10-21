<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $accounts = [
                [
                    'name' => 'Administrador General',
                    'email' => 'admin@clinic.test',
                    'password' => 'admin1234',
                    'roles' => [Role::ADMINISTRADOR],
                ],
                [
                    'name' => 'Dra. Mariana Herrera',
                    'email' => 'doctor@clinic.test',
                    'password' => 'doctor1234',
                    'roles' => [Role::DOCTOR],
                    'extra' => [
                        'precio_consulta' => 45.50,
                        'moneda' => 'USD',
                    ],
                ],
                [
                    'name' => 'Paciente de Prueba',
                    'email' => 'paciente@clinic.test',
                    'password' => 'paciente1234',
                    'roles' => [Role::PACIENTE],
                ],
            ];

            foreach ($accounts as $account) {
                $user = User::updateOrCreate(
                    ['email' => $account['email']],
                    array_merge([
                        'name' => $account['name'],
                        'password' => Hash::make($account['password']),
                        'active' => true,
                        'status' => 'active',
                    ], $account['extra'] ?? [])
                );

                $user->assignRole($account['roles']);
            }
        });
    }
}
