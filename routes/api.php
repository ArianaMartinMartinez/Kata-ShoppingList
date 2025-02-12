<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index'])->name('apiHomeProducts');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('apiShowProduct');
Route::post('/products', [ProductController::class, 'store'])->name('apiStoreProduct');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('apiUpdateProduct');
Route::delete('/products/{id}', [ProductController::class, 'destroyOneProduct'])->name('apiDestroyProduct');
Route::delete('/products/list', [ProductController::class, 'destroyAllProducts'])->name('apiDestroyList');