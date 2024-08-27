<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_creation_success()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a post
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        // Simulate the user being logged in
        $this->actingAs($user);

        // Data to be sent in the request
        $data = [
            'post_id' => $post->id,
            'comment' => 'This is a test comment',
        ];

        // Send POST request to create a comment
        $response = $this->postJson('/api/comments', $data);

        // Assert the status is 201 Created
        $response->assertStatus(201);

        // Assert the comment exists in the database
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment',
        ]);

        // Assert the response contains the comment data
        $response->assertJson([
            'status' => 201,
            'data' => [
                'post_id' => $post->id,
                'user_id' => $user->id,
                'comment' => 'This is a test comment',
            ],
        ]);
    }

    public function test_comment_creation_fails_validation()
    {
        // Create a user
        $user = User::factory()->create();

        // Simulate the user being logged in
        $this->actingAs($user);

        // Data to be sent in the request with missing fields
        $data = [
            // 'post_id' => 1, // Post ID is missing
            'comment' => 'This is a test comment',
        ];

        // Send POST request to create a comment
        $response = $this->postJson('/api/comments', $data);

        // Assert the status is 422 Unprocessable Entity
        $response->assertStatus(422);

        // Assert the response contains validation errors
        $response->assertJsonValidationErrors(['post_id']);
    }
}
