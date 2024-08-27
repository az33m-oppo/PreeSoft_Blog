<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Preesoft Blog')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::check() ? Auth::id() : '' }}">
    <style>
        body {
            color: #333;
        }
        .container {
            max-width: 800px;
        }
        .bg-light-custom {
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container {
            padding: 2rem;
            border-radius: 0.75rem;
        }
        .navbar-custom {
            background-color: #007bff;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #fff;
        }
        .navbar-custom .nav-link.active {
            font-weight: bold;
        }
      
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container">
                <a class="navbar-brand" href="#">Preesoft Blog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    @if(Auth::check())
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('posts.index')}}">All Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('posts.create')}}">Add New Post</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('users.like')}}">All user with likes</a>
                        </li>
                    </ul>
                    @endif
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="logout()">Logout</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-3">
        <div class="container text-center">
            &copy; {{ date('Y') }} Preesoft Blog. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script>
       function logout() {
            fetch('http://127.0.0.1:8000/api/logout', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}`
                },
                body: JSON.stringify({
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Logout failed with status ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Logout successful:', data);
                window.location.href = '/login'; // Adjust this to your login route
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during logout');
            });
        }
    </script>
</body>
</html>
