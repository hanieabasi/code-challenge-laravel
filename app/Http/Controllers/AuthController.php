<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $r)
    {
        $data = $r->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data + ['role' => $data['role'] ?? 'member']);
        $token = JWTAuth::fromUser($user);
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(LoginRequest $r)
    {
        if (!$token = auth('api')->attempt($r->validated())) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }
        return response()->json(['user' => auth('api')->user(), 'token' => $token]);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Logged out']);
    }

    public function refresh()
    {
        return response()->json([
            'token' => auth('api')->refresh()
        ]);
    }
}

