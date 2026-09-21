<?php

namespace Tests\Feature;

use App\Mail\ForgotPasswordOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_successfully(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'user_id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    public function test_register_validation_fails(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_login_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);
    }

    public function test_login_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'jane@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Email atau kata sandi salah',
            ]);
    }

    public function test_logout_successfully(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Berhasil keluar',
            ]);

        $this->assertEmpty($user->tokens);
    }

    public function test_forgot_password_sends_otp(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $response = $this->postJson('/api/auth/forgot-password', [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Kode OTP telah berhasil dikirim ke email Anda.',
            ]);

        $this->assertDatabaseHas('password_reset_otps', [
            'email' => 'user@example.com',
        ]);

        Mail::assertSent(ForgotPasswordOtpMail::class, function ($mail) {
            return $mail->hasTo('user@example.com');
        });
    }

    public function test_verify_otp_successfully(): void
    {
        $otp = '123456';
        PasswordResetOtp::create([
            'email' => 'user@example.com',
            'otp' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/auth/verify-otp', [
            'email' => 'user@example.com',
            'otp' => $otp,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Kode OTP valid.',
            ]);
    }

    public function test_verify_otp_expired(): void
    {
        $otp = '123456';
        PasswordResetOtp::create([
            'email' => 'user@example.com',
            'otp' => Hash::make($otp),
            'expires_at' => Carbon::now()->subMinutes(1),
        ]);

        $response = $this->postJson('/api/auth/verify-otp', [
            'email' => 'user@example.com',
            'otp' => $otp,
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Kode OTP telah kedaluwarsa.',
            ]);
    }

    public function test_verify_otp_invalid(): void
    {
        PasswordResetOtp::create([
            'email' => 'user@example.com',
            'otp' => Hash::make('123456'),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/auth/verify-otp', [
            'email' => 'user@example.com',
            'otp' => '654321',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Kode OTP tidak valid.',
            ]);
    }

    public function test_reset_password_otp_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('old_password'),
        ]);

        $otp = '123456';
        PasswordResetOtp::create([
            'email' => 'user@example.com',
            'otp' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $response = $this->postJson('/api/auth/reset-password-otp', [
            'email' => 'user@example.com',
            'otp' => $otp,
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Kata sandi Anda telah berhasil diperbarui.',
            ]);

        $this->assertTrue(Hash::check('new_password123', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_otps', [
            'email' => 'user@example.com',
        ]);
    }

    public function test_reset_password_by_email_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'useremail@example.com',
            'password' => Hash::make('old_password'),
        ]);

        $response = $this->postJson('/api/auth/reset-password', [
            'email' => 'useremail@example.com',
            'password' => 'new_password123',
            'password_confirmation' => 'new_password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Kata sandi Anda telah berhasil diperbarui.',
            ]);

        $this->assertTrue(Hash::check('new_password123', $user->fresh()->password));
    }

    public function test_google_redirect(): void
    {
        $response = $this->getJson('/api/auth/google/redirect');

        $response->assertStatus(200)
            ->assertJsonStructure(['url']);

        $this->assertStringContainsString('accounts.google.com', $response->json('url'));
    }

    public function test_google_callback_successfully_creates_new_user(): void
    {
        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getEmail')
            ->andReturn('newgoogle@example.com');
        $abstractUser->shouldReceive('getName')
            ->andReturn('New Google User');

        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('stateless')
            ->andReturnSelf();
        $provider->shouldReceive('user')
            ->andReturn($abstractUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->getJson('/api/auth/google/callback');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => [
                    'user_id',
                    'name',
                    'email',
                ],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newgoogle@example.com',
            'name' => 'New Google User',
        ]);
    }

    public function test_google_callback_successfully_authenticates_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'existinggoogle@example.com',
            'name' => 'Existing User',
        ]);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getEmail')
            ->andReturn('existinggoogle@example.com');
        $abstractUser->shouldReceive('getName')
            ->andReturn('Existing User');

        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('stateless')
            ->andReturnSelf();
        $provider->shouldReceive('user')
            ->andReturn($abstractUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->getJson('/api/auth/google/callback');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token',
            ]);

        $this->assertEquals($user->user_id, $response->json('user.user_id'));
    }

    public function test_google_callback_fails_on_driver_exception(): void
    {
        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('stateless')
            ->andReturnSelf();
        $provider->shouldReceive('user')
            ->andThrow(new \Exception('Google OAuth failed'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->getJson('/api/auth/google/callback');

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Gagal mengambil data pengguna dari Google.',
            ]);
    }
}
