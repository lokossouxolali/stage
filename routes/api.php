<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CandidatureController;
use App\Http\Controllers\Api\OffreController;

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

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/register/verify', [AuthController::class, 'verifyRegister']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

Route::get('/offres-publiques', [OffreController::class, 'publiques']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('offres', OffreController::class)->names('api.offres');
    Route::get('/offres/{offre}/candidatures', [OffreController::class, 'candidatures']);

    Route::apiResource('candidatures', CandidatureController::class)->names('api.candidatures');
    Route::post('/candidatures/{candidature}/accepter', [CandidatureController::class, 'accepter']);
    Route::post('/candidatures/{candidature}/refuser', [CandidatureController::class, 'refuser']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
