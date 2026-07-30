<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_request_is_blocked(): void
    {
        $response = $this->getJson('/api/user/detail');
        $response->assertStatus(401);
    }

    public function test_get_user_detail_returns_null_when_not_created(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user/detail');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Detail pengguna berhasil diambil',
                'user_detail' => null,
            ]);
    }

    public function test_create_user_detail_successfully(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'nik' => '3573012304950001',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '1995-04-23',
            'no_telepon' => '081234567890',
        ];

        $response = $this->postJson('/api/user/detail', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user_detail' => [
                    'user_detail_id',
                    'user_id',
                    'nik',
                    'jenis_kelamin',
                    'tgl_lahir',
                    'no_telepon',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'message' => 'Detail pengguna berhasil diperbarui',
                'user_detail' => [
                    'user_id' => $user->user_id,
                    'nik' => '3573012304950001',
                    'jenis_kelamin' => 'Laki-laki',
                    'tgl_lahir' => '1995-04-23',
                    'no_telepon' => '081234567890',
                ],
            ]);

        $this->assertDatabaseHas('user_details', [
            'user_id' => $user->user_id,
            'nik' => '3573012304950001',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '1995-04-23',
            'no_telepon' => '081234567890',
        ]);
    }

    public function test_update_user_detail_successfully(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        UserDetail::create([
            'user_id' => $user->user_id,
            'nik' => '3573012304950001',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '1995-04-23',
            'no_telepon' => '081234567890',
        ]);

        $updatePayload = [
            'jenis_kelamin' => 'Perempuan',
            'no_telepon' => '089999999999',
        ];

        $response = $this->putJson('/api/user/detail', $updatePayload);

        $response->assertStatus(200)
            ->assertJson([
                'user_detail' => [
                    'user_id' => $user->user_id,
                    'nik' => '3573012304950001',
                    'jenis_kelamin' => 'Perempuan',
                    'no_telepon' => '089999999999',
                ],
            ]);

        $this->assertDatabaseHas('user_details', [
            'user_id' => $user->user_id,
            'jenis_kelamin' => 'Perempuan',
            'no_telepon' => '089999999999',
        ]);
    }

    public function test_validation_fails_for_invalid_nik(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/user/detail', [
            'nik' => '123', // Not 16 digits
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nik']);
    }
}
