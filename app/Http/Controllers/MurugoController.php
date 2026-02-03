<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
