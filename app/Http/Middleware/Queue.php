<?php
namespace App\Http\Middleware;

use App\Http\Request;
use Exception;

class Queue {

    /**
     * Mapeamento de middlewares
     * @var array
     */
    private static$map =[];
    /**
     *  Middlewares qque serão executados em todas as rotas
     * @var array
     */
    private static $default=[];
    /**
     * Fila de middlewares a serem executados
     * @var array
     */
    private $middlewares = [];

    /**
     * Função de execução do controlador
     * @var Closure|callable
     */
    private $controller;
    /**
     * Argumentos da função do controlador
     * @var array
     */
    private $controllerArgs = [];
    /**
     * Responsavel por construir a classe de fila de middlewares
     * @param array $middlewares
     * @param array $controller
     * @param Closure $controllerArgs
     */
    public function __construct( $middlewares, $controller, $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        // echo "<pre>";
        // print_r($this->middlewares );
        // echo "</pre>";
        // exit;
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }
    /**
     * definir mapeamento de middleware
     * @param array
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }
    /**
     * definir default de middleware
     * @param array
     */
    public static function setDefault($default)
    {
        self::$default = $default;
    }
    /**
     * Executar o próximo nível da fila de middlewares
     * @param Request $request
     * @return Response|Callback
     */
    public function next($request)
    {
        // verifica se a fila esta vazia
        if(empty($this->middlewares)) return call_user_func_array($this->controller, $this->controllerArgs);
        //middleware
        $middleware = array_shift($this->middlewares);
        // verificar o mapeamento
        if(!isset(self::$map[$middleware])){
            throw new Exception("Problemas ao processo o middleware da requisição", 500);
        }

        // Next
        $queue = $this;
        $next = function($request)use($queue) {
            return $queue->next($request);
        };

        // executa o middleware
        return (new self::$map[$middleware])->handle($request, $next);
    }
}