<?php 
require __DIR__.'/../vendor/autoload.php';


use App\Controller\Pages\Home;
use App\Http\Middleware\Queue;
use App\Utils\View;
use WilliamCosta\DatabaseManager\Database;
use WilliamCosta\DotEnv\Environment;

Environment::load(__DIR__.'/../');

header("Access-Control-Allow-Origin: " . getenv('URL_CORS'));
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
// define('UPLOADS_PATH', __DIR__ . '/../public/uploads/');
// define('UPLOADS_URL', getenv('URL'));
// Captura a requisição OPTIONS e envia uma resposta imediata
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

define('URL', getenv('URL'));
// define o padrão de url
View::init(['URL' => URL]);

// define o mapeamento de middlewares
Queue::setMap([
    'maintenence' => \App\Http\Middleware\Maintenance::class,
    'api' => \App\Http\Middleware\Api::class,
]);

// define o mapeamento de middlewares
Queue::setDefault([
    'maintenence'
]);