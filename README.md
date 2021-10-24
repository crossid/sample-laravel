# sample-laravel

A PHP Laravel[https://laravel.com/] project that demonstrates how to perform authentication and authorization via [crossid](crossid.io).

## Prerequisites

-   Have a Crossid tenant, or [sign up](https://crossid.io/signup) for free.
-   [Create a web application](https://developer.crossid.io/docs/guides/howto/create-web-app)

## Running locally

First install dependencies.

```bash
ISSUER_BASE_URL=<tenant>.crossid.io/oauth2/ composer install
```

Set auth client in `.env`

```bash
CLIENT_ID=<client_id>
CLIENT_SECRET=<client_secret>
REDIRECT_URI=<host>/callback
ISSUER_BASE_URL=<tenant>.crossid.io/oauth2/
```

```bash
php artisan serve
```

```bash
export TOKEN=<token>
curl -H 'Accept: application/json' -H "Authorization:Bearer $TOKEN" https://localhost/api/protected
```

## Deploying on Digital Ocean

Click this button to deploy the app to the DigitalOcean App Platform.

[![Deploy to DigitalOcean](https://www.deploytodo.com/do-btn-blue.svg)](https://cloud.digitalocean.com/apps/new?repo=https://github.com/crossid/sample-laravel/tree/main)

Note: when creating the web app in crossid, put a temporary URLs in _Redirect URI_ and _Logout URI_ until the app is deployed.

Fill the needed enviroment variables: `ISSUER_BASE_URL`, `CLIENT_ID` and `CLIENT_SECRET`.

or if you have `doctl` installed then run:

`doctl apps create --spec .do/app.yaml`

Then go to the DigitalOcean admin screen and update the enviroment variables as stated above.

Take note of the public url of your new app. (replace _{public_url}_ below with the public url)

Finally, go to CrossID admin screen, edit the oauth2 client, and add the correct callback url: `{public_url}/callback` and to post logout redirect uris as: `{public_url}`
