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