<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('auth:sanctum')->only(['logout']);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $token = $this->authService->register($request->validated());
        return response()->json(['token' => $token], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {

        $token = $this->authService->login($request->validated());
        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());
            return response()->json(['message' => 'Đăng xuất thành công'], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Đăng xuất không thành công',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
