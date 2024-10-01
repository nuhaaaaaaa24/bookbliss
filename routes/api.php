<?php

use App\Http\Controllers\Api\ChallengeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VirtualBookshelf;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ReviewController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Users
Route::post('/user-register', [UserController::class, 'register']);
Route::post('/user-login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user-profile', [UserController::class, 'userProfile']);
    Route::put('/user-profile', [UserController::class, 'updateProfile']);
});

//Virtual Bookshelf
Route::middleware('auth:api')->group(function () {
    Route::post('/books', [VirtualBookshelf::class, 'store']);
    Route::get('/books/{id}', [VirtualBookshelf::class, 'show']);
    Route::put('/books/{id}', [VirtualBookshelf::class, 'update']);
    Route::delete('/books/{id}', [VirtualBookshelf::class, 'destroy']);
    Route::get('/books-all', [VirtualBookshelf::class, 'getAllBooks']);
});

//Groups
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-groups', [GroupController::class, 'store']);
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups/{group}/messages', [MessageController::class, 'store']);
    Route::get('/groups/{group}/messages', [MessageController::class, 'index']);
    Route::post('/groups/{id}/join', [GroupController::class, 'joinGroup']);
});

//Reviews
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/reviews', [ReviewController::class, 'index']);

//Challenges
Route::post('/challenges', [ChallengeController::class, 'store']); // Create a challenge
Route::post('/challenges/{challengeId}/join', [ChallengeController::class, 'joinChallenge']); // Join a challenge
Route::get('/challenges/{challengeId}/user/{userId}/progress', [ChallengeController::class, 'userProgress']); // Get user progress in challenge
Route::post('/challenges/{challengeId}/user/{userId}/complete', [ChallengeController::class, 'completeChallenge']); // Mark challenge as complete
