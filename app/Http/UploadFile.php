<?php

namespace App\Http;

class UploadFile
{
    /**
     * Nome original do arquivo enviado
     * @var string
     */
    private $name;

    /**
     * Caminho completo do arquivo
     * @var string
     */
    private $fullPath;

    /**
     * Tipo MIME do arquivo
     * @var string
     */
    private $type;

    /**
     * Caminho temporário onde o arquivo está armazenado
     * @var string
     */
    private $tmpName;

    /**
     * Código de erro do upload
     * @var int
     */
    private $error;

    /**
     * Tamanho do arquivo em bytes
     * @var int
     */
    private $size;
    /**
     * 
     */
    private $extension;
    /**
     * data persistido no banco de dados
     * @var string
     */
    private $nameDatabase;
    /**
     * Construtor da classe UploadedFile
     *
     * @param array $fileInfo Informações do arquivo do $_FILES
     */
    public function __construct(array $fileInfo)
    {
        $this->name = random_int(1, 1000).date('Ymdhs');
        $this->fullPath = $fileInfo['full_path'];
        $this->type = $fileInfo['type'];
        $this->tmpName = $fileInfo['tmp_name'];
        $this->error = $fileInfo['error'];
        $this->size = $fileInfo['size'];
        $info = pathinfo($fileInfo['name']);
       
        // $this->name = $info['filaname'];
        $this->extension = $info['extension'];
        // echo "<pre>";
        // print_r($info);
        // echo "</pre>";
        // exit;
    }
    /**
     * Retorna o nome original do arquivo
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retorna o caminho completo do arquivo
     * @return string
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * Retorna o tipo MIME do arquivo
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Retorna o caminho temporário do arquivo
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }
    

    /**
     * Retorna o código de erro do upload
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retorna o tamanho do arquivo em bytes
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Verifica se o upload foi bem-sucedido
     * @return bool
     */
    public function isValid()
    {
        return $this->error === UPLOAD_ERR_OK;
    }
    public function getBaseName()
    {
        $extension = strlen($this->extension) ? '.'.$this->extension : '';

        return $this->name.$extension;
    }
    public function getExtension()
    {
        return $this->extension;
    }
    public function setNameDatabade($full)
    {
        $xName =  explode("public",$full, 2);
        $this->nameDatabase =  $xName[1];
    }
    public function getNameDatabase()
    {
        return $this->nameDatabase;
    }
    /** 
     * verifica tamanho maximo
     * @param int $maxSizeKB
     * @return boolean
     */
    public function isSizeValid($maxSizeKB = 200)
    {
        $maxSizeBytes = $maxSizeKB * 1024; // 200 KB em bytes
        // echo "<pre>";
        // print_r($this->size.' - ->'. $maxSizeBytes);
        // echo "</pre>";
        // exit;
        return $this->size >= $maxSizeBytes;
    }
    /**
     * Move o arquivo para um novo local
     * @param string $destination Caminho de destino para mover o arquivo
     * @return bool
     */
    public function uploadTo($destination)
    {
        if($this->error != 0) return false;
        $path = $destination.'/public/uploads/'.$this->getBaseName();
        
        $this->setNameDatabade($path);

        move_uploaded_file($this->tmpName, $path);
      
    }
}
