<?php

declare(strict_types=1);

function env(string $key, ?string $default = null): ?string
{
    static $vars = null;

    if ($vars === null) {
        $vars = [];
        $envFile = dirname(__DIR__, 2) . '/.env';
        if (is_readable($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$k, $v] = explode('=', $line, 2);
                $vars[trim($k)] = trim($v);
            }
        }
    }

    return $vars[$key] ?? $default;
}

function view(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $templatePath = dirname(__DIR__) . '/Views/' . $template . '.php';
    require dirname(__DIR__) . '/Views/layout.php';
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function verify_csrf(): void
{
    $sessionToken = $_SESSION['_csrf'] ?? null;
    $requestToken = $_POST['_csrf'] ?? null;

    if (!is_string($sessionToken) || $sessionToken === '' || !is_string($requestToken) || $requestToken === '') {
        http_response_code(419);
        exit('Invalid CSRF token');
    }

    if (!hash_equals($sessionToken, $requestToken)) {
        http_response_code(419);
        exit('Invalid CSRF token');
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
