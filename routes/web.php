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
Route::post('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [
    'uses' => 'App\Http\Controllers\DashboardController@index',
    'as' => 'customer-listing'
  ]);
Route::post('/customersave', [App\Http\Controllers\DashboardController::class, 'saveCustomer'])->name('customersave');
Route::post('/getcustomer', [App\Http\Controllers\DashboardController::class, 'getcustomer'])->name('getcustomer');
Route::post('/customerdelete', [App\Http\Controllers\DashboardController::class, 'deleteCustomer'])->name('customerdelete');
Route::post('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('list');
Route::get('/product', [
      'uses' => 'App\Http\Controllers\ProductController@index',
      'as' => 'product-listing'
    ]);
Route::post('/productsave', [App\Http\Controllers\ProductController::class, 'saveProduct'])->name('productsave');
Route::post('/getproduct', [App\Http\Controllers\ProductController::class, 'getproduct'])->name('getproduct');
Route::post('/productdelete', [App\Http\Controllers\ProductController::class, 'deleteProduct'])->name('productdelete');

Route::post('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('list');
Route::get('/inventory', [
      'uses' => 'App\Http\Controllers\InventoryController@index',
      'as' => 'inventory-listing'
    ]);
Route::post('/inventorysave', [App\Http\Controllers\InventoryController::class, 'saveInventory'])->name('inventorysave');
Route::post('/getinventory', [App\Http\Controllers\InventoryController::class, 'getinventory'])->name('getinventory');
Route::post('/inventorydelete', [App\Http\Controllers\InventoryController::class, 'deleteInventory'])->name('inventorydelete');

Route::post('/report', [App\Http\Controllers\ReportController::class, 'index'])->name('list');
Route::get('/report', [
      'uses' => 'App\Http\Controllers\ReportController@index',
      'as' => 'report-listing'
    ]);
  