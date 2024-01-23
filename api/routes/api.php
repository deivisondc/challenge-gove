<?php

use App\Http\Controllers\FileImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FileImportErrorController;
use App\Http\Controllers\NotificationController;

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


Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::get('/contacts/{contact}', [ContactController::class, 'show']);
Route::put('/contacts{contact}', [ContactController::class, 'update']);
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);

Route::get('/files', [FileImportController::class, 'index']);
Route::get('/files/{fileImport}', [FileImportController::class, 'show']);
Route::get('/files/{fileImport}/errors', [FileImportErrorController::class, 'index']);
Route::get('/files/{fileImport}/notifications', [NotificationController::class, 'index']);
Route::post('/files/import', [FileImportController::class, 'import']);

Route::put('/notifications/{notification}/retry', [NotificationController::class, 'retry']);
Route::put('/notifications/{notification}/update-date', [NotificationController::class, 'updateScheduledFor']);
Route::put('/notifications/{notification}/cancel', [NotificationController::class, 'cancel']);
