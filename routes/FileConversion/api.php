<?php

use App\Http\Controllers\Api\V1\FileConverter\FileConvertController;
use App\Http\Controllers\Api\V1\User\UserJobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    // Converter
    Route::post('convert', [FileConvertController::class, 'convert']);
    Route::get('converter/allowed-file-formats',
        [FileConvertController::class, 'showAllowedFileFormats']);

    // User jobs
    Route::apiResource('users.jobs', UserJobController::class)->only(['index', 'show']);
    Route::get('users/jobs/{job}/download', [UserJobController::class, 'download']);
});



