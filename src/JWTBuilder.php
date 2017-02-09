<?php

namespace Refactory\LaravelGluuWrapper;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTBuilder {

  protected $builder;
  protected $algo;
  protected $secret;
  protected $payloads = [];

  public function __construct($algo = 'none')
  {
      $this->builder = new Builder;
      $this->setAlgorithm($algo);
  }

  public function generate()
  {
    foreach ($this->payloads as $key => $val) {
      $this->builder->with($key, $val);
    }

    $this->sign();

    return $this->builder->getToken();
  }

  public function setSecret($secret)
  {
    $this->secret = $secret;
  }

  public function setAlgorithm($algo)
  {
    $this->algo = $algo;
  }

  public function hasSecret()
  {
    return $this->secret != null && $this->secret != '';
  }

  protected function sign()
  {
    if ($this->algo != 'none') {
      $signer = $this->getSigner();
      if ($signer && $this->hasSecret()) {
        $this->builder->sign($signer, $this->secret);
      }
    }
  }

  public function getSigner()
  {
    $signer = null;

    switch (strtoupper($this->algo)) {
      case "HS256":
        $signer = new Sha256();
        break;
    }

    return $signer;
  }

  public function addClaim($key, $value)
  {
      $this->payloads[$key] = $value;
  }

  public function addClaims($values)
  {
      $this->payloads = array_merge($this->payloads, $values);
  }
}
