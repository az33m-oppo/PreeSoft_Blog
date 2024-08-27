<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentLikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_a_comment()
    {
        // Create a user, post, and comment
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        // Act as the user
        $this->actingAs($user);

        // Send a POST request to like the comment
        $response = $this->postJson('/api/comments/like', [
            'comment_id' => $comment->id,
        ]);

        // Assert that the like was successful
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'id',
                'comment_id',
                'user_id',
                'created_at',
                'updated_at',
            ],
        ]);

        // Assert that the like exists in the database
        $this->assertDatabaseHas('comment_likes', [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_user_cannot_like_a_comment_multiple_times()
    {
        // Create a user, post, and comment
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

        // Act as the user
        $this->actingAs($user);

        // Like the comment
        CommentLike::create([
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        // Send a POST request to like the comment again
        $response = $this->postJson('/api/comments/like', [
            'comment_id' => $comment->id,
        ]);

        // Assert that the request is denied with a 400 status
        $response->assertStatus(400);
        $response->assertJson([
            'status' => 400,
            'message' => 'You have already liked this comment.',
        ]);
    }
}
