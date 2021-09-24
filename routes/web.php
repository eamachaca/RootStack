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

Route::get('/queue/categories', [App\Http\Controllers\HomeController::class, 'queueCategories'])->name('queue.categories');
Route::get('/queue/products', [App\Http\Controllers\HomeController::class, 'queueProducts'])->name('queue.products');
Route::get('/reset/products', [App\Http\Controllers\HomeController::class, 'resetDatabase'])->name('database');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/category/{id}', [App\Http\Controllers\HomeController::class, 'products'])->name('products');
