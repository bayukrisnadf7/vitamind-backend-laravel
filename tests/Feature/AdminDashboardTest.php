<?php

namespace Tests\Feature;

use App\Models\Screening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_see_statistics(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user1 = User::factory()->create(['name' => 'Jane Doe', 'role' => 'user']);
        $user2 = User::factory()->create(['name' => 'John Smith', 'role' => 'user']);

        Screening::create([
            'user_id' => $user1->user_id,
            'answers' => [
                ['question_id' => 'Q1', 'answer' => 'Tidak'],
            ],
            'result' => 'Risiko Rendah',
        ]);

        Screening::create([
            'user_id' => $user2->user_id,
            'answers' => [
                ['question_id' => 'Q1', 'answer' => 'Ya'],
            ],
            'result' => 'Risiko Tinggi',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Utama');
        $response->assertSee('Jane Doe');
        $response->assertSee('John Smith');
        $response->assertSee('Risiko Rendah');
        $response->assertSee('Risiko Tinggi');
    }
}
