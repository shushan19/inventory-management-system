<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Categories
Route::resource('categories', CategoryController::class)
    ->except(['show']);

// Menu Items
Route::resource('menu-items', MenuItemController::class);

// Ingredients
Route::resource('ingredients', IngredientController::class)
    ->except(['show']);
Route::post('ingredients/{ingredient}/add-stock', [IngredientController::class, 'addStock'])
    ->name('ingredients.add-stock');
Route::get('ingredients/{ingredient}/movements', [IngredientController::class, 'movements'])
    ->name('ingredients.movements');

// Recipes (nested under menu items)
Route::get('menu-items/{menuItem}/recipe', [RecipeController::class, 'show'])
    ->name('recipes.show');
Route::post('menu-items/{menuItem}/recipe', [RecipeController::class, 'store'])
    ->name('recipes.store');
Route::patch('menu-items/{menuItem}/recipe/{ingredient}', [RecipeController::class, 'update'])
    ->name('recipes.update');
Route::delete('menu-items/{menuItem}/recipe/{ingredient}', [RecipeController::class, 'destroy'])
    ->name('recipes.destroy');

// Orders
Route::resource('orders', OrderController::class)
    ->only(['index', 'create', 'store', 'show']);
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])
    ->name('orders.update-status');
