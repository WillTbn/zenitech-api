<?php

namespace App\Controller\Api\User;

use App\Http\Request;
use App\Model\Entity\User;
use Exception;
use PDO;
use WilliamCosta\DatabaseManager\Pagination;

class UserController {

    /**
     * Pegar todos os usuários
     * @param Request $request
     * @return array
     */
    public static function getAll(Request $request)
    {
        
        $quantity = User::getUsers(null, null, null, 'COUNT(*)  as qtd')->fetchObject()->qtd;

        $queryParams = $request->getQueryParams();
        $pageNow = $queryParams['page'] ?? 1;

        $obPagination = new Pagination($quantity, $pageNow, 5);

        $results = User::getUsers(null, null, $obPagination->getLimit())->fetchAll(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // print_r($obPagination);
        // echo "</pre>";
        // exit;
        return [
            "users" => $results,
            "pagination" =>  [
                "pages" => $obPagination->getPages(),
                "next" =>$obPagination->getCurrent(),
                "previes" =>$obPagination->getPrevies(),
                "quantity" =>$quantity,
                
            ],
        ];

    }
    /**
     * Responsavel em devolver usuário
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function get(Request $request, $id)
    {
        $user = new User;
        $user = $user->getUserById( (int)$id);
        if($user != false){
            return [
                "message" => "Usuário encontrado!",
                "user" => $user,
            ];
        }
        return [
            "message" => "Usuário não encontrado"
        ];

    }
    /**
     * Editar usuário passado
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function update(Request $request, $id)
    {
        $user = new User;
        $user = $user->getUserById( (int)$id);

        $postVars = $request->getPostVars();
        $user->name = $postVars['name'] ?? $user->name;
        $user->email = $postVars['email'] ?? $user->email;
        $user->photo = $postVars['photo'] ?? $user->photo;
        $user->date_of_birth = $postVars['date_of_birth'] ?? $user->date_of_birth;
        $user->update();
        if($user != false){
            return [
                "message" => "Usuário Atualizado com sucesso!",
                "user" => $user,
            ];
        }
        return [
            "message" => "Usuário não encontrado"
        ];
    }
    /**
     * Cria usuário
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function store(Request $request)
    {
        $user = new User;
        $postVars = $request->getPostVars();
        $user->name = $postVars['name'];
        $user->email = $postVars['email'];
        // $user->photo = $postVars['photo'];
        $user->date_of_birth = $postVars['date_of_birth'];
        $user->create();
        if($user != false){
            return [
                "message" => "Usuário Criado com sucesso!",
                "user" => $user,
            ];
        }
        return [
            "message" => "Usuário não encontrado"
        ];
    }
    /**
     * Deletando usuário
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function delete(Request $request, $id)
    {
        $user = new User;
        $user = $user->getUserById( (int)$id);

        $user->delete();
        
        return [
            "message" => "Usuário deletado com sucesso!",
        ];

    }

}