<?php

$client = new GuzzleHttp\Client();
use Refactory\LaravelGluuWrapper\JWTBuilder;

$builder = new JWTBuilder('HS256');

$exp = 86400;

$clientAssertion = [
  "iss" => $claims['client_id'],
  "sub" => $claims['client_id'],
  "aud" => $token_endpoint,
  "jti" => md5(time()),
  "exp" => time() + $exp,
  "iat" => time()
];

$builder->addClaims($clientAssertion);
$builder->setSecret($secret);
$token = $builder->generate();

$res = $client->request('POST', $token_endpoint, [
    'verify' => false,
    'form_params' => [
        "grant_type" => "authorization_code",
        "code" => $_GET['code'],
        "redirect_uri" => "localhost:8000/access_token",
        "username" => $username,
        "password" => $password,
        'client_assertion_type' => "urn:ietf:params:oauth:client-assertion-type:jwt-bearer",
        "client_assertion" => $token . ''
    ]
]);

$results = json_decode($res->getBody()->getContents());

echo "<pre>"; print_r($results); echo "</pre>";
