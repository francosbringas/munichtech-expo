<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

if (app()->environment('production')) {
    URL::forceScheme('https');
}

// ─────────────────────────────────────────────
//  Public
// ─────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─────────────────────────────────────────────
//  Guest-only (standard auth)
// ─────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// ─────────────────────────────────────────────
//  Google OAuth (no guest middleware – Socialite
//  needs to be reachable on the callback even if
//  the session briefly loses the "guest" state)
// ─────────────────────────────────────────────

Route::get('auth/google',          [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

//authenticated
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('dashboard', function () {
        return redirect()->route('projects.index');
    })->name('dashboard');

    Route::get('admin', [AdminController::class, 'index'])->name('admin.dashboard');

    //events
    Route::get('events',          [EventRegistrationController::class, 'index'])->name('events.index');
    Route::get('events/register', [EventRegistrationController::class, 'create'])->name('events.create');
    Route::post('events/register',[EventRegistrationController::class, 'store'])->name('events.store');

    //collaborations
    Route::get('collaborations',                           [CollaborationController::class, 'index'])->name('collaborations.index');
    Route::get('collaborations/create',                    [CollaborationController::class, 'create'])->name('collaborations.create');
    Route::post('collaborations',                          [CollaborationController::class, 'store'])->name('collaborations.store');
    Route::post('collaborations/{collaboration}/respond',  [CollaborationController::class, 'respond'])->name('collaborations.respond');

    //projects
    Route::get('projects',          [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create',   [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects',         [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}',[ProjectController::class, 'show'])->name('projects.show');

    //search
    Route::get('search', [SearchController::class, 'index'])->name('search.index');
});
