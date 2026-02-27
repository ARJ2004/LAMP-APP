<?php

declare(strict_types=1);

namespace App\Controllers;

final class TeacherDashboardController
{
    public function index(): void
    {
        view('teacher/dashboard', [
            'title' => 'Teacher Dashboard',
        ]);
    }
}
