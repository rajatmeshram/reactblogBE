<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Controllers\BlogController;
Route::get('/', function () {
    return view('welcome');
});

 //Route::post('blogs',[BlogController::class,'storeBlog']);
// Route::prefix('api')->group(function () {
//     Route::post('/blogs', [BlogController::class, 'storeBlog']);
//     // Add more API routes here
// });