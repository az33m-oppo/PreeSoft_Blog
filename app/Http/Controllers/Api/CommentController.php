<?php

namespace App\Http\Controllers\Api;
use App\Models\Comment;
use App\Models\post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CommentController extends Controller
{
   
        // Fetch all comments for a specific post
    public function index($postId)
    {
        // Find the post by its ID
        $post = Post::find($postId);

        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'Post not found'
            ], 404);
        }

        // Retrieve comments for the post, including the user and likes count
        $comments = $post->comments()->with(['user', 'likes'])->get()->map(function ($comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user' => $comment->user->name, // or any other user attribute
                'user_id' => $comment->user->id,
                'likes_count' => $comment->likes->count(),
                'liked' => $comment->likes()->where('user_id', auth()->id())->exists(),
            ];
        });

        return response()->json([
            'status' => 200,
            'data' => $comments
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request
    $request->validate([
        'post_id' => 'required|exists:posts,id',
        'comment' => 'required|string',
    ]);

    // Create a new comment
    $comment = Comment::create([
        'post_id' => $request->post_id,
        'user_id' => auth()->id(), // Get the ID of the currently authenticated user
        'comment' => $request->comment,
    ]);

    return response()->json([
        'status' => 201,
        'data' => $comment
    ], 201);
    }

    public function show($id)
    {
        // Find a comment by id
        $comment = Comment::with(['post', 'user'])->find($id);

        if (!$comment) {
            return response()->json([
                'status' => 404,
                'message' => 'Comment not found'
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'data' => $comment
        ]);
    }

    public function update(Request $request, $id)
    {
        // Find the comment
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => 404,
                'message' => 'Comment not found'
            ], 404);
        }

        // Check if the authenticated user is the owner of the comment
    if ($request->user()->id !== $comment->user_id) {
        return response()->json([
            'status' => 403,
            'message' => 'You are not authorized to update this comment'
        ], 403);
    }

        // Validate the request
        $request->validate([
            'comment' => 'required|string',
        ]);

        // Update the comment
        $comment->update($request->only('comment'));

        return response()->json([
            'status' => 200,
            'message' => 'Comment updated successfully',
            'data' => $comment
        ]);
    }

    public function destroy($id)
    {
        // Find the comment
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json([
                'status' => 404,
                'message' => 'Comment not found'
            ], 404);
        }

        if (auth()->id() !== $comment->user_id) {
            return response()->json([
                'status' => 403,
                'message' => 'You are not authorized to Delete this comment'
            ], 403);
        }

        // Delete all likes associated with the comment
    $comment->likes()->delete();
        // Delete the comment
        $comment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Comment deleted successfully'
        ], 200);
    }
    
}
