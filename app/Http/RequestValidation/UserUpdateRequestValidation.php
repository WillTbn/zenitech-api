<?php
namespace App\Http\RequestValidation;

use App\Http\UploadFile;
use App\Model\Entity\User;
use DateTime;

class UserUpdateRequestValidation extends RequestValidation
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
    public function validateId($id)
    {
        if (empty($id)) {
            $this->setErros(['id' => $id]);
        }
        $user = (new User)->getUserById($id);
        if(empty($user)){
            $this->setErros(['id' => $id], "Usuário não existe!");
        }
    }

    public function validateAll($data)
    {
        
        $this->validateName($data['name'] ?? '');
        $this->validateId($data['id'] ?? '');
        $this->validatePhoto($data['photo'] ?? '');
        $this->validateBirthdate($data['date_of_birth'] ?? '');
    }
}