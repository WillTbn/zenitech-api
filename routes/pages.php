<?php

use App\Controller\Pages\About;
use App\Http\Response;
use App\Controller\Pages\Home;


$obRouter->get('/', [

    function(){
        return new Response(200, Home::getHome());
    }
]);
$obRouter->get('/about', [
    function(){
        return new Response(200, About::getAbout());
    }
]);

$obRouter->get('/pagina/{id}/{action}', [
    function($id, $action){
        return new Response(200,'Página 10'.$id.$action);
    }
]);