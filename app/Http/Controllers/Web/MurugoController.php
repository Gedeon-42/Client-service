<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;

class MurugoController extends Controller
{
    //
     public function redirectToMurugo()
    {
        dd(config('services.murugo'));
        return MurugoAuth::redirect();
    }

      public function murugoCallback()
    { 
        $murugoUser = MurugoAuth::user();

        return redirect('/');
    }
}
