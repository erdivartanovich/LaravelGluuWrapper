<?php

require "./vendor/autoload.php";

$params = explode('/', $_SERVER['REQUEST_URI']);
$params = explode('?', array_pop($params))[0];

$authorization_endpoint = "https://dev.idp.kw.com/oxauth/seam/resource/restv1/oxauth/authorize";
$token_endpoint = "https://dev.idp.kw.com/oxauth/seam/resource/restv1/oxauth/token";

$secret = "hanyacerita";
$username = "admin";
$password = "3bxuSI2eRAbT";

$claims = [
  "response_type" => "code",
  "client_id" => "@!8EF4.0267.10A3.7789!0001!58DE.5ADC!0008!FCFC.B130",
  "client_secret" => "hanyacerita",
  "redirect_uri" => "http://localhost:8000/callback",
  "scope" => "openid"
];

if ($params == 'access_token') {
  
} elseif ($params == 'callback') {
  require './get_token.php';
} else {
  require './login.php';
}
