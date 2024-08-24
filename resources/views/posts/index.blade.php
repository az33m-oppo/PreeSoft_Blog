@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
    <div class="container bg-light-custom form-container">
        <h2 class="mb-4">All Posts</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="posts-list">
                <!-- Posts will be loaded here by JavaScript -->
            </tbody>
        </table>
    </div>

    <script>
     document.addEventListener('DOMContentLoaded', async function() {
    try {
        const response = await fetch('http://127.0.0.1:8000/api/posts', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}` // Ensure the token is stored correctly
            }
        });

        if (response.ok) {
            const jsonResponse = await response.json();
            console.log('Data received:', jsonResponse);

            // Extract posts from the response
            const posts = jsonResponse.data || [];
            renderPosts(posts);
        } else {
            console.error('Failed to load posts', response.statusText);
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

function renderPosts(posts) {
    const postsList = document.getElementById('posts-list');
    postsList.innerHTML = ''; // Clear existing posts

    // Get logged-in user ID from meta tag
    const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

    if (Array.isArray(posts) && posts.length > 0) {
        posts.forEach((post, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${post.title}</td>
                <td>${post.body}</td>
                <td>
                    <a href="/posts/${post.id}" class="btn btn-primary btn-sm">View</a>
                    ${
                        userId == post.user_id
                            ? `
                                <a href="/posts/${post.id}/edit" class="btn btn-warning btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="deletePost(${post.id})">Delete</button>
                              `
                            : ''
                    }
                </td>
            `;
            postsList.appendChild(row);
        });
    } else {
        postsList.innerHTML = '<tr><td colspan="4">No posts available.</td></tr>';
    }
}

async function deletePost(postId) {
    if (confirm('Are you sure you want to delete this post?')) {
        try {
            const response = await fetch(`http://127.0.0.1:8000/api/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('token')}` // Ensure the token is stored correctly
                }
            });

            if (response.ok) {
                alert('Post deleted successfully');
                // Reload the posts after deletion
                location.reload();
            } else {
                console.error('Failed to delete post', response.statusText);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
}


    </script>
@endsection
