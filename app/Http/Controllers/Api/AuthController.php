<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    //
     public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
    public function login(LoginRequest $request)
    {

        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            // return an error message
            return response()->json(['message' => 'Email Address not found']);
        }

        
        $passwordMatches = Hash::check($credentials['password'], $user->password);
        if (!$passwordMatches) {
            return response()->json([
                'message' => 'incorrect password'
            ]);
        }
        Auth::login($user);
        $token = $user->createToken('auth_token')->accessTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

     public function logout(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();
        return response()->json([
            'message' => 'logout successfully'
        ]);
    }
}
