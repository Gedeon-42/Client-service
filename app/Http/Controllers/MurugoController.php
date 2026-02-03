<?php

namespace App\Http\Controllers;


use App\Models\User;


use App\Services\MurugoService;
use App\Http\Requests\ValidateMurugoRequest;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoController extends Controller
{
    //
    public function redirectToMurugo()
    {
        return MurugoAuth::redirect();
    }

    public function murugoCallback()
    {
        $murugoUser = MurugoAuth::user();
    }

    public function loginWithMurugo(ValidateMurugoRequest $request, MurugoService $murugoService)
    {

        $murugoService->loginWithMurugo($request->validated());
        return response()->json(['message' => 'Logged in with Murugo']);
      
    }
}
