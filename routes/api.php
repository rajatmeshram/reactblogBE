<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\TempImageController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('blogs',[BlogController::class,'storeBlog']);
Route::post('save-temp-image',[TempImageController::class,'store']);
Route::get('allblogs',[BlogController::class,'index']);
Route::get('details/{id}',[BlogController::class,'singleBlog']);
Route::put('editblog/{id}',[BlogController::class,'updateBlog']);
Route::delete('delete/{id}',[BlogController::class,'destroyBlog']);