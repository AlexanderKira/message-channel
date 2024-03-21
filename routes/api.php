<?php

use App\Http\Controllers\API\Authentication\PersonalAccessTokenController;
use App\Http\Controllers\API\Message\MessageController;
use App\Http\Controllers\API\Reply\ReplyController;
use Illuminate\Support\Facades\Route;

Route::post('/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
Route::post('/personal-access-tokens/delete', [PersonalAccessTokenController::class, 'destroy'])->middleware('auth:sanctum');


Route::prefix('/messages')->group(function (){
    Route::get('/', [MessageController::class, 'index']);
    Route::get('/show/{message}', [MessageController::class, 'show']);
    Route::middleware('auth:sanctum')->group(function (){
        Route::post('/store', [MessageController::class, 'store']);
        Route::post('/reply', [ReplyController::class, 'store']);
        Route::post('/update/{message}', [MessageController::class, 'update']);
        Route::post('/delete/{message}', [MessageController::class, 'destroy']);
    });
});


