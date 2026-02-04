<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoService
{
    public function loginWithMurugo(array $data)
    {
        $tokens = [
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'expires_in' => $data['expires_in'],
        ];

        $murugoUser = MurugoAuth::userFromToken($tokens);
        $user = $murugoUser->user;
        if (!$user) {
            $user = User::firstOrCreate(
                ['murugo_user_id' => $murugoUser->id],
                [
                    'email' => $murugoUser->email ?? null,
                    'name' => $murugoUser->name ?? 'User',
                ]
            );
        }
        $token = $user->createToken("auth_token")->accessToken;

        return compact('user', 'token');
    }

    public function callback()
    {
        $murugoUser = MurugoAuth::user();
        $user = $murugoUser->user;
        if (!$user) {
            $user = User::firstOrCreate(
                ['murugo_user_id' => $murugoUser->id],
                [
                    'email' => $murugoUser->email ?? null,
                    'name' => $murugoUser->name ?? 'User',
                ]
            );
        }
        Auth::login($user);
    }
}
