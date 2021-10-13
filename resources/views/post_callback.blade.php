<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <h1>Hello {{ $name }}!</h1>
    Your access token is: <br/>
    {{$accessToken}}<br/>
    <button><a href="{{$logoutUrl}}">Logout</a></button>
  </body>
</html>
