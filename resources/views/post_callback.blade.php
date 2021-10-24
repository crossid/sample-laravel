<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <h1>Hello {{ $name }}!</h1>
    Your access token is: <br/>
    {{$accessToken}}<br/><br/><br/>
    Try to consume a protected resource by:<br /><br />
    <code style="background-color: gainsboro">
    export TOKEN=token...
    <br />
    curl -H "Authorization: Bearer $TOKEN" {public_url}/api/protected
    </code>
    <br/><br/>
    <button><a href="{{$logoutUrl}}">Logout</a></button>
  </body>
</html>
