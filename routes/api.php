<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ServerTimeController;
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


// Example protected route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/employees/{employee}/signature', function (App\Models\Employee $employee) {
    return response()->json([
        'signature' => $employee->signature ? Storage::url($employee->signature) : null,
        'signature_path' => $employee->signature
    ]);
});

Route::get('notifications/health', [NotificationsController::class, 'healthCheck']);

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::get('/today-celebrants', function () {
    // Get today's celebrants
    $today = now()->format('m-d');
    $celebrants = \App\Models\Employee::whereRaw("DATE_FORMAT(birth_date, '%m-%d') = ?", [$today])
        ->select('first_name', 'last_name', 'department', 'profile_picture')
        ->get()
        ->map(function ($employee) {
            return [
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'department' => $employee->department,
                'profile_picture' => $employee->profile_picture ? asset($employee->profile_picture) : null
            ];
        });

    // Check if user has dismissed the notification today
    $userDismissed = false;
    if (auth()->check()) {
        $userDismissed = Cache::get('celebrants_dismissed_' . auth()->id() . '_' . now()->format('Y-m-d'), false);
    }

    return response()->json([
        'celebrants' => $celebrants,
        'userDismissed' => $userDismissed
    ]);
});

Route::post('/dismiss-celebrants', function () {
    if (auth()->check()) {
        Cache::put(
            'celebrants_dismissed_' . auth()->id() . '_' . now()->format('Y-m-d'),
            true,
            now()->endOfDay()
        );
    }
    return response()->json(['success' => true]);
});

// Server Time Routes
Route::get('/server-time', [ServerTimeController::class, 'getTime']);
Route::get('/verify-timestamp/{timestamp}/{hash}', [ServerTimeController::class, 'verifyTimestamp']);
