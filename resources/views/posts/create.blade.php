@extends('layouts.app')

@section('title', 'Add New Post')

@section('content')
    <div class="container bg-light-custom form-container">
        <h2 class="mb-4">Add New Post</h2>

        <form id="postForm">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
                <div class="invalid-feedback" id="titleError"></div>
            </div>

            <div class="mb-3">
                <label for="body" class="form-label">Body</label>
                <textarea id="body" name="body" class="form-control" rows="5" required></textarea>
                <div class="invalid-feedback" id="bodyError"></div>
            </div>

            <button type="submit" class="btn btn-primary">Save Post</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script>
        document.getElementById('postForm').addEventListener('submit', async function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            try {
                const response = await fetch('http://127.0.0.1:8000/api/posts', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: formData
                });

                if (response.ok) {
                    const result = await response.json();
                    
                    window.location.href = "{{ route('posts.index') }}";
                } else {
                    const errors = await response.json();
                    handleErrors(errors.errors);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        function handleErrors(errors) {
            document.getElementById('titleError').textContent = errors.title ? errors.title.join(', ') : '';
            document.getElementById('bodyError').textContent = errors.body ? errors.body.join(', ') : '';
        }
    </script>
@endsection
