<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return redirect()->back()->withErrors([
                'email' => 'Email không tồn tại.',
            ]);
        }

        if (!$user->is_active) {
            return redirect()->back()->withErrors([
                'email' => 'Tài khoản đã bị khóa.',
            ]);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()->withErrors([
                'email' => 'Mật khẩu không đúng.',
            ]);
        }

        auth()->login($user);

        $token = $user->createToken('UserToken')->plainTextToken;
        logger()->info("User logged in with token: " . $token);

        return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
    }

    public function logout(User $user): bool
    {
        try {
            // Xóa tất cả tokens của user
            $user->tokens()->delete();
            // $user->currentAccessToken()->delete();
            return true;
        } catch (Exception $e) {
            logger()->error('Logout failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
