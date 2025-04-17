<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterviewController;
use Illuminate\Support\Facades\Route;

// Trang chủ
Route::get('/', function () {
    return view('home');
});

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::prefix('apply')->name('candidates.')->group(function () {
        Route::get('/', [CandidateController::class, 'ShowApllyForm'])->name('form');
        Route::post('/', [CandidateController::class, 'storeApplication'])->name('submit');
    });

});

// Protected routes (auth only)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'ShowDashboardForm'])->name('dashboard');

    // Candidate routes
    Route::prefix('candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidateController::class, 'showCandidateForm'])->name('index');
        Route::get('/create', [CandidateController::class, 'createCandidateForm'])->name('create');
        Route::get('/export', [CandidateController::class, 'exportCsv'])->name('export');
        Route::post('/import', [CandidateController::class, 'importCsv'])->name('import');
        Route::post('/store', [CandidateController::class, 'store'])->name('store');
        Route::get('/{candidate}/edit', [CandidateController::class, 'updateCandidateForm'])->name('edit');
        Route::put('/{candidate}', [CandidateController::class, 'update'])->name('update');
        Route::delete('/{candidate}', [CandidateController::class, 'destroy'])->name('destroy');

});

// Form nộp CV ứng viên (không cần login)

    // Interview routes
    Route::prefix('interviews')->name('interviews.')->group(function () {
        Route::get('/', [InterviewController::class, 'showInterviewsForm'])->name('index');
        Route::get('/create', [InterviewController::class, 'create'])->name('create');
        Route::post('/', [InterviewController::class, 'store'])->name('store');
        Route::get('/{interview}/edit', [InterviewController::class, 'edit'])->name('edit');
        Route::put('/{interview}', [InterviewController::class, 'update'])->name('update');
        Route::delete('/{interview}', [InterviewController::class, 'destroy'])->name('destroy');
        Route::post('/send-email', [InterviewController::class, 'sendEmail'])->name('sendEmail');
    });
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::put('/admin/{user}', [AdminController::class, 'update'])->name('update');
        Route::post('/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('reset');
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle-active', [AdminController::class, 'toggleActive'])->name('admin.toggle-active');
    });
});
