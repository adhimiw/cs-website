<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContentController;

Route::middleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \App\Http\Middleware\TrackVisitor::class,
])->group(function () {
    Route::get('/contents', [ContentController::class, 'index']);
    Route::post('/chat', [ChatController::class, 'chat'])->middleware('throttle:60,1');
    Route::post('/contact', [ContactController::class, 'submit'])->middleware('throttle:20,1');
});

Route::post('/track-visit', [ContentController::class, 'trackVisit']);
