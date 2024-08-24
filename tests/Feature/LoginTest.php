<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_users_to_login_with_correct_credentials()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Attempt login
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Assert response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
        ]);
    }

    /** @test */
    public function it_does_not_allow_login_with_incorrect_password()
    {
        // Create a user
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        // Attempt login with incorrect password
        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        // Assert response
        $response->assertStatus(401); // Unauthorized
        $response->assertJson([
            'error' => 'Invalid credentials',
        ]);
    }

    /** @test */
    public function it_does_not_allow_login_with_invalid_email()
    {
        // Attempt login with invalid email
        $response = $this->post('/api/login', [
            'email' => 'invalid@example.com',
            'password' => 'password123',
        ]);

        // Assert response
        $response->assertStatus(401); // Unauthorized
        $response->assertJson([
            'error' => 'Invalid credentials',
        ]);
    }
}
