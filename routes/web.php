<?php

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\TourPackageController;
use App\Models\BoreActivity;
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
Route::get('/abc', function (){
   $bore_data = BoreActivity::get('api_response')->map(function ($f){
       return [
           'activity' => $f['api_response']['activity'],
           'price' => (float) $f['api_response']['price'],
       ];
   });
   return $bore_data;
});

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

    Route::get('tour-package', [TourPackageController::class, 'index'])->name('tour.package.page');
    Route::post('tour-package', [TourPackageController::class, 'store'])->name('tour.package.store');
    Route::post('tour-package-delete', [TourPackageController::class, 'delete'])->name('tour.package.delete');

    // DATA Routes
    Route::get('category-list', [CategoryController::class, 'allCategory'])->name('category.list.data');
    Route::get('tour-package-list', [TourPackageController::class, 'allTourPackage'])->name('tour.package.data');
    // set it post instead of put/patch request due to laravel cors issue
    Route::post('tour-package-update', [TourPackageController::class, 'update'])->name('tour.package.update');
//    Route::post('tour-package-image-remove', [TourPackageController::class, 'imageRemove'])->name('tour.package.image.remove');
    Route::post('tour-package-image-remove', [TourPackageController::class, 'imageRemoveFromGallery'])->name('tour.package.image.remove');
});
