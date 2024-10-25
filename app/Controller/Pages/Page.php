<?php
namespace App\Controller\Pages;

use App\Utils\View;

class Page{
    /**
     * Metodo responsabel por renderizar o header da página
     * @return string
     */
    private static function getHeader()
    {
        return View::render('pages/partials/header');
    }
     /**
     * Metodo responsabel por renderizar o footer da página
     * @return string
     */
    private static function getFooter()
    {
        return View::render('pages/partials/footer');
    }
    /**
     * Metodo responsabel por retornar o conteudo (view) da nossa página genérica
     * @return string
     */
    public static function getPage($title, $content)
    {
        return View::render('pages/page', [
            'title' =>$title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter(),
        ]);
    }

}