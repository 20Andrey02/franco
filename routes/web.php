<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\StandController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\VisitorController;

// ── Pública ──────────────────────────────────────────────────
Route::get('/', [HomeController::class , 'index'])->name('home');

// ── Encuestas (Pública) ─────────────────────────────────────
Route::get('/survey', [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

// ── Panel de Visitantes (Pública) ────────────────────────
Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
Route::get('/visitors/dashboard', [VisitorController::class, 'dashboard'])->name('visitors.dashboard');

// ── Auth ─────────────────────────────────────────────────────
Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
Route::post('/login', [AuthController::class , 'login'])->name('login.post');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

// ── Admin ─────────────────────────────────────────────────────
Route::middleware('role:admin')->group(function () {
    Route::resource('participants', ParticipantController::class);
    Route::resource('stands', StandController::class);
    Route::get('reports', [ReportController::class , 'index'])->name('reports.index');
    Route::get('reports/surveys', [SurveyController::class, 'reports'])->name('surveys.reports');    Route::get('reports/surveys/export/excel', [SurveyController::class, 'exportExcel'])->name('surveys.export.excel');
    Route::get('reports/surveys/export/pdf', [SurveyController::class, 'exportPdf'])->name('surveys.export.pdf');});

// ── Scanner + Admin (escaneo de QR) ──────────────────────────
Route::middleware('role:admin,scanner')->group(function () {
    // Registro de visita desde escaneo QR — acepta GET (desde QR físico) y POST (desde AJAX del stand)
    Route::match (['get', 'post'], 'visit', [ParticipantController::class , 'visit'])->name('visit');
    Route::get('scan', [ScanController::class , 'index'])->name('scan.index');
});
