@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
    <div class="text-center">
        @if(Auth::check())
            <h1 class="display-4">Welcome, {{ Auth::user()->name }}!</h1>
        @endif

        <p class="lead">You are logged in and ready to explore the application.</p>
    </div>
@endsection
