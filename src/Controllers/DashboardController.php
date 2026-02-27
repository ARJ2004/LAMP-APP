<?php

declare(strict_types=1);

namespace App\Controllers;

final class DashboardController
{
    public function index(): void
    {
        view('dashboard/index', [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'] ?? null,
        ]);
    }
}
