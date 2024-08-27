<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_post()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate the user being authenticated
        $this->actingAs($user);

        // Define the post data
        $postData = [
            'title' => 'Test Post',
            'body' => 'This is a test post body.',
        ];

        // Send a POST request to create the post
        $response = $this->postJson('/api/posts', $postData);

        // Assert that the post was created successfully
        $response->assertStatus(201)
                 ->assertJson([
                     'status' => 201,
                     'data' => [
                         'title' => 'Test Post',
                         'body' => 'This is a test post body.',
                         'user_id' => $user->id,
                     ],
                 ]);

        // Assert that the post exists in the database
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'body' => 'This is a test post body.',
            'user_id' => $user->id,
        ]);
    }
}
