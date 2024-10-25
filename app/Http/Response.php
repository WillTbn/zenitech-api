<?php
namespace App\Http;

class Response {
    /**
     * Código do Status HTTP
     * @var integer
     */
    private $httpCode = 200;
     /**
     * Retornar os parâmetros da requisição
     * @var array
     */
    private $headers = [];
    /**
     * Tipo de conteúdo que esta sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteudo do response
     * @var mixed
     */
    private $content;
    /**
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     */
    public function __construct($httpCode, $content, $contentType= 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }
    /**
     * alterando content type do response
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }
    /**
     * alterando content type do response
     * @param string $key
     * @param string $value
     */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }
    /**
     * Enviar os headers para o navegador
     */
    private function sendHeaders(){
        //STATUS
        http_response_code($this->httpCode);
        //enviar headers
        foreach($this->headers as $key => $value){
            header($key.': '.$value);
        }
    }
    /**
     * enviar a resposta para o usuário
     */
    public function sendResponse()
    {
        // Enviando os headers
        $this->sendHeaders();
        // enviado o conteudo
        switch($this->contentType){
            case 'text/html':
                echo $this->content;
                exit;
            case 'application/json':
                echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                exit;
        } 
    }
}