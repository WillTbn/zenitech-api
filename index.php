<?php

require __DIR__.'./includes/app.php';

use App\Http\Router;

//iniciar o router
$obRouter = new Router(URL);
// imprimi o response da rota
include __DIR__.'/routes/pages.php';

//rotas da api
include __DIR__.'/routes/api.php';

include __DIR__.'/routes/Api/V1/users.php';

$obRouter->run()->sendResponse();
?>