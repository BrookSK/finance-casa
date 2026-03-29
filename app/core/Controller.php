<?php
/**
 * Controller base
 * Fornece métodos auxiliares para todos os controllers
 */
abstract class Controller
{
    /**
     * Renderiza uma view com layout
     */
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        // Extrair dados para a view
        extract($data);

        // Usuário logado
        $currentUser = $_SESSION['user'] ?? null;

        // Capturar conteúdo da view
        ob_start();
        require_once APP_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();

        // Renderizar layout
        require_once APP_PATH . '/views/layouts/' . $layout . '.php';
    }

    /**
     * Renderiza view sem layout (para login, etc)
     */
    protected function viewOnly(string $view, array $data = []): void
    {
        extract($data);
        require_once APP_PATH . '/views/' . $view . '.php';
    }

    /**
     * Retorna JSON
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Obtém o usuário logado
     */
    protected function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Verifica se é admin
     */
    protected function isAdmin(): bool
    {
        return ($this->user()['papel'] ?? '') === 'admin';
    }

    /**
     * Exige que seja admin
     */
    protected function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            setFlash('error', 'Acesso negado.');
            redirect('/dashboard');
            exit;
        }
    }
}
