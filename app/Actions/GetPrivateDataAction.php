<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Http;

class GetPrivateDataAction
{

    public function handle(string $accessToken, string $murugoAtname)
    {

        $privateDataResponse = Http::withHeaders([
            'APPKEY' => config('services.murugo.murugo_app_key')
        ])->withToken($accessToken)->get(config('services.murugo.murugo_url') .'/api/user/get-private-data', [
            'atname' =>  $murugoAtname
        ]);

        if($privateDataResponse->failed()){
            throw new Exception('Failed to get private data from Murugo');
        }

        return $privateDataResponse;
    }
}
