<?php

use App\Http\Controllers\Api\AccommodationController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\HotelRoomController;
use App\Http\Controllers\Api\RoomTypeController;
use Illuminate\Support\Facades\Route;

// Rutas para hoteles
Route::apiResource('hotels', HotelController::class);
Route::get('hotels/{hotel}/rooms', [HotelController::class, 'getRoomsDetail']);

// Rutas para tipos de habitación
Route::get('room-types', [RoomTypeController::class, 'index']);

// Rutas para acomodaciones
Route::get('accommodations', [AccommodationController::class, 'index']);
Route::get('room-types/{roomType}/accommodations', [AccommodationController::class, 'getByRoomType']);

// Rutas para habitaciones de hotel
Route::apiResource('hotel-rooms', HotelRoomController::class);

// Cambio para forzar redeploy