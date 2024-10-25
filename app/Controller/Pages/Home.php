<?php
namespace App\Controller\Pages;

use App\Model\Entity\User;
use App\Utils\View;

class Home extends Page{
    /**
     * Metodo responsabel por retornar o conteudo (view) da nossa home
     * @return string
     */
    public static function getHome()
    {
        $user = new User();
        $content = View::render('pages/home', [
            'name' =>$user->name
        ]);

        return parent::getPage('Zenitech - Teste', $content);

    }

}