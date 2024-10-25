<?php
namespace App\Http\RequestValidation;

use App\Model\Entity\User;
use DateTime;

class UserUpdateRequestValidation
{
    /**
     * Erros da request
     * @var array
     */
    private $erros = [];
    public function __construct()
    {
        
    }
    private function setErros($erro, $message = "Campo obrigatório")
    {
        $keys = array_keys($erro);
        $key = $keys[0];

        $this->erros[$key] = $message;
    }

    public function getErros()
    {
        return $this->erros;
    }

    public function validateName($name)
    {
        if (empty($name)) {
            $this->setErros(['name' => $name]);
        }
    }

    // public function validateEmail($email)
    // {
    //     if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    //         $this->setErros(['email' => $email], "O e-mail inválido");
    //     }
    //     $userEmail = (new User)->getUserByEmail($email);
    //     if(! empty($userEmail)){
    //         $this->setErros(['email' => $email], "O e-mail já cadastrado!");
    //     }
    // }

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
        // $this->validateEmail($data['email'] ?? '');
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