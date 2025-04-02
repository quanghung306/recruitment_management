<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route Trang chủ (chỉ là ví dụ, có thể thay đổi tùy theo ứng dụng của bạn)
Route::get('/', function () {
    return view('app');
});

Route::middleware('guest')->group(function () {
   // Route đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register'); // Hiển thị form đăng ký
Route::post('/register', [AuthController::class, 'register']); // Xử lý đăng ký khi submit form
// Route đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Hiển thị form đăng nhập
Route::post('/login', [AuthController::class, 'login']); // Xử lý đăng nhập khi submit form
});




// dashboard


Route::middleware('auth')->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', function () {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('layouts.dashboard');

    })->name('dashboard');
    // Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
    // Route::get('/interviews', [InterviewController::class, 'index'])->name('interviews.index');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Xử lý đăng xuất
    //Thêm các route khác như quản lý ứng viên, phỏng vấn, v.v.
});
