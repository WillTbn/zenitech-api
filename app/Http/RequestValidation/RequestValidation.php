<?php

namespace App\Http\RequestValidation;

abstract class RequestValidation
{
    /**
     * Abstract method to force all request to implement its logic here
     * @param array
     */
    abstract public function validateAll($data);
    /**
     * Erros da request
     * @var array
     */
    protected $erros = [];
    protected function setErros($erro, $message = "Campo obrigatório")
    {
        $keys = array_keys($erro);
        $key = $keys[0];

        $this->erros[$key] = $message;
    }

    public function getErros()
    {
        return $this->erros;
    }

    
    public function verifyErrors()
    {
        if(!empty($this->getErros())){
            http_response_code(402);
            echo json_encode($this->getErros());
            exit;
        }
    }

}