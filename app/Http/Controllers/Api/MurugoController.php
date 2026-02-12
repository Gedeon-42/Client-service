<?php

namespace App\Http\Controllers\Api;


use App\Models\User;


use Illuminate\Http\Request;
use App\Services\MurugoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateMurugoRequest;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoController extends Controller
{
    //
    public function redirectToMurugo()
    {
        return MurugoAuth::redirect();
    }


    public function loginWithMurugo(ValidateMurugoRequest $request, MurugoService $murugoService)
    {

        $user = $murugoService->loginWithMurugo($request->validated());
        return response()->json([
            'message' => 'Logged in with Murugo',
            'user' => $user
        ]);
    }

    
    public function loginWithEmail(Request $request)
    {

        $tokens = [
            'access_token' => $request->access_token,
            'expires_in' => $request->expires_in,
        ];
        $murugoUser = MurugoAuth::userFromToken($tokens);
        dd($murugoUser);
    }
}
