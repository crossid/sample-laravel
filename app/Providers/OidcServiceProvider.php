<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Oidc\OidcClient;
use App\Oidc\OidcUserProvider;
use App\Oidc\OidcGuard;

class OidcServiceProvider extends ServiceProvider
{
  /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton(OidcClient::class, function ($app) {
          $clientId = env('CLIENT_ID');
          $clientSecret = env('CLIENT_SECRET');
          $redirectUri = env('REDIRECT_URI');
          $issuerBaseUrl = env('ISSUER_BASE_URL');

          return new OidcClient($clientId, $clientSecret, $redirectUri, $issuerBaseUrl);
      });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(OidcClient $oidcClient)
    {
      Auth::extend('oidc', function ($app, $name, array $config) use ($oidcClient) {
        return new OidcGuard($app->make('request'), $oidcClient);
      });
    }
  
}

?>