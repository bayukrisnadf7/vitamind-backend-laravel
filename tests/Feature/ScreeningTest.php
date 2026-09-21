<?php

namespace Tests\Feature;

use App\Models\Screening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ScreeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_request_is_blocked(): void
    {
        $response = $this->postJson('/api/screenings', [
            'answers' => ['Ya', 'Tidak'],
        ]);

        $response->assertStatus(401);
    }

    public function test_submit_screening_successfully(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // 5 'Ya' answers (5 * 3 = 15 points) -> should be 'Indikasi Cemas Ringan'
        $answers = [
            ['question_id' => 'q1', 'answer' => 'Ya'],
            ['question_id' => 'q2', 'answer' => 'Tidak'],
            ['question_id' => 'q3', 'answer' => 'Ya'],
            ['question_id' => 'q4', 'answer' => 'Ya'],
            ['question_id' => 'q5', 'answer' => 'Tidak tahu'],
            ['question_id' => 'q6', 'answer' => 'Ya'],
            ['question_id' => 'q7', 'answer' => 'Ya'],
        ];

        $response = $this->postJson('/api/screenings', [
            'answers' => $answers,
            'result' => 'Indikasi Cemas Ringan',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'screening' => [
                    'skrining_id',
                    'user_id',
                    'answers',
                    'result',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'screening' => [
                    'result' => 'Indikasi Cemas Ringan',
                ],
            ]);

        $this->assertDatabaseHas('screenings', [
            'user_id' => $user->user_id,
            'result' => 'Indikasi Cemas Ringan',
        ]);
    }

    public function test_screening_list_returns_latest_first(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $screening1 = new Screening([
            'user_id' => $user->user_id,
            'answers' => ['Ya'],
            'result' => 'Sehat Mental',
        ]);
        $screening1->created_at = now()->subDay();
        $screening1->save();

        $screening2 = new Screening([
            'user_id' => $user->user_id,
            'answers' => ['Ya', 'Ya'],
            'result' => 'Sehat Mental',
        ]);
        $screening2->created_at = now();
        $screening2->save();

        $response = $this->getJson('/api/screenings');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'screenings');

        $this->assertEquals($screening2->skrining_id, $response->json('screenings.0.skrining_id'));
    }

    public function test_get_single_screening_successfully(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $screening = Screening::create([
            'user_id' => $user->user_id,
            'answers' => ['Ya'],
            'result' => 'Sehat Mental',
        ]);

        $response = $this->getJson("/api/screenings/{$screening->skrining_id}");

        $response->assertStatus(200)
            ->assertJson([
                'screening' => [
                    'skrining_id' => $screening->skrining_id,
                    'result' => 'Sehat Mental',
                ],
            ]);
    }

    public function test_cannot_get_other_users_screening(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $screening = Screening::create([
            'user_id' => $user1->user_id,
            'answers' => ['Ya'],
            'result' => 'Sehat Mental',
        ]);

        Sanctum::actingAs($user2);

        $response = $this->getJson("/api/screenings/{$screening->skrining_id}");

        $response->assertStatus(404);
    }
}
