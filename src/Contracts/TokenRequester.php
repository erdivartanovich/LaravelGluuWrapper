<?php

namespace Refactory\LaravelGluuWrapper\Contracts;

use Illuminate\Http\Request;

interface TokenRequester
{
    public function generateURI();
    public function getCode(Request $request);
    public function getAccessToken($code);
}
