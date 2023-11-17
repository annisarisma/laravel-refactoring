<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\QuestionController;
use App\Http\Controllers\api\v1\VoiceController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::group(['middleware' => 'auth:sanctum'], function() {
        Route::controller(QuestionController::class)->group(function () {
            Route::get('question/list-question', 'index');
            Route::post('question/store-question', 'store');
            Route::delete('question/destroy-question/{id}', 'destroy');
            Route::get('question/show-question/{id}', 'show');
        });
        Route::controller(VoiceController::class)->group(function () {
            // Route::post('voice/check-voice', 'check');
            Route::post('voice/store-voice', 'store');
            Route::post('voice/update-voice', 'update');
        });
    });
});