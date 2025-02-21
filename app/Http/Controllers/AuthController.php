<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        
        $fields = $request->validated();

        $user = User::create($fields);

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'name' => $user->name,
            'token' => $token
        ], 200);
    }

    public function login(LoginRequest $request) {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => "Las credenciales no coinciden, correo o contraseña incorrectos",
            ], 200);

        }

        $token = $user->createToken($user->name)->plainTextToken;

        return response()->json([
            'name' => $user->name,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request) {

        $request->user()->tokens()->delete();
        return [
            'message' => 'Sesión cerrada'
        ];
    }
}
