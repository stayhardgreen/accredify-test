<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['as' => 'api.v1.'],function() {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::group(['prefix' => '/v1/upload/', 'middleware' => 'auth:sanctum'], function () {
        Route::post('/file-upload', [FileUploadController::class, 'uploadJsonFile'])->name('file-upload');
    });
});
