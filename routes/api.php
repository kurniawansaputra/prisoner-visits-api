<?php

use App\Http\Controllers\Api\SuggestionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [UserController::class, 'login']);

Route::get('/visitors', [VisitorController::class, 'index']);
Route::get('/visitors-by-month', [VisitorController::class, 'getVisitorsByMonthAndYear']);
Route::post('/visitor-store', [VisitorController::class, 'store']);
Route::post('/visitor-update/{id}', [VisitorController::class, 'update']);
Route::post('/visitor-delete/{id}', [VisitorController::class, 'destroy']);
Route::get('/count-visitors', [VisitorController::class, 'countVisitors']);

Route::get('/suggestions', [SuggestionController::class, 'index']);
Route::post('/suggestion-store', [SuggestionController::class, 'store']);
