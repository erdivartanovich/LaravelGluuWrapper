<?php

namespace Refactory\LaravelGluuWrapper;

use Refactory\LaravelGluuWrapper\Contracts\TokenRequester as Contract;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Route;

class TokenRequester implements Contract
{
    public function generateURI()
    {
        $builder = new JWTBuilder(config('gluu-wrapper.algorithm'));

        $client_id = config('gluu-wrapper.client_id');        
        $client_secret = config('gluu-wrapper.client_secret');

        $claims = [
            "response_type" => config('gluu-wrapper.response_type'),
            "client_id" => $client_id,
            "redirect_uri" => url(config('gluu-wrapper.route_access_token_granted')),
            "scope" => config('gluu-wrapper.scope'),
        ];

        $builder->setSecret($client_secret);
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
        $client_id = config('gluu-wrapper.client_id');
        $client_secret = config('gluu-wrapper.client_secret');
        
        $client = new Client();
        $builder = new JWTBuilder(config('gluu-wrapper.algorithm'));
        $exp = 86400;
        $endpoint = config('gluu-wrapper.token_endpoint');

        //prepare openID payload
        $builder->addPayloads([
            "iss" => $client_id,
            "sub" => $client_id,
            "aud" => $endpoint,
            "jti" => md5(time()),
            "exp" => time() + $exp,
            "iat" => time()
            // claims => {} cannot use empty claims, if empty don't include it! 
        ]);

        //set client secret
        $builder->setSecret($client_secret);

        //generate JWT
        $token = $builder->generate();

        //Make a request to Gluu's token_endpoint using GuzzleHttp
        $res = $client->request('POST', config('gluu-wrapper.token_endpoint'), [
            'verify' => false,
            'form_params' => [
                "grant_type" => config('gluu-wrapper.grant_type'),
                "code" => $code,
                "redirect_uri" => url(config('gluu-wrapper.route_access_token_granted')),
                'client_assertion_type' => config('gluu-wrapper.client_assertion_type'),
                "client_assertion" => $token . ''
            ]
        ]);

        //decode json result, and get the content
        $result = json_decode($res->getBody()->getContents(), true);
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $expiration = Carbon::now()->addSeconds($result['expires_in'])->format('Y-m-d H:i:s');

        if (isset($result['access_token']) && config('gluu-wrapper.autosave')) {
            \DB::table(config('gluu-wrapper.table_name'))->insert(
                [
                    'access_token' => $result['access_token'],
                    'expiry_in' => $expiration,
                    'client_id' => $client_id,
                    'refresh_token' => $result['refresh_token'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        return $result;
    }

    public function routes()
    {
        Route::get(config('gluu-wrapper.route_endpoint'), function () {
            return redirect($this->generateURI());
        });

        Route::get(config('gluu-wrapper.route_access_token_granted'), function () {
            $code = $this->getCode(request());
            $accessToken = $this->getAccessToken($code);

            return response()->json($accessToken);
        });
    }
}
