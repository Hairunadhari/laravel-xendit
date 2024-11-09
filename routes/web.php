<?php

use Illuminate\Support\Facades\Route;

Route::get('/',  function () {
    return view('home.index');
});

//route donations resource
Route::resource('/donations', \App\Http\Controllers\DonationController::class, ['only' => ['index', 'create', 'store']]);