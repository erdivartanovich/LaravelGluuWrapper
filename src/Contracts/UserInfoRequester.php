<?php

namespace Refactory\LaravelGluuWrapper\Contracts;

interface UserInfoRequester
{
    public function getUserInfo($access_token);
}
