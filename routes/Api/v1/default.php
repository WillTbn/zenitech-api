<?php

use App\Controller\Api\Api;
use App\Http\Response;

$obRouter->get('/api/v1', [
    'middlewares' =>[
        'api'
    ],
    function($request){
        return new Response(200, Api::getDetails($request), 'application/json');
    }
]);
