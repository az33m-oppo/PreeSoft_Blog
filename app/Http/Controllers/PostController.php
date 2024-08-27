<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }
    public function index()
    {
        // Return the view for showing all posts
        return view('posts.index');
    }
    public function edit($id)
{
    // Return the edit view with the post ID
    return view('posts.edit', ['postId' => $id]);
}
public function show($id)
{
    // Return the edit view with the post ID
    return view('posts.post_detail', ['postId' => $id]);
}

public function showUsersWithLikes()
{
    $usersWithLikedCommentsOnPosts = User::whereHas('posts.comments.likes')->get();
    return view('users.with_liked_comments', compact('usersWithLikedCommentsOnPosts'));
}
}
