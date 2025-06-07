<?php

use App\Http\Controllers\ExperimentController;
use App\Http\Controllers\NodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    if (app()->environment('local')) {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    }

    Route::resource('experiments', ExperimentController::class)
        ->only(['store']);
    Route::resource('experiments.nodes', NodeController::class)
        ->only(['store', 'update'])
        ->shallow();
});
