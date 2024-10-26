<?php

namespace App\Http;

class Request {

     /**
     * Instancia do Router
     * @var Router
     */
    private $router;
    /**
     * Metodo HTTP reqisição
     * @var string
     */
    private $httpMethod;
    /**
     * URI da página
     * @var string
     */
    private $uri;
    /**
     * Parâmetros da URL ($_GET)
     * @var array
     */
    private $queryParams=[];

    /**
     * Variáveis recebidas na POST da página ($_POST)
     * @var array
     */
    private $postVars = [];
    /**
     * Cabeçalho da requisição 
     * @var array
     */
    private $headers = [];
    /**
     * Arquivos recebidos na requisição ($_FILES)
     * @var array
     */
    private $files = [];
    public function __construct($router)
    {
       
        $this->router = $router;
        $this->queryParams = $_GET ??[];
        $this->headers = getallheaders();
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->setUri();
        $this->setPostVars();
        $this->setFiles();
    }
    /**
     * Definir os arquivos da requisição
     */
    private function setFiles()
    {
        $this->files = $_FILES ?? [];
    }
    /**
     * Definir as variáveis do POST
     */
    private function setPostVars()
    {
        if($this->httpMethod == 'GET') return false;

        $this->postVars = $_POST ?? [];

        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST))? json_decode($inputRaw,true) : $this->postVars;

    }

    /**
     * metodo responsavel por definir a URI
     */
    private function setUri()
    {
        //URI completa (com GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        // remove GETS da URI
        $xURI = explode('?', $this->uri);
       
        $this->uri = $xURI[0];
    }
    /**
     * retorna a instancia de rotuer
     */
    public function getRouter(){
        return $this->router;
    }
    /**
     * Retornar o HTTP da requisição
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }
    /**
     * Retornar a URI da requisição
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
    /**
     * Retornar a Headers da requisição
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    /**
     * Retornar os parâmetros da requisição GET
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }
     /**
     * Retornar os parâmetros da requisição POST
     * @return array
     */
    public function getPostVars()
    {
      
        return $this->postVars;
    }
    /**
     * Retorna os arquivos enviados na requisição
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

}