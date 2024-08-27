<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_update_comment()
    {
        // Create a user, post, and comment
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        // Act as the user
        $this->actingAs($user);

        // Update data
        $updatedCommentData = ['comment' => 'Updated comment text'];

        // Send PUT request
        $response = $this->json('PUT', "/api/comments/{$comment->id}", $updatedCommentData);

        // Assert status
        $response->assertStatus(200);

        // Assert response structure
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'comment',
                'post_id',
                'user_id',
                'created_at',
                'updated_at',
            ],
        ]);

        // Assert comment was updated
        $this->assertEquals('Updated comment text', $comment->fresh()->comment);
    }

    public function test_non_owner_cannot_update_comment()
    {
        // Create a user, post, and comment
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $otherUser->id,
        ]);

        // Act as the user
        $this->actingAs($user);

        // Update data
        $updatedCommentData = ['comment' => 'Updated comment text'];

        // Send PUT request
        $response = $this->json('PUT', "/api/comments/{$comment->id}", $updatedCommentData);

        // Assert status
        $response->assertStatus(403);
        $response->assertJson([
            'status' => 403,
            'message' => 'You are not authorized to update this comment',
        ]);
    }

    public function test_comment_not_found()
    {
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Attempt to update a non-existent comment
        $response = $this->json('PUT', "/api/comments/999", ['comment' => 'Updated comment text']);

        // Assert status
        $response->assertStatus(404);
        $response->assertJson([
            'status' => 404,
            'message' => 'Comment not found',
        ]);
    }

    public function test_comment_update_validation()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        // Act as the user
        $this->actingAs($user);

        // Send request with invalid data
        $response = $this->json('PUT', "/api/comments/{$comment->id}", ['comment' => '']);

        // Assert validation error
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('comment');
    }
}
