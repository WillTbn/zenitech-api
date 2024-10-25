<?php

namespace App\Controller\Api;

class Api {
    public static function getDetails($request)
    {
        return [
            "name" =>"Teste conhecimentos Zenitech",
            "version" => "v1.0.0",
            "author"=>"Jorge Nunes - @github/willTbn - jlbnunes@live.com"
        ];
    }
}
