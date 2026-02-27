<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class DashboardController
{
    public function index(): void
    {
        $pdo = Database::connection();
        $studentCount = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();

        $modules = [
            ['name' => 'Students', 'route' => '/students', 'status' => 'Live'],
            ['name' => 'Attendance', 'route' => '/attendance', 'status' => 'Live'],
            ['name' => 'Result Entry', 'route' => '/results', 'status' => 'Live'],
        ];

        if (($_SESSION['user']['role_name'] ?? '') === 'faculty') {
            $modules[] = ['name' => 'Teacher Dashboard', 'route' => '/teacher/dashboard', 'status' => 'Live'];
        }

        view('dashboard/index', [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'] ?? null,
            'studentCount' => $studentCount,
            'modules' => $modules,
        ]);
    }
}
