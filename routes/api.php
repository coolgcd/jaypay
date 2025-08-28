<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Api\BankDetailController;

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

// User login route
Route::post('/user/login', [UserLoginController::class, 'login']);
Route::post('/user/register', [UserLoginController::class, 'register']); // Add this line
Route::post('/bank-details', [BankDetailController::class, 'store']);
Route::put('/bank-details/{id}', [BankDetailController::class, 'update']);
// Protected route for authenticated users
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
