<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetPrivateDataAction
{

    public function handle(string $access_token, string $murugoAtname)
    {

        $privateDataResponse = Http::withHeaders([
            'APPKEY' => config('services.murugo.murugo_app_key')
        ])->withToken($access_token)->get(config('services.murugo.murugo_url') .'/api/user/get-private-data', [
            'atname' =>  $murugoAtname
        ]);

        return $privateDataResponse;
    }
}
