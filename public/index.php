<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = dirname(__DIR__) . '/src/' . $relative . '.php';
    if (is_readable($file)) {
        require $file;
    }
});

require dirname(__DIR__) . '/src/Core/helpers.php';

session_name(env('SESSION_NAME', 'college_erp_session'));
session_start();

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\StudentController;
use App\Controllers\AttendanceController;
use App\Middleware\RequireAuth;
use App\Middleware\RequireRole;

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

$auth = new AuthController();
$dashboard = new DashboardController();
$students = new StudentController();
$attendance = new AttendanceController();

if ($path === '/') {
    redirect('/login');
}

if ($path === '/login' && $method === 'GET') {
    $auth->showLogin();
    exit;
}

if ($path === '/login' && $method === 'POST') {
    $auth->login();
    exit;
}

if ($path === '/logout' && $method === 'POST') {
    $auth->logout();
    exit;
}

RequireAuth::handle();

if ($path === '/dashboard' && $method === 'GET') {
    $dashboard->index();
    exit;
}

if ($path === '/students' && $method === 'GET') {
    RequireRole::handle(['super_admin', 'admin', 'faculty']);
    $students->index();
    exit;
}

if ($path === '/students/create' && $method === 'GET') {
    RequireRole::handle(['super_admin', 'admin']);
    $students->create();
    exit;
}

if ($path === '/students/store' && $method === 'POST') {
    RequireRole::handle(['super_admin', 'admin']);
    $students->store();
    exit;
}

if ($path === '/students/edit' && $method === 'GET') {
    RequireRole::handle(['super_admin', 'admin']);
    $students->edit((int)($_GET['id'] ?? 0));
    exit;
}

if ($path === '/students/update' && $method === 'POST') {
    RequireRole::handle(['super_admin', 'admin']);
    $students->update((int)($_GET['id'] ?? 0));
    exit;
}

if ($path === '/students/delete' && $method === 'POST') {
    RequireRole::handle(['super_admin', 'admin']);
    $students->delete((int)($_GET['id'] ?? 0));
    exit;
}


if ($path === '/attendance' && $method === 'GET') {
    RequireRole::handle(['super_admin', 'admin', 'faculty']);
    $attendance->index();
    exit;
}

if ($path === '/attendance/mark' && $method === 'POST') {
    RequireRole::handle(['super_admin', 'admin', 'faculty']);
    $attendance->store();
    exit;
}

if ($path === '/attendance/history' && $method === 'GET') {
    RequireRole::handle(['super_admin', 'admin', 'faculty']);
    $attendance->history();
    exit;
}

http_response_code(404);
echo 'Not Found';
