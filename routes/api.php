<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/callback', [\App\Http\Controllers\Api\CallbackController::class, 'index'])->name('callback');