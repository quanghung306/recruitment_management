<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware(['auth', 'auth:sanctum'])->only(['logout']);
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());
            session()->flash('success', $result['message']);
            return redirect()->route('login');
        } catch (Exception $e) {
            return redirect()->route('register')->withErrors([
                'message' => 'Đăng ký thất bại: ' . $e->getMessage()
            ]);
        }
    }


    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {

        $this->authService->login($request->validated());
        Session()->flash('success', 'Đăng nhập thành công!');
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        // dd($request->user());
        try {
            // Đăng xuất session trước
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }

            // Sau đó xóa token API nếu có
            if ($request->user()) {
                $this->authService->logout($request->user());
            }

            // Hủy session hiện tại
            $request->session()->invalidate();

            // Tạo lại token CSRF
            $request->session()->regenerateToken();

            // Chuyển hướng về trang login với thông báo
            return redirect()->route('login')->with('success', 'Đăng xuất thành công.');
        } catch (Exception $e) {
            logger()->error('Logout error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đăng xuất không thành công: ' . $e->getMessage());
        }
    }
}
