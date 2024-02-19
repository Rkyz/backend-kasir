<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return 'ss';
});


Route::post('/login', [AuthController::class,'login']);

Route::prefix('buying')->group(function () {
    Route::get('/account', [AuthController::class, 'getAllAccount']);
});

Route::prefix('buying')->group(function () {
    Route::get('/', [BuyingController::class, 'getAllBuying']);
    Route::get('/{id}', [BuyingController::class, 'searchBuying']);
    Route::post('/create', [BuyingController::class, 'createBuying']);
});

Route::get('/detail',[BuyingController::class, 'getAllBuyingDetail']);
Route::get('/detail/{id}',[BuyingController::class, 'searchDetail']);

Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'getAllCustomer']);
    Route::get('/{id}', [CustomerController::class, 'searchCustomer']);
    Route::post('/create', [CustomerController::class, 'createCustomer']);
    Route::put('/edit/{id}', [CustomerController::class, 'editCustomer']);
    Route::delete('/delete/{id}', [CustomerController::class, 'deleteCustomer']);
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'getAllProduct']);
    Route::get('/{id}', [ProductController::class, 'searchProduct']);
    Route::post('/create', [ProductController::class, 'createProduct']);
    Route::put('/edit/{id}', [ProductController::class, 'editProduct']);
    Route::delete('/delete/{id}', [ProductController::class, 'deleteProduct']);
});