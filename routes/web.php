<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['web'])->group(function () {
    Route::get('/', [CrudController::class, 'index']);
    Route::get('/crud', [CrudController::class, 'index']);
    Route::get('/crud/get-all-products', [CrudController::class, 'getAllProducts']);
    Route::post('/crud/import-products', [CrudController::class, 'importProductsFromJson'])->name('crud.import-products');

    // CRUD routes
    Route::post('/crud/create', [CrudController::class, 'create'])->name('crud.create');
    Route::match(['post', 'put'], '/crud/update', [CrudController::class, 'update'])->name('crud.update');
    Route::post('/crud/delete', [CrudController::class, 'delete'])->name('crud.delete');
});