<?php

use Refactory\LaravelGluuWrapper\JWTBuilder;

$authorization_endpoint = "https://dev.idp.kw.com/oxauth/seam/resource/restv1/oxauth/authorize";

$secret = "hanyacerita";

$claims = [
  "response_type" => "code",
  "client_id" => "@!8EF4.0267.10A3.7789!0001!58DE.5ADC!0008!FCFC.B130",
  "client_secret" => "hanyacerita",
  "redirect_uri" => "http://localhost:8000/callback",
  "scope" => "openid",
  "claims" => "{}"
];

$builder = new JWTBuilder('HS256');

$builder->setSecret($secret);
$builder->addClaims($claims);

$token = $builder->generate();

$uri = $authorization_endpoint . "?" . http_build_query($claims);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <a href="<?= $uri ?>">LOGIN</a>
</body>
</html>
