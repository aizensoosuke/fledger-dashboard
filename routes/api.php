<?php

use App\Http\Controllers\DataPointController;
use App\Http\Controllers\ExperimentController;
use App\Http\Controllers\NodeController;
use App\Http\Controllers\TimelessDataPointController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    if (app()->environment('local')) {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    }

    Route::get('/experiments/{experiment}/end', [ExperimentController::class, 'end'])
        ->name('experiments.end');
    Route::get('/experiments/{experiment}/target-page-id', [ExperimentController::class, 'targetPageId'])
        ->name('experiments.target-page-id');
    Route::resource('experiments', ExperimentController::class)
        ->only(['store', 'update']);

    Route::resource('experiments.nodes', NodeController::class)
        ->shallow()
        ->only(['store', 'update']);

    Route::resource('nodes.data-points', DataPointController::class)
        ->shallow()
        ->only(['store']);

    Route::resource('nodes.timeless-data-points', TimelessDataPointController::class)
        ->shallow()
        ->only(['store']);
});
