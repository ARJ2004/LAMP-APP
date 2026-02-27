<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

final class RequireRole
{
    public static function handle(array $roles): void
    {
        if (!Auth::hasRole($roles)) {
            http_response_code(403);
            view('errors/403', ['title' => 'Forbidden']);
            exit;
        }
    }
}
