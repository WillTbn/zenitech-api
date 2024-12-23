<?php
namespace App\Http\RequestValidation;

use App\Http\UploadFile;
use App\Model\Entity\User;
use DateTime;

class UserCreatedRequestValidation extends RequestValidation
{

    public function validateName($name)
    {
        if (empty($name)) {
            $this->setErros(['name' => $name]);
        }
        if(strlen($name) < 3) {
            $this->setErros(['name.size' =>$name],'Nome deve conter mais de 3 caracteres');
        }
        if(!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $name)){
            $this->setErros(['name.char' =>$name], 'Nome invalido!');
        }
    }

    public function validateEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->setErros(['email' => $email], "O e-mail inválido");
        }
        $userEmail = (new User)->getUserByEmail($email);
        if(! empty($userEmail)){
            $this->setErros(['email' => $email], "O e-mail já cadastrado!");
        }
    }
    /**
     * Verifica existencia do file
     * @var array $photo
     */
    public function existsPhoto($photo){
        return isset($photo['error']) && $photo['error'] === 0;        
    }
    /**
     * validação de photo
     * @var array $photo
     */
    public function validatePhoto($photo)
    {
        
        if(!$this->existsPhoto($photo)){
            return;
        }
        $uplod = new UploadFile($photo);
       
        if($uplod->getExtension() != 'jpg'){
            $this->setErros(['photo.extension' => $photo], "Formato não permitido, aceitamos '.jpg'!");
        }
        
        if($uplod->isSizeValid()){
            $this->setErros(['photo.size' => $photo], "Excedeu o limite permitido, aceitamos até 200kb.");
        }
    }
    public function validateBirthdate($birthdate)
    {
        $dob = new DateTime($birthdate);
        $now = new DateTime();
        $age = $dob->diff($now)->y;

        if ($age < 18) {
            $this->setErros(['date_of_birth' => $birthdate], "Idade deve ser maior ou igual a 18 anos");
        }
    }

    public function validateAll($data)
    {
        
        $this->validateName($data['name'] ?? '');
        $this->validateEmail($data['email'] ?? '');
        $this->validatePhoto($data['photo'] ?? '');
        $this->validateBirthdate($data['date_of_birth'] ?? '');
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