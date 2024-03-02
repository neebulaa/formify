<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;

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

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

 
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('forms', [FormController::class, 'store']);
        Route::get('forms', [FormController::class, 'index']);

        Route::middleware('formfound')->group(function () {
            Route::get('forms/{form_slug}', [FormController::class, 'show'])->middleware('creator');
            Route::get('forms/{form_slug}/view', [FormController::class, 'show'])->middleware('domain', 'firsttimesubmit');

            Route::middleware('creator')->group(function () {
                Route::post('forms/{form_slug}/questions', [QuestionController::class, 'store']);
                Route::delete('forms/{form_slug}/questions/{question_id}', [QuestionController::class, 'destroy']);
                Route::get('forms/{form_slug}/responses', [ResponseController::class, 'index']);
            });

            Route::post('forms/{form_slug}/responses', [ResponseController::class, 'store'])->middleware('domain', 'firsttimesubmit');
        });
    });
});
