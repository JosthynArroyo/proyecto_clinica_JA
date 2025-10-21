<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_administrador_puede_ver_dashboard_administrativo(): void
    {
        $user = $this->userWithRole(Role::ADMINISTRADOR);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Panel administrativo');
    }

    public function test_doctor_no_puede_acceder_al_dashboard_administrativo(): void
    {
        $user = $this->userWithRole(Role::DOCTOR);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_doctor_puede_ver_su_dashboard(): void
    {
        $user = $this->userWithRole(Role::DOCTOR);

        $response = $this->actingAs($user)->get(route('doctor.dashboard'));

        $response->assertOk();
        $response->assertSee('Agenda del día');
    }

    public function test_paciente_no_puede_acceder_al_dashboard_del_doctor(): void
    {
        $user = $this->userWithRole(Role::PACIENTE);

        $response = $this->actingAs($user)->get(route('doctor.dashboard'));

        $response->assertRedirect('/');
        $response->assertSessionHas('error');
    }

    public function test_paciente_ve_su_dashboard(): void
    {
        $user = $this->userWithRole(Role::PACIENTE);

        $response = $this->actingAs($user)->get(route('paciente.dashboard'));

        $response->assertOk();
        $response->assertSee('Panel de paciente');
    }

    protected function userWithRole(string $roleName): User
    {
        $user = User::factory()->create();
        $user->assignRole([$roleName]);

        return $user->fresh();
    }
}
