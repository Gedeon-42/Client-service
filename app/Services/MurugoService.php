<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PSpell\Config;
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
        // dd($murugoUser);
        if (!$user) {
            //
            $credentials =  Http::post('https://test.murugocloud.com/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.murugo.client_id'),
                'client_secret' => config('services.murugo.client_secret'),
                'scope' => ''
            ]);
            //   dd($credentials['access_token']);
            $token = $credentials['access_token'];
            $userDataResponse = Http::withHeaders([
                'APPKEY' => config('services.murugo.murugo_app_key')
            ])->withToken($token)->get('https://test.murugocloud.com/api/user/get-private-data', [
                'atname' => $murugoUser->name
            ]);

            // dd($userDataResponse->json());
            $userData = $userDataResponse->json();
            $user = User::firstOrCreate(
                ['murugo_user_id' => $murugoUser->id],
                [
                    'email' => $userData['email'] ?? null,
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
