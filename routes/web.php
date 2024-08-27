<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// Registration Page
Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

// Login Page
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::middleware('auth')->group(function () {
    // Route to display the "Add New Post" page
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

    // Route to display all posts
    Route::get('/posts/all', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.detail');
    


    Route::get('/users-with-likes', [PostController::class, 'showUsersWithLikes'])->name('users.like');

});


