<?php

use App\Http\Controllers\Api\ChallengeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

//admin
Route::get('/admin-login', [AdminController::class, 'showLoginForm'])->name('adminLogin');
Route::post('/admin-login', [AdminController::class, 'adminLogin'])->name('adminLogin');
Route::get('/admin-logout', [AdminController::class, 'logout'])->name('adminLogout');
Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('adminDashboard');
Route::get('/admin/groups', [AdminController::class, 'groups'])->name('groups.index');
Route::get('/admin/reviews', [AdminController::class, 'showReviews'])->name('admin.reviews');
Route::get('/admin/challenges/overview', [AdminController::class, 'challengesOverview'])->name('admin.challenges');
Route::get('/admin/challenges/participants', [AdminController::class, 'challengesParticipants'])->name('admin.challengesParticipants');
Route::put('challenges/{challengeId}/users/{userId}/progress', [ChallengeController::class, 'updateProgress']);


