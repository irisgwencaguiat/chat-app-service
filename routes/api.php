<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('sign-up', [AuthController::class, 'signUp']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::middleware(['auth:api'])->group(function () {
    Route::get('rooms/users/{user}', [RoomController::class, 'checkIfRoomExist']);
    Route::put('rooms/users/last-read_at/{room}', [RoomController::class, 'updateLastReadAt']);
   Route::apiResource('rooms', RoomController::class);
   Route::apiResource('chats', ChatController::class);
   Route::apiResource('users', UserController::class);
});
