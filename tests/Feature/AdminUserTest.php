<?php

namespace Tests\Feature;

use App\Models\Screening;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['name' => 'Alice Wonder', 'email' => 'alice@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Alice Wonder');
        $response->assertSee('alice@example.com');
    }

    public function test_admin_can_search_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['name' => 'Alice Wonder', 'email' => 'alice@example.com']);
        User::factory()->create(['name' => 'Bob Marley', 'email' => 'bob@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.users.index', ['search' => 'Alice']));

        $response->assertStatus(200);
        $response->assertSee('Alice Wonder');
        $response->assertDontSee('Bob Marley');
    }

    public function test_admin_can_view_user_detail_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['name' => 'Alice Wonder', 'email' => 'alice@example.com']);

        UserDetail::create([
            'user_id' => $user->user_id,
            'nik' => '1234567890123456',
            'jenis_kelamin' => 'Perempuan',
            'tgl_lahir' => '2000-01-01',
            'no_telepon' => '081234567890',
        ]);

        Screening::create([
            'user_id' => $user->user_id,
            'answers' => [
                ['question_id' => 'Q1', 'answer' => 'Tidak'],
            ],
            'result' => 'Risiko Rendah',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.show', $user->user_id));

        $response->assertStatus(200);
        $response->assertSee('Alice Wonder');
        $response->assertSee('1234567890123456');
        $response->assertSee('Perempuan');
        $response->assertSee('081234567890');
        $response->assertSee('Risiko Rendah');
    }
}
