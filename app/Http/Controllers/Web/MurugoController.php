<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Services\MurugoService;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoController extends Controller
{
    //
    public function redirectToMurugo()
    {
        // dd(config('services.murugo'));
        return MurugoAuth::redirect();
    }

    public function murugoCallback(MurugoService $murugoService)
    {
        $murugoService->callback();

        return redirect('/');
    }
}
