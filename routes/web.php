<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use Illuminate\Support\Facades\Route;

// Route Trang chủ (chỉ là ví dụ, có thể thay đổi tùy theo ứng dụng của bạn)
Route::get('/', function () {
    return view('home');
});

Route::middleware('guest')->group(function () {
    // Route đăng ký
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); // Hiển thị form đăng ký
    Route::post('/register', [AuthController::class, 'register']); // Xử lý đăng ký khi submit form
    // Route đăng nhập
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Hiển thị form đăng nhập
    Route::post('/login', [AuthController::class, 'login']);
});

// dashboard
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('layouts.dashboard'))->name('dashboard');

    Route::prefix('candidates')->name('candidates.')->group(function () {
        // Route hiển thị danh sách ứng viên
        Route::get('/', [CandidateController::class, 'showCandidateForm'])->name('index');
        // Route tạo mới ứng viên
        Route::get('/create', [CandidateController::class, 'createCandidateForm'])->name('create');
        Route::post('/store', [CandidateController::class, 'store'])->name('store');
        // Route chỉnh sửa ứng viên
        Route::get('/{candidate}/edit', [CandidateController::class, 'updateCandidateForm'])->name('edit');
        Route::put('/{candidate}', [CandidateController::class, 'update'])->name('update');
        // Route xóa ứng viên
        Route::delete('/{candidate}', [CandidateController::class, 'destroy'])->name('destroy');
    });

    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
