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

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('dashboard', [ProjectController::class, 'index'])->name('dashboard');

    Route::get('events', [EventRegistrationController::class, 'index'])->name('events.index');
    Route::get('events/register', [EventRegistrationController::class, 'create'])->name('events.create');
    Route::post('events/register', [EventRegistrationController::class, 'store'])->name('events.store');

    Route::get('collaborations', [CollaborationController::class, 'index'])->name('collaborations.index');
    Route::get('collaborations/create', [CollaborationController::class, 'create'])->name('collaborations.create');
    Route::post('collaborations', [CollaborationController::class, 'store'])->name('collaborations.store');
    Route::post('collaborations/{collaboration}/respond', [CollaborationController::class, 'respond'])->name('collaborations.respond');

    Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('projects/{project}/milestones', [ProjectController::class, 'storeMilestone'])->name('projects.milestones.store');
    Route::post('projects/{project}/tasks', [ProjectController::class, 'storeTask'])->name('projects.tasks.store');
    Route::patch('milestones/{milestone}/status', [ProjectController::class, 'updateMilestoneStatus'])->name('milestones.updateStatus');
    Route::patch('tasks/{task}/status', [ProjectController::class, 'updateTaskStatus'])->name('tasks.updateStatus');


    Route::get('search', [SearchController::class, 'index'])->name('search.index');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::patch('users/{user}/role', [AdminController::class, 'updateUserRole'])->name('update-user-role');
        Route::patch('users/{user}/toggle-active', [AdminController::class, 'toggleUserActive'])->name('toggle-user-active');
        Route::patch('users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle-admin');
        Route::patch('event-registrations/{registration}/status', [AdminController::class, 'updateEventRegistrationStatus'])->name('update-event-status');
        Route::patch('projects/{project}/admin-status', [AdminController::class, 'updateProjectAdminStatus'])->name('update-project-admin-status');
    });
});
