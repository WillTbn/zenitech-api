<?php
namespace App\Utils;

class View{

    /**
     * variaveis padrões
     */
    private static $vars = [];
    /**
     * definir os dados inicias da classe
     */
    public static function init($vars = [])
    {
        self::$vars = $vars;
    }
     /**
     * Metodo que retorna o conteúdo de uma view
     * @return string
     */
    private static function getContentView($view)
    {
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) :"";
    }
    /**
     * Metodo que retorna o conteúdo de uma view renderizado
     * @param string $view
     * @param array $vars (string/numeric)
     * @return string
     */
    public static function render($view, $vars = [])
    {
        $contentView = self::getContentView($view);

        // merge de variáveis da view
        $vars = array_merge(self::$vars, $vars);

        
        //Chaves dos arrays
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{'.$item.'}}';
        }, $keys);
        // echo "<pre>";
        // print_r($keys);
        // echo "</pre>";exit;
        return str_replace($keys, array_values($vars), $contentView);

    }
}