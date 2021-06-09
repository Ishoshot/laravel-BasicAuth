<?php

use App\Http\Controllers\AuthController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/* -------------------------------------------------------------------------- */
/*                            Authentication Routes                           */
/* -------------------------------------------------------------------------- */

Route::post('/register', [AuthController::class, 'register'])->name("register");
Route::post('/login', [AuthController::class, 'login'])->name("login");
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name("logout");


Route::middleware('auth:sanctum')->get('/roles', [AuthController::class, 'roles']);
