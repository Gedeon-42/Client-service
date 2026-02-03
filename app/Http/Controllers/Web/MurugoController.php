<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\MurugoService;
use Illuminate\Support\Facades\Auth;
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
