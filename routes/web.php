<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/news/{page?}', [BlogController::class, 'index'])->name('news.page');
