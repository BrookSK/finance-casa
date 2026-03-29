<?php
/**
 * Proteção CSRF
 * Gera e valida tokens para formulários
 */
class Csrf
{
    public static function generate(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . self::generate() . '">';
    }

    public static function validate(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function regenerate(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
