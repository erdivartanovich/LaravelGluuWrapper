<?php

require "./vendor/autoload.php";

use Refactory\GluuWrapper\JWTBuilder;

$secret = "TEST";

$builder = new JWTBuilder('HS256');
$builder->setSecret($secret);

$builder->addClaims([
  "response_type" => "code",
  "client_id" => "@!8EF4.0267.10A3.7789!0001!58DE.5ADC!0008!FCFC.B130",
  "client_secret" => "hanyacerita",
  "redirect_uri" => "https://crm.kw.com/auth/callback",
  "scope" => "openid",
  "claims" => []
]);

$token = $builder->generate();

var_dump($token->verify($builder->getSigner(), $secret));
