<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): string
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'hr',
        ]);

        return $user->createToken('UserToken')->plainTextToken;
    }

    public function login(array $credentials): string
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Thông tin đăng nhập không hợp lệ.'],
            ]);
        }

        return $user->createToken('UserToken')->plainTextToken;
    }

    public function logout(User $user): bool
{
    try {
     // $user->currentAccessToken()->delete();
        $user->tokens()->delete();
        return true;
    } catch (Exception $e) {
        logger()->error('Logout failed: ' . $e->getMessage());
        throw $e;
    }
}
}
