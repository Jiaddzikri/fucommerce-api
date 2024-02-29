<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Models\ProductDiscussion;
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
    Route::get("/product/{id}", "show");
    Route::get("/product", "search");
    Route::get("/product/{store}/{slug}", "showProductBySlug");
    Route::post("/product", "create");
    Route::put("/product/{id}", "update");
    Route::delete("/product/{id}", "destroy");
});

Route::controller(SellerController::class)->group(function () {
    Route::post("/seller", "store");
    Route::get("/seller/products", "productsList");
});

Route::controller(CategoryController::class)->group(function () {
    Route::get("/categories", "show");
    Route::get("/testing/{param}", "testingSimilarity");
});

Route::controller(StoreController::class)->group(function () {
    Route::get("/store/{store_domain}", "show");
    Route::get("/store/{store_domain}/products", "storeProducts");
    Route::get("/store/{store_domain}/reviews", "storeReviews");
});

Route::controller(DiscussionController::class)->group(function () {
    Route::post("/discussions/product", "writeProductDiscussion");
    Route::get("/discussions/product/{productSlug}", "showProductDiscussions");
    Route::post("/discussion/replies/product", "writeReplyDiscussion");
    Route::get("/discussions/replies/product/{productSlug}", "showProductReplies");
});
