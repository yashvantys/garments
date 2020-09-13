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
  Route::post('/product', [App\Http\Controllers\ProductController::class, 'index'])->name('list');
  Route::get('/product', [
      'uses' => 'App\Http\Controllers\ProductController@index',
      'as' => 'product-listing'
    ]);
  Route::post('/customersave', [App\Http\Controllers\DashboardController::class, 'saveCustomer'])->name('customersave');
  Route::post('/getcustomer', [App\Http\Controllers\DashboardController::class, 'getcustomer'])->name('getcustomer');
  Route::post('/customerdelete', [App\Http\Controllers\DashboardController::class, 'deleteCustomer'])->name('customerdelete');