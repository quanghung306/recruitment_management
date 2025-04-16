<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'hr',
        ]);

        $token = $user->createToken('UserToken')->plainTextToken;
        logger()->info("User registered with token: " . $token);
        return [
            'token' => $token,
            'message' => 'Đăng ký thành công. Bạn có thể đăng nhập ngay.'
        ];
    }
    public function login(array $credentials)
    {
        try {
            $user = User::where('email', $credentials['email'])->first();
            if (!$user) {
                throw new Exception('Email không tồn tại.');
            }
            if (!$user->is_active) {
                session()->flash('account_locked', true);
                throw new Exception('Tài khoản đã bị khóa.');
            }
            log::info('User login : ' . $user->is_active);
            if (!Hash::check($credentials['password'], $user->password)) {
                throw new Exception('Mật khẩu không đúng.');
            }
            auth()->login($user);
            $user->createToken('UserToken')->plainTextToken;
            log::info("User logged in with token: " . $user->createToken('UserToken')->plainTextToken);
            return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
        } catch (Exception $e) {
            log::error('Login failed: ' . $e->getMessage());
            throw $e;
        }
    }
    public function logout(User $user): bool
    {
        try {
            $user->tokens()->delete();
            return true;
        } catch (Exception $e) {
            log::error('Logout failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
