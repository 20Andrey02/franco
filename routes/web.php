<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('participants.index');
});

use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\ReportController;

Route::resource('participants', ParticipantController::class);
Route::resource('stands', StandController::class);

// Registro de visita desde escaneo QR — acepta GET (desde QR físico) y POST (desde AJAX del stand)
Route::match (['get', 'post'], 'visit', [ParticipantController::class , 'visit'])->name('visit');

// Reporte
Route::get('reports', [ReportController::class , 'index'])->name('reports.index');
