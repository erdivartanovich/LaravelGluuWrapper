<?php

return [
    'algorithm' => 'HS256',
    'authorization_endpoint' => 'https://example.com/authorize',
    'token_endpoint' => 'https://example.com/token',

    'client_id' => 'fake-client-id',
    'client_secret' => 'fake-client-secret',
    
    'redirect_uri' => 'http://localhost:8000/callback',

    'response_type' => 'code',
    'scope' => 'openid',

    'grant_type' => 'authorization_code',
    'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
];
