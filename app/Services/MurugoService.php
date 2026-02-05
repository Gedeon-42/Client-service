<?php

namespace App\Services;

use PSpell\Config;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Actions\GetPrivateDataAction;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoService
{

    public function __construct(protected GetPrivateDataAction $get_private_data_action) {}


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
            $credentials =  Http::post('https://test.murugocloud.com/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.murugo.client_id'),
                'client_secret' => config('services.murugo.client_secret'),
                'scope' => ''
            ]);
            //   dd($credentials['access_token']);
            $token = $credentials['access_token'];
            $userData = $this->get_private_data_action->handle($token, $murugoUser->name)->json();
            //  dd($userData);
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
