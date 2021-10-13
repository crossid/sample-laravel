<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Oidc\OidcClient;

class OAuth2Controller extends Controller
{
    public function loginRedirect (OidcClient $oidcClient) {
      $state = bin2hex(random_bytes(32));
      $nonce = bin2hex(random_bytes(32));
  
      session(['state' => $state]);
      session(['nonce' => $nonce]);
  
      $url = $oidcClient->getAuthorizationUrl($state, $nonce, ['openid', 'profile', 'email']);
  
      return redirect ($url);
  }

    public function callback(Request $request, OidcClient $oidcClient)
    {
      $code = $request->code;

      if ($code=='') 
      {
          abort(400, 'Missing code.');
      }
  
      $state = session('state', '');
      if ($state == '' || $request->state != $state) 
      {
          abort(400, 'Bad state.');
      }
      session(['state' => '']);
  
      $tokens = $oidcClient->exchange_token($code);
  
      $nonce = session('nonce', '');
  
      $idToken = $oidcClient->decode($tokens->getValues()['id_token'], $nonce);
  
      $logoutUrl = $oidcClient->getLogoutUrl($tokens->getValues()['id_token'], 'https://localhost');
  
      return view('post_callback',  ['name' => $idToken->name, 'accessToken' => $tokens->getToken(), 'logoutUrl' => $logoutUrl]);
    }

    
}