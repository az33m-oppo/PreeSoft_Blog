@extends('layouts.app')

@section('title', 'Users with Comment Likes')

@section('content')
    <div class="container mt-5">
        <h1>Users with Comment Likes</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                  
                </tr>
            </thead>
            <tbody>
                @foreach($usersWithLikedCommentsOnPosts as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                      
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
