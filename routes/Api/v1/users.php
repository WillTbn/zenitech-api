<?php

use App\Controller\Api\Api;
use App\Controller\Api\Auth\Authentication;
use App\Controller\Api\User\UserController;
use App\Http\Response;

$obRouter->get('/api/v1/users', [
    'middlewares' =>[
        'api'
    ],
    function($request){
        return new Response(200, UserController::getAll($request), 'application/json');
    }
]);

$obRouter->get('/api/v1/user/{id}', [
    'middlewares' =>[
        'api'
    ],
    function($request, $id){
        return new Response(200, UserController::get($request, $id), 'application/json');
    }
]);
$obRouter->post('/api/v1/user', [
    'middlewares' =>[
        'api'
    ],
    function($request){
        return new Response(200, UserController::store($request), 'application/json');
    }
]);
$obRouter->post('/api/v1/user/{id}', [
    'middlewares' =>[
        'api'
    ],
    function($request, $id){
        return new Response(200, UserController::update($request, $id), 'application/json');
    }
]);
$obRouter->delete('/api/v1/user/{id}', [
    'middlewares' =>[
        'api'
    ],
    function($request, $id){
        return new Response(200, UserController::delete($request, $id), 'application/json');
    }
]);