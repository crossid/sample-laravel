<?php
// app/Services/Auth/JsonGuard.php
namespace App\Oidc;
 
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;
 
class OidcGuard implements Guard
{
  protected $request;
  protected $oidcClient;
  protected $token;
  protected $error;
 
  /**
   * Create a new authentication guard.
   *
   * @param  \Illuminate\Contracts\Auth\UserProvider  $provider
   * @param  \Illuminate\Http\Request  $request
   * @return void
   */
  public function __construct(Request $request, OidcClient $oidcClient)
  {
    $this->request = $request;
    $this->oidcClient = $oidcClient;

    $this->token = NULL;


    $tok = $request->bearerToken();
    if ($tok) {
      try{
        $token = $oidcClient->decode($tok);
        $this->token = new OidcUser($token, $tok);
      }
      catch (\Exception $e) {
        $request->request->add(['authError' => $e->getMessage()]);
      }  
    }
    else 
    {
      $request->request->add(['authError' => 'Bad or missing token.']);
    }
  }
 
  /**
   * Determine if the current user is authenticated.
   *
   * @return bool
   */
  public function check()
  {
    return ! is_null($this->user());
  }
 
  /**
   * Determine if the current user is a guest.
   *
   * @return bool
   */
  public function guest()
  {
    return ! $this->check();
  }
 
  /**
   * Get the currently authenticated user.
   *
   * @return \Illuminate\Contracts\Auth\Authenticatable|null
   */
  public function user()
  {
    if (! is_null($this->token)) {
      return $this->token;
    }
  }
     
  /**
   * Get the ID for the currently authenticated user.
   *
   * @return string|null
  */
  public function id()
  {
    if ($user = $this->user()) {
      return $this->user()->getAuthIdentifier();
    }
  }
 
  /**
   * Validate a user's credentials.
   *
   * @return bool
   */
  public function validate(Array $credentials=[])
  {
    return $this->check();
  }
 
  /**
   * Set the current user.
   *
   * @param  Array $user User info
   * @return void
   */
  public function setUser(Authenticatable $user)
  {
    $this->user = $user;
    return $this;
  }
}