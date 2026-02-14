<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        $credenciais = $request->all('email', 'password');
        $token = auth('api')->setTTL(120)->attempt($credenciais);
        if ($token) {
            return response()->json(['token' => $token], 200);
        }
        return response()->json(['error' => 'Credenciais inválidas'], 403);
    }

    public function me() {
        $data = auth()->user();

        return response()->json($data, 200);
    }

    public function refresh() {
        $token = Auth::guard('api')->setTTL(120)->refresh();
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 120
        ], 200);
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
