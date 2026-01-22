<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });

    // Rides API
    Route::get('/rides', [\App\Http\Controllers\RideController::class, 'apiIndex']);
    Route::get('/rides/{ride}', [\App\Http\Controllers\RideController::class, 'apiShow']);

    // Rooms API
    Route::get('/rooms', [\App\Http\Controllers\RoomController::class, 'apiIndex']);
    Route::get('/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'apiShow']);

    // Bookings API
    Route::get('/bookings', [\App\Http\Controllers\AdminController::class, 'apiBookings']);
    Route::post('/bookings', [\App\Http\Controllers\AdminController::class, 'apiStoreBooking']);
});

// Public API routes
Route::get('/rides/active', function () {
    return \App\Models\Ride::where('is_active', true)->get();
});

Route::get('/tickets', function () {
    return \App\Models\TicketType::all();
});

// Versioned API (v1)
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', function (Request $request) {
            return $request->user();
        });

        Route::get('/rides', [\App\Http\Controllers\RideController::class, 'apiIndex']);
        Route::get('/rides/{ride}', [\App\Http\Controllers\RideController::class, 'apiShow']);

        Route::get('/rooms', [\App\Http\Controllers\RoomController::class, 'apiIndex']);
        Route::get('/rooms/{room}', [\App\Http\Controllers\RoomController::class, 'apiShow']);

        Route::get('/bookings', [\App\Http\Controllers\AdminController::class, 'apiBookings']);
        Route::post('/bookings', [\App\Http\Controllers\AdminController::class, 'apiStoreBooking']);
    });

    Route::get('/rides/active', function () {
        return \App\Models\Ride::where('is_active', true)->get();
    });

    Route::get('/tickets', function () {
        return \App\Models\TicketType::all();
    });
});
