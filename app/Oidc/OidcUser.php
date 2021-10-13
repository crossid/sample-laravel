<?php
namespace App\Oidc;

use Illuminate\Contracts\Auth\Authenticatable;


class OidcUser implements Authenticatable 
{
  protected $sub;
  protected $scopes;
  protected $remember_token;
  protected $token;


  public function __construct($decodedToken, $rawToken) 
  {
    $this->sub = $decodedToken->sub;
    $this->scopes = $decodedToken->scp;
    $this->remember_token = NULL;
    $this->token = $rawToken;
  }
  /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName() {
      return "sub";
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
      return $this->sub;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
      return NULL;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
      return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value) {
      $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
      return "remember_token";
    }

    public function token() {
      return $this->token;
    }

    public function tokenCan(string $scope) {
      return $this->scopes != NULL && in_array($scope, $this->scopes);
    }
}