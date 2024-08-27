<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnlikeCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_unlike_a_comment()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a post
        $post = Post::factory()->create();

        // Create a comment
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        // User likes the comment
        $like = CommentLike::create([
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        // Assert that the like was created
        $this->assertDatabaseHas('comment_likes', [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        // Acting as the user, send a request to unlike the comment
        $response = $this->actingAs($user)->postJson('/api/comments/unlike', [
            'comment_id' => $comment->id,
        ]);

        // Assert the response status is 200
        $response->assertStatus(200);

        // Assert that the like was deleted from the database
        $this->assertDatabaseMissing('comment_likes', [
            'comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        // Assert the correct response message
        $response->assertJson([
            'status' => 200,
            'message' => 'Comment unliked successfully.'
        ]);
    }

    /** @test */
    public function user_cannot_unlike_a_comment_they_have_not_liked()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a post
        $post = Post::factory()->create();

        // Create a comment
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        // Acting as the user, send a request to unlike the comment
        $response = $this->actingAs($user)->postJson('/api/comments/unlike', [
            'comment_id' => $comment->id,
        ]);

        // Assert the response status is 404
        $response->assertStatus(404);

        // Assert the correct response message
        $response->assertJson([
            'status' => 404,
            'message' => 'Like not found.'
        ]);
    }
}
