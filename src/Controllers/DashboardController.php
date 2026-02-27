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
            ['name' => 'Students', 'status' => 'Live', 'route' => '/students'],
            ['name' => 'Attendance', 'status' => 'Live', 'route' => '/attendance'],
            ['name' => 'Exams & Grades', 'status' => 'Planned', 'route' => '/dashboard'],
            ['name' => 'Fees & Accounts', 'status' => 'Planned', 'route' => '/dashboard'],
            ['name' => 'Library', 'status' => 'Planned', 'route' => '/dashboard'],
            ['name' => 'Faculty', 'status' => 'Planned', 'route' => '/dashboard'],
        ];

        view('dashboard/index', [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'] ?? null,
            'studentCount' => $studentCount,
            'modules' => $modules,
        ]);
    }
}
