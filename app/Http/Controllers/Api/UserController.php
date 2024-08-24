<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function usersWithLikedComments()
    {
        // Fetch users who have at least one like on any of their post's comments
        $users = User::whereHas('posts.comments.commentLikes')->with('posts.comments.commentLikes')->get();

        return response()->json([
            'status' => 200,
            'data' => $users
        ], 200);
    }
}
