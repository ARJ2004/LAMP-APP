<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;

final class RequireAuth
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            redirect('/login');
        }
    }
}
