<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
