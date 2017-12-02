<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class OauthController extends Controller
{
    public function callback(Request $request)
    {
        $http = new Client;
        $response = $http->post(env('OAUTH_URL_TOKEN'), [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => env('OAUTH_CLIENT_ID'),
                'client_secret' => env('OAUTH_CLIENT_SECRET'),
                'redirect_uri' => 'http://192.168.1.60:9000/oauth/callback',
                'code' => $request->code,
            ],
        ]);
        $responseData = json_decode($response->getBody()->getContents());
        $userId = uniqid();
        Redis::hmset('oauth:' . $userId,
          'access_token', $responseData->access_token,
          'refresh_token', $responseData->refresh_token,
          'expiry', Carbon::now()->addSeconds($responseData->expires_in),
          'token_type', $responseData->token_type
        );
        return redirect('/')->withCookies([
          cookie('session_key', $userId),
          cookie('access_token', $responseData->access_token)
        ]);
    }

    public function getSessionToken(Request $request)
    {
      $data = Redis::hmget('oauth:' . decrypt(Cookie::get('session_key')), 'access_token');
      return json_encode($data);
    }

    public function checkAuthStatus(Request $request)
    {
      if (Cookie::has('session_key')) {
        $sessionId = decrypt(Cookie::get('session_key'));
        if (Redis::exists('oauth:'.$sessionId)) {
          $expiry = Carbon::parse(Redis::hget('oauth:'.$sessionId, 'expiry'));
          if ($expiry->gt(Carbon::now())) {
            return json_encode(['status' => true]);
          } else {
            if ($this->getNewToken($sessionId)) {
              return json_encode(['status' => true]);
            } else {
              return json_encode(['status' => false]);
            }
          }
        } else {
          return json_encode(['status' => false]);
        }
      } else {
        return json_encode(['status' => false]);
      }
    }

    private function getNewToken(string $sessionId)
    {
      $refreshToken = Redis::hget('oauth:'.$sessionId, 'refresh_token');
      $http = new Client;
      try {
        $response = $http->post(env('OAUTH_URL_TOKEN'), [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => env('OAUTH_CLIENT_ID'),
                'client_secret' => env('OAUTH_CLIENT_SECRET'),
                'scope' => '',
            ],
        ]);
        $responseData = json_decode($response->getBody()->getContents());
        if (isset($responseData->access_token)) {
          Redis::hmset('oauth:' . $sessionId,
            'access_token', $responseData->access_token,
            'refresh_token', $responseData->refresh_token,
            'expiry', Carbon::now()->addSeconds($responseData->expires_in),
            'token_type', $responseData->token_type
          );
          Redis::set('updatedtime', Carbon::now());
          return true;
        } else {
          return false;
        }
      } catch (ClientException $e) {
        return false;
      }

    }

    public function logout(Request $request)
    {
      $http = new Client;
      if (Cookie::has('session_key'))
      {
        $sessionId = decrypt(Cookie::get('session_key'));
        $accessToken = Redis::hget('oauth:'.$sessionId, 'access_token');
        try {
          $response = $http->post(env('OAUTH_URL'). '/api/user/logout',[
            'headers' => [
              'Authorization' => 'Bearer '. $accessToken,
              'Accept' => 'application/json'
            ]
          ]);
          Redis::del('oauth:'.$sessionId);
          return json_encode(['status' => true]);
        } catch (ClientException $e) {
          Redis::del('oauth:'.$sessionId);
          return json_encode(['status' => true]);
        }
      }
      else
      {
        return json_encode(['status' => true]);
      }

    }

    public function testRepository(Request $request)
    {
      if (Cookie::has('session_key')) {
        $sessionId = decrypt(Cookie::get('session_key'));
        $accessToken = Redis::hmget('oauth:'. $sessionId, 'access_token');
        $http = new Client;
        try {
          $response = $http->get('http://192.168.1.60:9900/api/test', [
            'headers' => [
                'Authorization' => 'Bearer '. $accessToken[0],
                'Accept' => 'application/json'
            ]
          ]);
          $responseData = json_decode($response->getBody()->getContents());
          Redis::set('resource:debug', 'From Existing Token');
          return json_encode([
            'status' => true,
            'result' => $responseData
          ]);
        } catch (ClientException $e) {
          // return json_encode($e->getResponse()->getBody()->getContents());
          // abort(401, 'Authentication Fail');
          $this->getNewToken($sessionId);
          Redis::set('resource:debug', 'From New Token');
          return $this->testRepository($request);
        }

      } else {
        return json_encode([
          'status' => false,
          'message' => "User not logged in"
        ]);
      }
    }
}
