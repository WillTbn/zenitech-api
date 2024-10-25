<?php

namespace App\Controller\Api\Auth;

use App\Http\Request;
use App\Model\Entity\User;
use Exception;

class Authentication {

    /**
     * Responsavel para gera o token
     * @param Request $request
     * @return array
     */
    public static function generateToken(Request $request)
    {
        $postVars = $request->getPostVars();
        if(!isset($postVars['email']) || !isset($postVars['password'])){
            throw new Exception("Os campos email e senhas são obrigatórios", 400);
        }

        // $user = User::getUserByEmail($postVars['email']);

        return [
            "token" =>"T333214wqdqwd",
        ];
    }
}