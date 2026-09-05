<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_admin_login_page(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('Login Administrator');
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = User::factory()->create([
            'email' => 'vitamindadmin@gmail.com',
            'password' => Hash::make('vitamindadmin'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'vitamindadmin@gmail.com',
            'password' => 'vitamindadmin',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_non_admin_cannot_login_to_admin_portal(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_admin_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'vitamindadmin@gmail.com',
            'password' => Hash::make('vitamindadmin'),
            'role' => 'admin',
        ]);

        $response = $this->from(route('admin.login'))->post(route('admin.login.submit'), [
            'email' => 'vitamindadmin@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create([
            'email' => 'vitamindadmin@gmail.com',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }
}
