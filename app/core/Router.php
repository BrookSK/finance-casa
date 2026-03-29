<?php
/**
 * Router simples para MVC
 * Suporta GET, POST, PUT, DELETE
 * Suporta middleware de autenticação
 */
class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method, bool $auth = true): void
    {
        $this->addRoute('GET', $path, $controller, $method, $auth);
    }

    public function post(string $path, string $controller, string $method, bool $auth = true): void
    {
        $this->addRoute('POST', $path, $controller, $method, $auth);
    }

    private function addRoute(string $httpMethod, string $path, string $controller, string $method, bool $auth): void
    {
        $this->routes[] = [
            'httpMethod' => $httpMethod,
            'path' => $path,
            'controller' => $controller,
            'method' => $method,
            'auth' => $auth,
        ];
    }

    public function dispatch(): void
    {
        // Detectar URL: primeiro tenta $_GET['url'] (via .htaccess do /public),
        // senão usa REQUEST_URI (quando servidor aponta pra raiz)
        if (isset($_GET['url']) && $_GET['url'] !== '') {
            $url = '/' . trim($_GET['url'], '/');
        } else {
            $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $url = $url ?: '/';
        }
        // Normalizar
        $url = '/' . trim($url, '/');
        if ($url === '') $url = '/';

        $httpMethod = $_SERVER['REQUEST_METHOD'];

        // Suporte a _method para PUT/DELETE via POST
        if ($httpMethod === 'POST' && isset($_POST['_method'])) {
            $httpMethod = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);
            if ($route['httpMethod'] === $httpMethod && preg_match($pattern, $url, $matches)) {
                // Verificar autenticação
                if ($route['auth'] && !AuthMiddleware::check()) {
                    redirect('/login');
                    return;
                }

                // Verificar CSRF em POST
                if ($httpMethod === 'POST') {
                    if (!Csrf::validate($_POST['_token'] ?? '')) {
                        setFlash('error', 'Token de segurança inválido. Tente novamente.');
                        redirect($url);
                        return;
                    }
                }

                $controllerName = $route['controller'];
                $methodName = $route['method'];

                require_once APP_PATH . '/controllers/' . $controllerName . '.php';
                $controller = new $controllerName();

                // Extrair parâmetros da URL
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        // 404
        http_response_code(404);
        require_once APP_PATH . '/views/errors/404.php';
    }

    private function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}
