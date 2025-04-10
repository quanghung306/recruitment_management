<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
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
                logger('User not found: ' . $credentials['email']);
                 throw new Exception('Email không tồn tại.');
            }

            if (!$user->is_active) {
                logger('Tài khoản đã bị khóa: ' . $credentials['email']);
                throw new Exception('Tài khoản đã bị khóa.');
            }

            if (!Hash::check($credentials['password'], $user->password)) {
                logger('Mật khẩu không đúng: ' . $credentials['password']);
                throw new Exception('Mật khẩu không đúng.');
            }

            auth()->login($user);

            $token = $user->createToken('UserToken')->plainTextToken;
            logger()->info("User logged in with token: " . $token);


            return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
        } catch (Exception $e) {
            logger()->error('Login failed: ' . $e->getMessage());
            throw $e;
        }
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
