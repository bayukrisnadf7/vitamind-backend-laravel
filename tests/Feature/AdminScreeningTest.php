<?php

namespace Tests\Feature;

use App\Models\Screening;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScreeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_screening_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['name' => 'John Doe']);

        Screening::create([
            'user_id' => $user->user_id,
            'answers' => [
                ['question_id' => 'Q1', 'answer' => 'Tidak'],
            ],
            'result' => 'Risiko Rendah',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.screenings.index'));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Risiko Rendah');
    }

    public function test_admin_can_filter_screenings_by_risk(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create(['name' => 'Low Risk User']);
        $user2 = User::factory()->create(['name' => 'High Risk User']);

        Screening::create([
            'user_id' => $user1->user_id,
            'answers' => [['question_id' => 'Q1', 'answer' => 'Tidak']],
            'result' => 'Risiko Rendah',
        ]);

        Screening::create([
            'user_id' => $user2->user_id,
            'answers' => [['question_id' => 'Q1', 'answer' => 'Ya']],
            'result' => 'Risiko Tinggi',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.screenings.index', ['risk' => 'Risiko Tinggi']));

        $response->assertStatus(200);
        $response->assertSee('High Risk User');
        $response->assertDontSee('Low Risk User');
    }

    public function test_admin_can_view_screening_detail_with_questionnaire_answers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['name' => 'John Doe']);

        $screening = Screening::create([
            'user_id' => $user->user_id,
            'answers' => [
                ['question_id' => 'Q1', 'answer' => 'Ya'],
                ['question_id' => 'Q2', 'answer' => 'Menikah dan tinggal dengan pasangan'],
            ],
            'result' => 'Risiko Rendah',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.screenings.show', $screening->skrining_id));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Q1');
        $response->assertSee('Menikah dan tinggal dengan pasangan');
    }
}
