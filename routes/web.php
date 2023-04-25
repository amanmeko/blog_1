<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Porifle update
Route::middleware(['auth'])->group(function () {
    Route::get('profile',[App\Http\Controllers\HomeController::class,'ProfileIndex'])->name('profile');
    Route::post('profile/{user}',[App\Http\Controllers\HomeController::class,'update'])->name('profile.update');

    Route::resource('category', App\Http\Controllers\CategoryController::class);

    Route::resource('post', App\Http\Controllers\PostController::class, ['except' => ['show']]);
    Route::get('/post/{slug}', [App\Http\Controllers\postController::class,'show'])->name('post.show');

});


