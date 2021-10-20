# README

First run to install dependancies

```bash
php composer.phar install
```

Set auth client in .env


CLIENT_ID=<client_id>
CLIENT_SECRET=<client_secret>
REDIRECT_URI=<host/callback>
ISSUER_BASE_URL=<your tenant url>

```bash
php artisan serve
```

```bash
curl -k https://localhost/api/protected -H 'Accept: application/json' -H "Authorization:Bearer $token"
```



## Deploying on Digital Ocean

Click this button to deploy the app to the DigitalOcean App Platform.

[![Deploy to DigitalOcean](https://www.deploytodo.com/do-btn-blue.svg)](https://cloud.digitalocean.com/apps/new?repo=https://github.com/crossid/sample-laravel/tree/main)

Fill the needed enviroment variables: CLIENT_ID, CLIENT_SECRET, ISSUER_BASE_URL

or if you have `doctl` installed then run:

`doctl apps create --spec .do/app.yaml`

Then go to the DigitalOcean admin screen and update the enviroment variables: Fill the needed enviroment variables: CLIENT_ID, CLIENT_SECRET, ISSUER_BASE_URL

Take note of the public url of your new app.

Finally, go to CrossID admin screen, edit the oauth2 client, and add the correct callback url: {public_url}/callback and to post logout redirect uris: {public_url}