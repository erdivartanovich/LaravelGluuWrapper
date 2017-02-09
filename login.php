<?php

use Refactory\LaravelGluuWrapper\JWTBuilder;

$builder = new JWTBuilder('HS256');

$builder->setSecret($secret);
$builder->addClaims($claims);

$token = $builder->generate();

$uri = $authorization_endpoint . "?" . http_build_query($claims) . '&request=' . $token;

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
