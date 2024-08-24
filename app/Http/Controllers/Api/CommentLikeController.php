<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Support\Facades\Auth;
class CommentLikeController extends Controller
{
    // Like a comment
    public function like(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
        ]);

        $userId = Auth::id();
        $commentId = $request->comment_id;

        // Check if the user has already liked this comment
        $existingLike = CommentLike::where('comment_id', $commentId)
                                   ->where('user_id', $userId)
                                   ->first();

        if ($existingLike) {
            return response()->json([
                'status' => 400,
                'message' => 'You have already liked this comment.'
            ], 400);
        }

        // Create a new like
        $like = CommentLike::create([
            'comment_id' => $commentId,
            'user_id' => $userId,
        ]);

        return response()->json([
            'status' => 201,
            'data' => $like
        ], 201);
    }

    // Unlike a comment
    public function unlike(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
        ]);

        $userId = Auth::id();
        $commentId = $request->comment_id;

        // Find the like to remove
        $like = CommentLike::where('comment_id', $commentId)
                           ->where('user_id', $userId)
                           ->first();

        if (!$like) {
            return response()->json([
                'status' => 404,
                'message' => 'Like not found.'
            ], 404);
        }

        // Delete the like
        $like->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Comment unliked successfully.'
        ], 200);
    }
}
