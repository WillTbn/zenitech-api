<?php
namespace App\Model\Entity;

use WilliamCosta\DatabaseManager\Database;

class User {

    /**
     * @var integer
     */
    public $id;
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $date_of_birth;
     /**
     * @var string
     */
    public $photo = 'uploads/default.jpg';

    // /**
    //  * Configurando foto
    //  * @var string $path
    //  */
    // public function setPhoto($path){
    //     $this->photo = $path;
    // }
    /**
     * Método retornar uma usuário com base no ID
     * @param integer $id
     * @return User
     */
    public function getUserById($id){
        $baseUrl = getenv("URL");
        // return self::getUsers('id = '.$id)->fetchObject(self::class);
        return self::getUsers('id = '.$id, null, null, 
            "id, name,email,created_at, updated_at,date_of_birth,CONCAT('http://localhost:8091/zenitech-api/', photo) AS photo"
        )->fetchObject(self::class);
    }
    /**
     * Método retornar uma usuário com base no e-mail
     * @param string $email
     * @return User
     */
    public function getUserByEmail($email){
        return self::getUsers('email = "'.$email.'"', null, null,'email')->fetchObject(self::class);
    }
    /**
     * Método retornar uma usuário com base no e-mail e id
     * @param string $email
     * @param integer $id
     * @return User
     */
    public function getUserByEmailAndId($email, $id){
        return self::getUsers('email = "'.$email.'" AND id= '.$id, null, null,'email')->fetchObject(self::class);
    }
    /**
     * Retorna usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     */
    public static function getUsers($where =null, $order = null, $limit = null, $fields = "*")
    {
        return (new Database('users'))->select($where, $order, $limit, $fields);
    }
    
    public function update(){
        // $this->date_of_birth = date('Y-m-d');
        // $this->id = new Database(users)
        return (new Database('users'))->update('id ='.$this->id,[
            'name' => $this->name,
            'email' => $this->email,
            'photo' => $this->photo,
            'date_of_birth' => $this->date_of_birth,
        ]);
    }
    public function create(){
        // $this->date_of_birth = date('Y-m-d');
        // $this->id = new Database(users)
        $this->id = (new Database('users'))->insert([
            "name" => $this->name,
            "email" => $this->email,
            'photo' => $this->photo,
            "date_of_birth" => $this->date_of_birth,
        ]);

        return true;
    }
    public function delete(){
        return (new Database('users'))->delete('id ='.$this->id);
    }
}