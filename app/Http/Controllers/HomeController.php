<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    public function index()
    {
      // Cookie::forget('access_token');
      // var_dump(Cookie::get('access_token'));
      return view('index');
    }
}
