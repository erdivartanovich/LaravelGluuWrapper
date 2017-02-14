<?php

namespace Refactory\LaravelGluuWrapper\Contracts;

interface Manager
{
    public function getTokenRequester();
    public function getUserRequester();
}
