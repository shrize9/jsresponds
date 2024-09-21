<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RespondController;
use App\Http\Controllers\Api\AskController;
use App\Http\Controllers\Api\AnswerController;

Route::get('/respond/', [RespondController::class, 'get']);
Route::post('/respond/', [RespondController::class, 'create']);

Route::get('/asks/', [AskController::class, 'list']);
Route::get('/asks/{askId}', [AskController::class, 'load']);
Route::get('/asks/next/{respondId}', [AskController::class, 'getNext']);

Route::post('/answer/', [AnswerController::class, 'create']);

