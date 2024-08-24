<!-- resources/views/posts/show.blade.php -->
@extends('layouts.app')

@section('title', 'Post Details')

@section('content')
    <div class="container bg-light-custom form-container">
        <h2 id="post-title" class="mb-4"></h2>
        <p id="post-body"></p>

        <h3>Comments</h3>
        <div id="comments-list">
            <!-- Comments will be loaded here by JavaScript -->
        </div>

        <h4>Add a Comment</h4>
        <form id="comment-form">
            @csrf
            <div class="mb-3">
                <label for="comment-body" class="form-label">Comment</label>
                <textarea id="comment-body" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
<!-- Edit Comment Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-comment-form">
                    @csrf
                    <div class="mb-3">
                        <label for="edit-comment-body" class="form-label">Comment</label>
                        <textarea id="edit-comment-body" class="form-control" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="edit-comment-id">
                    <button type="submit" class="btn btn-primary">Update Comment</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const postId = {{ $postId }}; // Pass the post ID from the controller
            
            try {
                // Fetch post details
                const postResponse = await fetch(`http://127.0.0.1:8000/api/posts/${postId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });

                if (postResponse.ok) {
                    const post = await postResponse.json();
                    document.getElementById('post-title').textContent = post.data.title;
                    document.getElementById('post-body').textContent = post.data.body;
                } else {
                    console.error('Failed to load post');
                }

                // Fetch comments
                const commentsResponse = await fetch(`http://127.0.0.1:8000/api/posts/${postId}/comments`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });

                if (commentsResponse.ok) {
                    const comments = await commentsResponse.json();
                    renderComments(comments.data);
                } else {
                    console.error('Failed to load comments');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        function renderComments(comments) {
            const commentsList = document.getElementById('comments-list');
            commentsList.innerHTML = '';

            comments.forEach(comment => {
                console.log('Comment User ID:', comment.user_id);
        console.log('Authenticated User ID:', {{ Auth::id() }});
                const commentDiv = document.createElement('div');
                commentDiv.classList.add('mb-3');
                commentDiv.innerHTML = `
                    <p>${comment.comment}</p>
                    <small>Posted by: ${comment.user}</small>
                    
                    <div class="mt-2">
                        <span>${comment.likes_count} Likes</span>
                        ${comment.user_id === {{ Auth::id() }} ? `
                            <button class="btn btn-warning btn-sm" onclick="editComment(${comment.id})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteComment(${comment.id})">Delete</button>
                        ` : ''}
                     <button class="btn btn-sm ${comment.liked ? 'btn-success' : 'btn-outline-success'}" onclick="toggleLike(${comment.id}, ${comment.liked})">
    ${comment.liked ? 'Unlike' : 'Like'}
</button>
                    </div>
                `;
                commentsList.appendChild(commentDiv);
            });
        }

        document.getElementById('comment-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            const commentBody = document.getElementById('comment-body').value;

            try {
                const response = await fetch(`http://127.0.0.1:8000/api/comments`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    },
                    body: JSON.stringify({
                        post_id: {{ $postId }},
                        comment: commentBody
                    })
                });

                if (response.ok) {
                    
                    location.reload(); // Reload the page to show the new comment
                } else {
                    console.error('Failed to add comment');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });


        ////////////////////////// Delete comment ///////////////////////////////////////
        async function deleteComment(commentId) {
            if (confirm('Are you sure you want to delete this comment?')) {
                try {
                    const response = await fetch(`http://127.0.0.1:8000/api/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${localStorage.getItem('token')}`
                        }
                    });

                    if (response.ok) {
                        alert('Comment deleted successfully');
                        location.reload(); // Reload the page to reflect the deletion
                    } else {
                        console.error('Failed to delete comment');
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        }
////////////////////////// end Delete comment ///////////////////////////////////////

///////////////////////////add like or unlike ////////////////////////
async function toggleLike(commentId, isLiked) {
    const url = isLiked ? 'http://127.0.0.1:8000/api/comments/unlike' : 'http://127.0.0.1:8000/api/comments/like';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            },
            body: JSON.stringify({ comment_id: commentId })
        });

        if (response.ok) {
            location.reload(); // Reload to update like/unlike status
        } else {
            console.error('Failed to toggle like/unlike');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

        //////////////////end like //////////////////////////
        async function editComment(commentId) {
    try {
        const response = await fetch(`http://127.0.0.1:8000/api/comments/${commentId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });

        if (response.ok) {
            const comment = await response.json();
            document.getElementById('edit-comment-body').value = comment.data.comment;
            document.getElementById('edit-comment-id').value = comment.data.id;
            new bootstrap.Modal(document.getElementById('editCommentModal')).show(); // Show the modal
        } else {
            console.error('Failed to load comment');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}


//////////////comment update //////////////////
document.getElementById('edit-comment-form').addEventListener('submit', async function(event) {
    event.preventDefault(); // Prevent the default form submission

    const commentId = document.getElementById('edit-comment-id').value; // Get the comment ID
    const commentBody = document.getElementById('edit-comment-body').value; // Get the updated comment text

    try {
        const response = await fetch(`http://127.0.0.1:8000/api/comments/${commentId}`, {
            method: 'PUT', // HTTP method for updating data
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('token')}` // Ensure the token is sent
            },
            body: JSON.stringify({
                comment: commentBody // Send the updated comment in the request body
            })
        });

        if (response.ok) {
            alert('Comment updated successfully');
            location.reload(); // Reload the page to reflect the updated comment
        } else {
            console.error('Failed to update comment');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

    </script>
@endsection
