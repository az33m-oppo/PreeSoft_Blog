@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="form-container bg-light-custom">
        <h2 class="text-center mb-4">Register</h2>
        <form id="registerForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
            </div>
            <button type="button" onclick="registerUser()" class="btn btn-primary w-100">Register</button>
            <div id="responseMessage" class="mt-3 text-center text-danger"></div>
        </form>
        <div class="mt-3 text-center">
            <a href="{{ route('login') }}" class="text-primary">Already have an account? Login here</a>
        </div>
    </div>
</div>

<script>
    async function registerUser() {
        const formData = new FormData(document.getElementById('registerForm'));

        try {
            const response = await fetch('http://127.0.0.1:8000/api/register', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (response.ok) {
                window.location.href = '/login'; // Redirect to login after successful registration
            } else {
                document.getElementById('responseMessage').textContent = result.message || 'Registration failed';
            }
        } catch (error) {
            document.getElementById('responseMessage').textContent = 'An error occurred. Please try again.';
        }
    }
</script>
@endsection
