<?php
namespace App\Http;

use App\Http\Middleware\Queue;
use \Closure;
use Exception;
use ReflectionFunction;

class Router {
    /**
     * URL completa do projeto(raiz)
     * @var string 
     */
    private $url = '';
    /**
     * Prefix de todas as rotas
     * @var string 
     */
    private $prefix = '';
    /**
     * índice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instanvia de Request
     * @var Request 
     */
    private $request = '';
    /**
     * Padrão de resposta
     * @var string
     */
    private $contentType = 'text/html';
    /**
     * responsavel por inicia a classe
     * @param string url
     */
    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }
    /*
     * define tipo de resposta
     * @param string $contentType
     */
    public function setContentType($contentType){
       $this->contentType = $contentType;
    }

    /**
     * define os prefixos das rotas
     */
    private function setPrefix(){
        $parseUrl = parse_url($this->url);
        // Define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }
    /**
     * REtornar mensagem de erro de acordo com o content type
     * @param string $message
     * @return mixed
     */
    private function getErrorMessage($message)
    {
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $message
                ];
                break;
            
            default:
                return $message;
                break;
        }
    }
     /**
     * adicionar uma routa na classe
     * @param string $method
     * @param string $route
     * @param array $params
     */
    private function addRoute($method, $route, $params)
    {
        foreach($params as $key=>$value){
            if($value instanceof Closure){
                $params['controller'] = $value;
                unset($params[$key]);
                continue;   
            }
        }
        $params['middlewares'] = $params['middlewares'] ?? [];
      
        // variaveis da roda
        $params['variables'] = [];

        // padrão de validação das variáveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)){
           
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];

        }
        // padrão de validação da URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';
        // adionar a rot adentro da classe
        $this->routes[$patternRoute][$method] = $params;
        // echo "<pre>";
        // print_r($this->routes);
        // echo "</pre>";
        // exit;

    }
    /**
     * Define a rota GET
     * @param string $route
     * @param array $params
     */
    public function get($route, $params = [])
    {
       
        return $this->addRoute('GET', $route, $params);
    }
    /**
     * Define a rota POST
     * @param string $route
     * @param array $params
     */
    public function post($route, $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }
    /**
     * Define a rota PUT
     * @param string $route
     * @param array $params
     */
    public function put($route, $params = [])
    {
        return $this->addRoute('PUT', $route, $params);
    }
     /**
     * Define a rota DELETE
     * @param string $route
     * @param array $params
     */
    public function delete($route, $params = [])
    {
        return $this->addRoute('DELETE', $route, $params);
    }
    /**
     * rrertorn a url desconsiderando o prefixo
     */
    public function getUri()
    {
        // uri da request
        $uri = $this->request->getUri();
        // Verifique se $uri é um array e converta para string, se necessário
        if (is_array($uri)) {
            $uri = implode('/', $uri); // Converte o array em uma string, caso necessário
        }
        // fatia a uri com prefix
        $xUri = strlen($this->prefix) ?  explode($this->prefix, $uri) : [$uri];

        return end($xUri);
        // return rtrim(end($xUri)).'/';

    }
    /**
     * Retorna os dados da routa atual
     * @return array 
     */
    private function getRoute()
    {
        $uri = $this->getUri();
        // method 
        $httpMethod = $this->request->getHttpMethod();
        // valida as rotas
        foreach($this->routes as $patternRoute => $methods){
            // verificando se uri bate com o padrão
            if(preg_match($patternRoute, $uri, $matches)){
                // verifica o método
                if(isset($methods[$httpMethod])){
                    //remove first position
                    unset($matches[0]);

                    // chaves 
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;
                    
                    return $methods[$httpMethod];
                }
                // método não permitido
                throw new Exception("Método não permitido", 405);
            }
        }
        // url não encontrada
        throw new Exception("URL não encontrada", 404);
    }
    /**
     * retorna a url atual
     * @return string
     */
    public function getCurrentUrl()
    {
        return $this->url.$this->getUri();
    }
     /**
     * retorna a route
     * @return string
     */
    public function redirect($router)
    {
        $url =  $this->url.$router;

        header('location: '.$url);
        exit;
    }

    /**
     * Executa a rota atual
     * @return Response
     */
    public function run()
    {
        try{
            $route = $this->getRoute();
            
            // se não existe controllador
            if(!isset($route['controller'])){
                throw new Exception("A URL não pode ser processada.", 500);
            }
            // argumentos da função
            $args = [];
            //ReflectionFunction
            $refection = new ReflectionFunction($route['controller']);
            foreach($refection->getParameters() as $parameter){
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }
            // echo "<pre>";
            // print_r($args);
            // echo "</pre>";
            // exit;

            // returna a execução das fila de middleware
            return (new Queue($route['middlewares'], $route['controller'], $args))->next($this->request);
            // retorna a execução da função 
        }catch(Exception $e){
            return new Response($e->getCode(), $this->getErrorMessage($e->getMessage()), $this->contentType);
        }
    }
    

}