<?php
/**
 * FinançasCasal - Ponto de entrada da aplicação
 * Todas as requisições passam por aqui via .htaccess
 */

session_start();

// Definir constantes do sistema
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('APP_NAME', 'FinançasCasal');
define('APP_VERSION', '1.0.0');

// Autoload simples
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/services/',
        APP_PATH . '/repositories/',
        APP_PATH . '/helpers/',
        APP_PATH . '/middleware/',
        APP_PATH . '/core/',
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Carregar helpers globais
require_once APP_PATH . '/helpers/functions.php';

// Inicializar banco de dados
require_once APP_PATH . '/core/Database.php';

// Inicializar CSRF
require_once APP_PATH . '/core/Csrf.php';

// Router
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/routes/web.php';

$router = new Router();
registerRoutes($router);
$router->dispatch();
