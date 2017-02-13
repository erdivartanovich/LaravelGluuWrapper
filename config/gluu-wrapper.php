<?php

return [
    'algorithm' => 'HS256',
    'authorization_endpoint' => 'https://example.com/authorize',
    'token_endpoint' => 'https://example.com/token',

    // This client_id and client_secret is used as self-consumed keys.
    'client_id' => 'fake-client-id',
    'client_secret' => 'fake-client-secret',
    
    'response_type' => 'code',
    'scope' => 'openid',

    'grant_type' => 'authorization_code',
    'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',

    // Set this value to true to automatically store your token.
    // Make sure that you have migrate the table.
    'autosave' => false,
    'table_name' => 'access_tokens',

    // Routes
    'route_endpoint' => '/login',
    'route_access_token_granted' => '/access_granted'
];
