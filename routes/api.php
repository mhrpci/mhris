<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CelebrantsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ServerTimeController;
use App\Http\Controllers\AttendanceController;
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

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Employee Routes
Route::get('/employees/{employee}/signature', [EmployeeController::class, 'getSignature']);

// Celebrants Routes
Route::get('/today-celebrants', [CelebrantsController::class, 'getTodayCelebrants']);
Route::post('/dismiss-celebrants', [CelebrantsController::class, 'dismissCelebrants']);

// Notification Routes
Route::get('notifications/health', [NotificationsController::class, 'healthCheck']);

// Server Time Routes
Route::get('/server-time', [ServerTimeController::class, 'getTime']);
Route::get('/verify-timestamp/{timestamp}/{hash}', [ServerTimeController::class, 'verifyTimestamp']);

// Employee information API endpoint
Route::get('/employee-info', [AttendanceController::class, 'getEmployeeInfo'])->middleware('auth');

// Store attendance capture
Route::post('/attendance/store', [AttendanceController::class, 'storeAttendanceCapture'])->middleware('auth');
