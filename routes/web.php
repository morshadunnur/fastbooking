<?php

use App\Http\Controllers\Category\CategoryController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::group(['middleware' => 'auth'], function()  {
    Route::inertia('home', 'Welcome');
    Route::get('/', function () {
        return Inertia::render('welcome');
    });
    Route::get('categories', [CategoryController::class, 'index'])->name('category.page');
    Route::post('categories', [CategoryController::class, 'store'])->name('category.store');
    Route::patch('categories/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('categories/{id}', [CategoryController::class, 'delete'])->name('category.delete');
    Route::post('selected-delete', [CategoryController::class, 'selectedDelete'])->name('category.delete.all');

    // DATA Routes
    Route::get('category-list', [CategoryController::class, 'allCategory'])->name('category.list.data');
});
