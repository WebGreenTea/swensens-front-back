<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\testController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//api
// Route::get('/test',[testController::class, 'hello']);
Route::middleware('auth')->get('/test',[testController::class, 'hello']);

Route::get('/',[testController::class, 'hello']);

Route::get('/refresh',[AuthController::class, 'refresh']);
Route::post('/login',[AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);

Route::middleware('auth')->delete('/logout',[AuthController::class, 'logout']);
Route::middleware('auth')->get('/user',[AuthController::class, 'user']);



Route::get('/product',[ProductController::class, 'allProduct']);
Route::get('/product/{id}',[ProductController::class, 'oneProduct']);
Route::post('/product/add',[ProductController::class, 'addProduct']);
Route::put('/product/edit/{id}',[ProductController::class, 'editProduct']);
Route::delete('/product/del/{id}',[ProductController::class, 'delProduct']);

Route::get('/product/type/all',[ProductController::class, 'allType']);
Route::post('/product/type/add',[ProductController::class, 'addType']);
Route::put('/product/type/edit/{id}',[ProductController::class, 'editType']);
Route::delete('/product/type/del/{id}',[ProductController::class, 'delType']);