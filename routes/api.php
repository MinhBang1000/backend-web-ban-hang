<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\Category;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * REGISTER AND LOGIN
 */
Route::post('auth/register',[AuthController::class,'register']);
Route::post('auth/login',[AuthController::class,'login']);

/**
 * MIDDLEWARE EVERY CALL API
 */
Route::middleware('auth:api')->group(function(){
    /**
     * PROFILE 
     */
    Route::get('profile',[ProfileController::class,'showProfile']);
    Route::post('profile/update',[ProfileController::class,'updateById']);
    Route::post('profile/avatar',[ProfileController::class,'updateAvatar']);
    Route::post('profile/email',[ProfileController::class,'updateEmail']);
    /**
     * CATEGORY
     */
    Route::get('category/all',[CategoryController::class,'indexByGuest']);

    /**
     * PRODUCT
     */
    Route::get('product/all',[ProductController::class,'indexByGuest']);
    /**
     * CHANGE PASSWORD
     */
    Route::post('profile/password',[ProfileController::class,'updatePassword']);

    /**
     * LOGOUT API
     */
    Route::post('auth/logout',[AuthController::class,'logout']);
});

Route::middleware(['auth:api','scope:admin,super_admin'])->group(function(){
    /**
     * CATEGORY
     */
    Route::apiResource('category',CategoryController::class);
    Route::post('category/update/{id}',[CategoryController::class,'updateById']);

    /**
     * PRODUCT
     */
    Route::apiResource('product',ProductController::class);
    Route::post('product/update/{id}',[ProductController::class,'updateById']);
});