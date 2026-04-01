<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);
    
        $token = $user->createToken('api-token')->plainTextToken;
    
        return (new UserResource($user))
            ->additional([
                'token' => $token,
                'message' => 'Registro exitoso'
            ]);
    }

    public function login(LoginRequest $request)
    {
       

        $user = User::firstWhere('email', $request->email);

        abort_if(!$user || !Hash::check($request->password, $user->password), 401, 'Credenciales incorrectas');

        $token = $user->createToken('api-token')->plainTextToken;

        return (new UserResource($user))
            ->additional(['token' => $token, 'message' => 'Login exitoso']);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout exitoso']);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}