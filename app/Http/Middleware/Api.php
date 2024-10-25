<?php

namespace App\Http\Middleware;

use App\Http\Request;
use Exception;

class Api{
    /**
     * Responsavel por executar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, $next)
    {
        /// altera content type 
        $request->getRouter()->setContentType('application/json');
        // if(getenv('MAINTENANCE') == 'true'){
        //     throw new Exception("Página em manuntenção. Tente novamente mais tarde.", 200);
        // }
        
        return $next($request);
        // echo "<pre>";
        // print_r($request);
        // echo "</pre>";
        // exit;
    }
}