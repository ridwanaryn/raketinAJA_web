<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('raketinAJA');
    }

    public function test_users_can_authenticate_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'John Player',
            'email' => 'john@example.com',
            'phone' => '+123456',
            'role' => 'player',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/fields');
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::create([
            'name' => 'John Player',
            'email' => 'john@example.com',
            'phone' => '+123456',
            'role' => 'player',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_registration_creates_player_by_default(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Player',
            'email' => 'newplayer@example.com',
            'phone' => '+1234567',
            'role' => 'player',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => 'on',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'newplayer@example.com',
            'role' => 'player',
        ]);
        $response->assertRedirect('/fields');
    }

    public function test_registration_can_create_owner(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Owner',
            'email' => 'newowner@example.com',
            'phone' => '+12345678',
            'role' => 'owner',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => 'on',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'newowner@example.com',
            'role' => 'owner',
        ]);
        $response->assertRedirect('/owner/dashboard');
    }

    public function test_players_cannot_access_owner_dashboard(): void
    {
        $player = User::create([
            'name' => 'Alex Player',
            'email' => 'alex@example.com',
            'phone' => '+123456',
            'role' => 'player',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($player)->get('/owner/dashboard');

        $response->assertRedirect('/fields');
    }
}
