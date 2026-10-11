<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\TelescopeServiceProvider as AppTelescopeServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Telescope\TelescopeServiceProvider;
use Tests\TestCase;

class TelescopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['telescope.enabled' => true]);
        (new TelescopeServiceProvider($this->app))->boot();
        (new AppTelescopeServiceProvider($this->app))->boot();
    }

    public function test_telescope_is_accessible_in_local_environment(): void
    {
        $this->app->detectEnvironment(fn () => 'local');

        $response = $this->get('/telescope');

        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_to_admin_login_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $response = $this->get('/telescope');

        $response->assertRedirect(route('admin.login'));
    }

    public function test_unauthorized_guest_json_request_receives_401_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $response = $this->getJson('/telescope/telescope-api/entries');

        $response->assertStatus(401);
    }

    public function test_regular_user_cannot_access_telescope_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/telescope');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_telescope_in_production(): void
    {
        $this->app->detectEnvironment(fn () => 'production');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/telescope');

        $response->assertStatus(200);
    }
}
