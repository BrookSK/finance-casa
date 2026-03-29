<?php
/**
 * Middleware de autenticação
 * Verifica se o usuário está logado
 */
class AuthMiddleware
{
    public static function check(): bool
    {
        return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function login(array $user): void
    {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'email' => $user['email'],
            'papel' => $user['papel'],
        ];
        session_regenerate_id(true);
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function isAdmin(): bool
    {
        return (self::user()['papel'] ?? '') === 'admin';
    }
}
