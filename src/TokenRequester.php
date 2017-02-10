<?php

namespace Refactory\LaravelGluuWrapper;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TokenRequester
{
    public function generateURI()
    {
        $builder = new JWTBuilder(config('gluu-wrapper.algorithm'));

        $claims = [
            "response_type" => config('gluu-wrapper.response_type'),
            "client_id" => config('gluu-wrapper.client_id'),
            "redirect_uri" => config('gluu-wrapper.redirect_uri'),
            "scope" => config('gluu-wrapper.scope'),
        ];

        $builder->setSecret(config('gluu-wrapper.client_secret'));
        $builder->addPayloads($claims);

        $token = $builder->generate();

        $uri = config('gluu-wrapper.authorization_endpoint') . "?" . http_build_query($claims) . '&request=' . $token;

        return $uri;
    }
    
    public function getCode(Request $request)
    {
        return $request->get('code');
    }

    public function getAccessToken($code)
    {
        $client = new Client();
        $builder = new JWTBuilder(config('gluu-wrapper.algorithm'));
        $exp = 86400;
        $endpoint = config('gluu-wrapper.token_endpoint');

        //prepare openID payload
        $builder->addPayloads([
            "iss" => config('gluu-wrapper.client_id'),
            "sub" => config('gluu-wrapper.client_id'),
            "aud" => $endpoint,
            "jti" => md5(time()),
            "exp" => time() + $exp,
            "iat" => time()
            // claims => {} cannot use empty claims, if empty don't include it! 
        ]);

        //set client secret
        $builder->setSecret(config('gluu-wrapper.client_secret'));

        //generate JWT
        $token = $builder->generate();

        //Make a request to Gluu's token_endpoint using GuzzleHttp
        $res = $client->request('POST', config('gluu-wrapper.token_endpoint'), [
            'verify' => false,
            'form_params' => [
                "grant_type" => config('gluu-wrapper.grant_type'),
                "code" => $code,
                "redirect_uri" => config('gluu-wrapper.redirect_uri'),
                'client_assertion_type' => config('gluu-wrapper.client_assertion_type'),
                "client_assertion" => $token . ''
            ]
        ]);

        //decode json result, and get the content
        $result = json_decode($res->getBody()->getContents(), true);
        $expiration = Carbon::now()->addSeconds($result['expires_in'])->format('Y-m-d H:i:s');

        if (isset($result['access_token']) && config('gluu-wrapper.autosave')) {
            \DB::table(config('gluu-wrapper.table_name'))->insert(
                [
                    'access_token' => $result['access_token'],
                    'expiry_in' => $expiration,
                    'refresh_token' => $result['refresh_token'],
                ]
            );
        }

        return $result;
    }
}