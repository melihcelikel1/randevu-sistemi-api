<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/giris', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/giris', [AuthController::class, 'login'])->name('login.attempt');

    Route::get('/kayit-ol', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/kayit-ol', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/cikis', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/randevu-al', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::get('/randevu-musaitlik', [AppointmentController::class, 'availability'])->name('appointments.availability');
    Route::post('/randevu-al', [AppointmentController::class, 'store'])->name('appointments.store');

    Route::get('/randevularim', [AppointmentController::class, 'myAppointments'])->name('appointments.my');
    Route::post('/randevularim/{appointment}/iptal', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
});