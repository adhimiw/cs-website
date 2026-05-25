<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\BlogPostController;

Route::middleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \App\Http\Middleware\TrackVisitor::class,
])->group(function () {
    Route::get('/contents', [ContentController::class, 'index']);
    Route::post('/chat', [ChatController::class, 'chat'])->middleware('throttle:200,1');
    Route::post('/contact', [ContactController::class, 'submit'])->middleware('throttle:20,1');
});

Route::post('/track-visit', [ContentController::class, 'trackVisit']);

// Blog CRUD and AI Optimization routes
Route::get('/blogs', [BlogPostController::class, 'index']);
Route::get('/blogs/{slug}', [BlogPostController::class, 'show']);
Route::post('/blogs', [BlogPostController::class, 'store']);
Route::put('/blogs/{slug}', [BlogPostController::class, 'update']);
Route::delete('/blogs/{slug}', [BlogPostController::class, 'destroy']);
Route::post('/blogs/optimize', [BlogPostController::class, 'optimize']);

