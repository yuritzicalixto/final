<?php
// routes/client.php

use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\ReservationController;
use App\Http\Controllers\Client\CartController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('admin.dashboard');
})->middleware('can:access_dashboard')
->name('dashboard');



// =====================================================
// CITAS
// =====================================================

// AJAX endpoints — DEBEN ir ANTES del resource para que no colisionen con {appointment}
Route::get('appointments/stylists-by-service/{service}', [AppointmentController::class, 'stylistsByService'])
    ->middleware('can:client.appointments.create')
    ->name('appointments.stylists-by-service');

Route::get('appointments/available-slots', [AppointmentController::class, 'availableSlots'])
    ->middleware('can:client.appointments.create')
    ->name('appointments.available-slots');

// Resource CRUD (index, create, store, show, edit, update, destroy)
Route::resource('appointments', AppointmentController::class)
    ->middleware('can:client.appointments.create');


// CARRITO
Route::resource('carts', CartController::class)
    ->middleware('can:client.cart.use');

// APARTADOS — Acciones adicionales
Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
    ->middleware('can:client.reservations.view')
    ->name('reservations.cancel');


// APARTADOS
Route::resource('reservations', ReservationController::class)
    ->middleware('can:client.reservations.view');
