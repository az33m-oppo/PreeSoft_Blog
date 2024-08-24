<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        Log::info('Apis working .......');
        $user = Auth::user();
        Log::info('post', ['title' => $user]);
        $posts = Post::with('user')->get(); // Include user data if needed

        return response()->json([
            'status' => 200,
            'data' => $posts
        ], 200);
    }

    public function store(Request $request)
    {
        Log::info('post', ['title' => $request->title]);
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json([
            'status' => 201,
            'data' => $post
        ], 201);
    }

    public function show(Post $post)
    {
        $post->load('user'); // Include user data if needed

        return response()->json([
            'status' => 200,
            'data' => $post
        ], 200);
    }

    public function update(Request $request, Post $post)
    {
        // Check if the authenticated user is the owner of the post
        if ($request->user()->id !== $post->user_id) {
            return response()->json([
                'status' => 403,
                'message' => 'You are not authorized to update this post'
            ], 403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post->update($request->all());

        return response()->json([
            'status' => 200,
            'data' => $post
        ], 200);
    }

    public function destroy($id)
    {


        // Find the post by id
        $post = Post::find($id);

        // Check if the post exists
        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'Post not found'
            ], 404);
        }
        if (auth()->id() !== $post->user_id) {
            return response()->json([
                'status' => 403,
                'message' => 'You are not authorized to Delete this comment'
            ], 403);
        }
         // Delete all comments associated with the post
    $post->comments()->each(function ($comment) {
        // Delete all likes associated with each comment
        $comment->likes()->delete();
        // Delete the comment itself
        $comment->delete();
    });
        // Delete the post
        $post->delete();

        // Return a success message with status code 200
        return response()->json([
            'status' => 200,
            'message' => 'Post deleted successfully'
        ], 200);
    }
}
