<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestDataController extends Controller
{
    public function getRedis()
    {
      return Redis::get('test');
    }

    public function setRedis(Request $request)
    {
        $inputs = $request->all();
        foreach($inputs as $key => $input) {
          Redis::set($key, $input);
        }
        return "OK";
    }
}
