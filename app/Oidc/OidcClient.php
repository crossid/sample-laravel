<?php
namespace App\Oidc;

use Illuminate\Support\Facades\Http;
use \League\OAuth2\Client\Provider\GenericProvider;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

class OidcClient
{
  protected $issuerBaseUrl;
  protected $provider;
  protected $jwksUri;
  protected $endSessionEndpoint;

  public function __construct($clientId, $clientSecret, $redirectUri, $issuerBaseUrl)
  {
    $this->issuerBaseUrl = $issuerBaseUrl;

    $response = Http::get($issuerBaseUrl . '.well-known/openid-configuration' );

    $this->jwksUri = $response['jwks_uri'];
    $this->endSessionEndpoint = $response['end_session_endpoint'];

    $this->provider = new GenericProvider([
        'clientId'                => $clientId,
        'clientSecret'            => $clientSecret,
        'redirectUri'             => $redirectUri,
        'urlAuthorize'            => $response['authorization_endpoint'],
        'urlAccessToken'          => $response['token_endpoint'],
        'urlResourceOwnerDetails' => $response['userinfo_endpoint'],
        'scopeSeparator'          => ' ',
    ]);

  }

  public function getAuthorizationUrl($state, $nonce = '', $scope = ['openid', 'profile'], $audience = '')
  {
      $options = [
        'state'          => $state,
        'scope'          => $scope,
      ];

      if ($nonce != '')
      {
        $options['nonce'] = $nonce;
      }

      if ($audience != '')
      {
        $options['audience'] = $audience;
      }

      return $this->provider->getAuthorizationUrl($options);
  }

  public function getLogoutUrl($idToken, $postLogoutRedirect) 
  {
    return $this->endSessionEndpoint . '?id_token_hint=' .  $idToken . '&post_logout_redirect_uri=' . $postLogoutRedirect;
  }

  public function exchange_token($code) 
  {
    return  $this->provider->getAccessToken('authorization_code', [
        'code' => $code
      ]);
  }

  public function refresh_token($refreshToken) 
  {
    return  $this->provider->getAccessToken('refresh_token', [
        'refresh_token' => $refresh_token
      ]);
  }

  public function decode($token, $nonce = '', $audience = '')
  {
      $jwks = Http::get($this->jwksUri)->json();
      $data = JWT::decode($token, JWK::parseKeySet($jwks), ['RS256']);

      if ($data->iss != $this->issuerBaseUrl)
      {
        throw new \Exception('Mismatched issuer');
      }
        
      if ($data->exp < time()) 
      {
        throw new \Exception('Token expired');
      }

      if (isset($data->nbf) && $data->nbf > time()) 
      {
        throw new \Exception('Token not valid yet');
      }

      if ($nonce != '' && $data->nonce != $nonce)
      {
        throw new \Exception('Mismatched nonce');
      }

      if ($audience != '' && $data->aud != $audience) 
      {
        throw new \Exception('Mismatched audience');
      }
      
      return $data;
  }
}


?>