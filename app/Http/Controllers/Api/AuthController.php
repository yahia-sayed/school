<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        if (Auth::attempt($request->only('username', 'password'))) {
            $token = $request->user()->createToken($request->user()->role.'_'.$request->user()->username);
            return response()->json([
                'Token' => $token->plainTextToken
            ]);
        }
        return response()->json([
            'Message' => 'credentials does not match'
        ]);
    }
}
