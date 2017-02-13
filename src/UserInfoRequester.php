<?php

namespace Refactory\LaravelGluuWrapper;

use Refactory\LaravelGluuWrapper\Contracts\UserInfoRequester as Contract;
use Lcobucci\JWT\Parser;
use GuzzleHttp\Client;

class UserInfoRequester implements Contract
{
    public function getUserInfo($access_token)
    {
        $parser = new Parser();

        $client = new Client();
        $res = $client->request('GET', config('gluu-wrapper.userinfo_endpoint'), [
            'verify' => false,
            'headers' => [
                'Authorization' => "Bearer {$access_token}"
            ]
        ]);

        $result = $res->getBody()->getContents();

        $token = $parser->parse($result);

        return $token->getClaims();
    }
}
