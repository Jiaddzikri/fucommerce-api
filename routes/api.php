<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// user controller
Route::controller(UserController::class)->group(function () {
    Route::patch("/user/{id}", "edit");
});

// login controller
Route::controller(LoginController::class)->group(function () {
    Route::post("/user/login", "login");
});

// register controller
Route::controller(RegisterController::class)->group(function () {
    Route::post("/user/register", "register");
});

// session controller
Route::controller(SessionController::class)->group(function () {
    Route::get("/user/session",  "find");
    Route::delete("/user/session",  "delete");
});

// product controller
Route::controller(ProductController::class)->group(function () {
    Route::get("/products", "index");
    Route::get("/products/{id}", "show");
    Route::get("/products/search/{param}", "search");
    Route::post("/products", "create");
    Route::put("/products/{id}", "update");
    Route::delete("/products/{id}", "destroy");
});

Route::controller(SellerController::class)->group(function () {
    Route::post("/seller", "store");
});

Route::controller(CategoryController::class)->group(function () {
    Route::get("/categories/{param}", "show");
});
