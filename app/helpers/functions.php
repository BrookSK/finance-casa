<?php
/**
 * Funções auxiliares globais
 */

function redirect(string $url): void
{
    header("Location: {$url}");
    exit;
}

function setFlash(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function getFlash(string $type): ?string
{
    $msg = $_SESSION['flash'][$type] ?? null;
    unset($_SESSION['flash'][$type]);
    return $msg;
}

function hasFlash(string $type): bool
{
    return isset($_SESSION['flash'][$type]);
}

function old(string $key, string $default = ''): string
{
    return htmlspecialchars($_SESSION['old'][$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

function setOld(array $data): void
{
    $_SESSION['old'] = $data;
}

function clearOld(): void
{
    unset($_SESSION['old']);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatMoney(float $value): string
{
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function formatDate(string $date): string
{
    if (empty($date)) return '';
    return date('d/m/Y', strtotime($date));
}

function formatDateTime(string $date): string
{
    if (empty($date)) return '';
    return date('d/m/Y H:i', strtotime($date));
}

function currentMonth(): int
{
    return (int) date('m');
}

function currentYear(): int
{
    return (int) date('Y');
}

function monthName(int $month): string
{
    $months = [
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
    ];
    return $months[$month] ?? '';
}

function isActive(string $path): string
{
    if (isset($_GET['url']) && $_GET['url'] !== '') {
        $url = '/' . trim($_GET['url'], '/');
    } else {
        $url = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $url = '/' . trim($url ?: '', '/');
    }
    if ($url === '') $url = '/';
    if ($path === '/' || $path === '/dashboard') {
        return ($url === '/' || $url === '/dashboard') ? 'active' : '';
    }
    return strpos($url, $path) === 0 ? 'active' : '';
}

function percentual(float $value, float $total): float
{
    if ($total <= 0) return 0;
    return min(round(($value / $total) * 100, 1), 100);
}

function statusColor(float $percent): string
{
    if ($percent >= 90) return 'danger';
    if ($percent >= 70) return 'warning';
    return 'success';
}

function csrfField(): string
{
    return Csrf::field();
}
