@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="form-container bg-light-custom">
        <h2 class="text-center mb-4">Login</h2>
        <form id="loginForm">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="button" onclick="loginUser()" class="btn btn-primary w-100">Login</button>
            <div id="responseMessage" class="mt-3 text-center text-danger"></div>
        </form>
        <div class="mt-3 text-center">
            <a href="{{ route('register') }}" class="text-primary">Don't have an account? Register here</a>
        </div>
    </div>
</div>

<script>
  async function loginUser() {
    const formData = new FormData(document.getElementById('loginForm'));

    try {
        const response = await fetch('http://127.0.0.1:8000/api/login', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        });

        const contentType = response.headers.get('content-type');

        if (contentType && contentType.includes('application/json')) {
            const result = await response.json();

         
            localStorage.setItem('token', result.token);

            if (response.ok) {
                window.location.href = '/'; // Redirect to home page or dashboard
            } else {
                document.getElementById('responseMessage').textContent = result.message || 'Login failed';
            }
        } else {
            const errorText = await response.text();
            console.error('Error response:', errorText);
            document.getElementById('responseMessage').textContent = 'Unexpected response format';
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('responseMessage').textContent = 'An error occurred. Please try again.';
    }
}

</script>
@endsection
