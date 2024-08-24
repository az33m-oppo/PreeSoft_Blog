# Preesoft Blog

Preesoft Blog is a web application built using Laravel for the backend and a front-end interface to manage posts and comments. Users can create, read, update, and delete posts and comments, as well as like or unlike comments.

## Features

- User authentication with Laravel Sanctum(login/register)
- Create, read, update, and delete posts
- Comment on posts
- Edit and delete comments (only by the owner)
- Like and unlike comments

## Installation

Clone Repository

git clone https://github.com/az33m-oppo/PreeSoft_Blog.git
   cd PreeSoft_Blog

   Install Dependencies

Make sure you have Composer and NPM installed. Run the following commands to install PHP and JavaScript dependencies:

composer install
npm install

Environment Setup

Copy the example environment file and set up your environment variables:

cp .env.example .env

Generate Application Key

php artisan key:generate

Run Migrations

php artisan migrate
Serve the Application

Start the local development server:

php artisan serve

Usage
Login/Register: Access the login and registration pages to create and authenticate users.
Posts: Navigate to "All Posts" to view, create, and manage posts.
Comments: Add comments to posts, and manage them as per the ownership and permissions.
API Endpoints
Posts: /api/posts
Comments: /api/comments
Like/Unlike Comments: /api/comments/like and /api/comments/unlike