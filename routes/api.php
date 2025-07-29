<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\TestConnectionController;

Route::get('/test-connection', TestConnectionController::class);

Route::get('/movies', [MovieController::class, 'index']);