<?php
// routes/stylist.php

use App\Http\Controllers\Stylist\AppointmentController;
use App\Http\Controllers\Stylist\ClientController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('admin.dashboard');
})->middleware('can:access_dashboard')
->name('dashboard');


// =====================================================
// MIS CITAS (vista del estilista)
// =====================================================
Route::get('/mis-citas', [AppointmentController::class, 'index'])
    ->middleware('can:stylist.appointments.view')
    ->name('appointments.index');


// ACCIONES SOBRE CITAS
Route::post('/mis-citas/{appointment}/confirm', [AppointmentController::class, 'confirm'])
    ->middleware('can:stylist.appointments.view')
    ->name('appointments.confirm');

Route::post('/mis-citas/{appointment}/complete', [AppointmentController::class, 'complete'])
    ->middleware('can:stylist.appointments.view')
    ->name('appointments.complete');

Route::post('/mis-citas/{appointment}/no-show', [AppointmentController::class, 'noShow'])
    ->middleware('can:stylist.appointments.view')
    ->name('appointments.noShow');
// =====================================================
// MIS CLIENTES
// =====================================================
// Route::get('/mis-clientes', [ClientController::class, 'index'])
//     ->middleware('can:stylist.clients.view')
//     ->name('clients.index');
