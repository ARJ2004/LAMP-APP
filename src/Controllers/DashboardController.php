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
        $user = $_SESSION['user'] ?? null;
        $role = $user['role_name'] ?? '';
        $pdo = Database::connection();

        $studentCount = (int)$pdo->query('SELECT COUNT(*) AS total FROM students')->fetch()['total'];

        $modules = [];
        if (in_array($role, ['super_admin', 'admin', 'faculty'], true)) {
            $modules[] = ['name' => 'Students', 'route' => '/students', 'status' => 'Live'];
            $modules[] = ['name' => 'Courses', 'route' => '/courses', 'status' => 'Live'];
            $modules[] = ['name' => 'Attendance', 'route' => '/attendance', 'status' => 'Live'];
        }

        if ($role === 'super_admin') {
            $modules[] = ['name' => 'Student Registration', 'route' => '/students/register-account', 'status' => 'Live'];
            $modules[] = ['name' => 'Fee Structures', 'route' => '/billing', 'status' => 'Live'];
        }

        if ($role === 'student') {
            $modules[] = ['name' => 'Course Registration', 'route' => '/courses/register', 'status' => 'Live'];
            $modules[] = ['name' => 'My Bills', 'route' => '/billing/my-bills', 'status' => 'Live'];
        }

        view('dashboard/index', [
            'title' => 'Dashboard',
            'user' => $_SESSION['user'] ?? null,
            'user' => $user,
            'studentCount' => $studentCount,
            'modules' => $modules,
        ]);
    }
}
