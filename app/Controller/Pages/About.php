<?php
namespace App\Controller\Pages;

use App\Model\Entity\User;
use App\Utils\View;

class About extends Page{
    /**
     * Metodo responsabel por retornar o conteudo (view) da nossa about
     * @return string
     */
    public static function getAbout()
    {
        $user = new User();
        $content = View::render('pages/about', [
            'name' =>$user->name
        ]);

        return parent::getPage('Zenitech - sobre', $content);

    }

}