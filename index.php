<?php

require "./vendor/autoload.php";

$params = explode('/', $_SERVER['REQUEST_URI']);
$params = explode('?', array_pop($params))[0];

if ($params == 'callback') {
  require './get_token.php';
} else {
  require './login.php';
}
