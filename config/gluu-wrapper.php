<?php

return [
    'algorithm' => 'HS256',
    'authorization_endpoint' => 'https://dev.idp.kw.com/oxauth/seam/resource/restv1/oxauth/authorize',
    'token_endpoint' => 'https://dev.idp.kw.com/oxauth/seam/resource/restv1/oxauth/token',

    'client_id' => '@!8EF4.0267.10A3.7789!0001!58DE.5ADC!0008!FCFC.B130',
    'client_secret' => 'hanyacerita',
    
    'redirect_uri' => 'http://localhost:8000/callback',

    'response_type' => 'code',
    'scope' => 'openid',

    'grant_type' => 'authorization_code',
    'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
];
