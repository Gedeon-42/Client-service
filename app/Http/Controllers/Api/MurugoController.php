<?php

namespace App\Http\Controllers\Api;


use App\Models\User;


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
}
