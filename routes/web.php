<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'HomeController@index');

Route::get('/login', function(){
  return redirect('/redirect');
});

Route::get('/redirect', function () {
    $query = http_build_query([
        'client_id' => '3',
        'redirect_uri' => 'http://192.168.1.60:9000/oauth/callback',
        'response_type' => 'code',
        'scope' => '',

      ]);
    return redirect('http://192.168.1.60:81/oauth/authorize?'.$query);
});

Route::get('/oauth/callback', 'OauthController@callback');

Route::get('/redis/get', 'TestDataController@getRedis');
Route::get('/redis/set', 'TestDataController@setRedis');
