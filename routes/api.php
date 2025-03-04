<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

Route::get('categories/tree', [CategoryController::class, 'tree'])->name('categories.tree');

Route::apiResource('products', ProductController::class);
Route::apiResource('categories', CategoryController::class);

