@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <div class="container bg-light-custom form-container">
        <h2 class="mb-4">Edit Post</h2>

        <form id="edit-post-form">
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Body</label>
                <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
            </div>
            <input type="hidden" id="post-id" name="post_id">
            <button type="submit" class="btn btn-primary">Update Post</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const postId = @json($postId); // Retrieve the postId passed from the controller

           
            if (postId) {
                try {
                    const response = await fetch(`http://127.0.0.1:8000/api/posts/${postId}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('token')}`
                        }
                    });

                    if (response.ok) {
                        const post = await response.json();
                        console.log(post);
                        const data = post.data; // Access the data property
                        document.getElementById('title').value = data.title;
                        document.getElementById('body').value = data.body;
                        document.getElementById('post-id').value = data.id;
                    } else {
                        console.error('Failed to load post');
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        });

        document.getElementById('edit-post-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const postId = document.getElementById('post-id').value;
            const title = document.getElementById('title').value;
            const body = document.getElementById('body').value;

            try {
                const response = await fetch(`http://127.0.0.1:8000/api/posts/${postId}`, {
                    method: 'PUT',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ title, body })
                });

                if (response.ok) {
                    window.location.href = '{{ route('posts.index') }}'; // Redirect to the posts index page
                } else {
                    console.error('Failed to update post');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
@endsection
