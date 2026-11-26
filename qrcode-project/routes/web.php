<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('qrcode')->name('qrcode.')->group(function () {
    Route::get('/', [QrCodeController::class, 'index'])->name('index');
    Route::post('/submit', [QrCodeController::class, 'submit'])->name('submit');
    Route::delete('/{qrCode}', [QrCodeController::class, 'destroy'])->name('destroy');
});