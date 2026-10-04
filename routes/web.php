<?php

use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — GadgetHub (Futuristic Cyber-Tech Gadget Hub)
|--------------------------------------------------------------------------
*/

// ==============================
// Rute Publik
// ==============================

// Landing Page
Route::get('/', [BlogController::class, 'landing'])->name('home');

// Blog Publik
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// ==============================
// Rute Autentikasi Admin
// ==============================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==============================
// Rute Admin CMS (Perlu Login)
// ==============================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Redirect /admin ke blog index
    Route::get('/', fn () => redirect()->route('admin.blogs.index'));

    // Blog CRUD
    Route::resource('blogs', AdminBlogController::class);

    // Toggle publish/draft status
    Route::patch('blogs/{blog}/toggle-status', [AdminBlogController::class, 'toggleStatus'])
        ->name('blogs.toggle-status');
});

