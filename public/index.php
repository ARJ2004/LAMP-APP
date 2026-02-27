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

use App\Controllers\AttendanceController;
use App\Controllers\AuthController;
use App\Controllers\BillingController;
use App\Controllers\CourseController;
use App\Controllers\DashboardController;
use App\Controllers\StudentController;
use App\Controllers\AttendanceController;
use App\Controllers\ResultController;
use App\Controllers\TeacherDashboardController;
use App\Controllers\StudentRegistrationController;
use App\Middleware\RequireAuth;
use App\Middleware\RequireRole;

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

$auth = new AuthController();
$dashboard = new DashboardController();
$students = new StudentController();
$attendance = new AttendanceController();
$results = new ResultController();
$teacherDashboard = new TeacherDashboardController();
$courses = new CourseController();
$studentRegistration = new StudentRegistrationController();
$billing = new BillingController();

try {
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


if ($path === '/teacher/dashboard' && $method === 'GET') {
    RequireRole::handle(['faculty']);
    $teacherDashboard->index();
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

if ($path === '/students/register-account' && $method === 'GET') {
    RequireRole::handle(['super_admin']);
    $studentRegistration->create();
    exit;
}

if ($path === '/students/register-account' && $method === 'POST') {
    RequireRole::handle(['super_admin']);
    $studentRegistration->store();
    exit;
}

if ($path === '/courses' && $method === 'GET') {
    RequireRole::handle(['super_admin', 'admin', 'faculty']);
    $courses->index();
    exit;
}

if ($path === '/courses/store' && $method === 'POST') {
    RequireRole::handle(['super_admin']);
    $courses->store();
    exit;
}

if ($path === '/courses/subjects/store' && $method === 'POST') {
    RequireRole::handle(['super_admin', 'admin']);
    $courses->addSubject((int)($_GET['course_id'] ?? 0));
    exit;
}

if ($path === '/courses/register' && $method === 'GET') {
    RequireRole::handle(['student']);
    $courses->registerForm();
    exit;
}

if ($path === '/courses/register' && $method === 'POST') {
    RequireRole::handle(['student']);
    $courses->register();
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


if ($path === '/results' && $method === 'GET') {
    RequireRole::handle(['super_admin', 'admin', 'faculty']);
    $results->index();
    exit;
}

if ($path === '/results/store' && $method === 'POST') {
    RequireRole::handle(['super_admin', 'admin', 'faculty']);
    $results->store();
if ($path === '/billing' && $method === 'GET') {
    RequireRole::handle(['super_admin']);
    $billing->index();
    exit;
}

if ($path === '/billing/fee-structures/store' && $method === 'POST') {
    RequireRole::handle(['super_admin']);
    $billing->storeFeeStructure();
    exit;
}

if ($path === '/billing/my-bills' && $method === 'GET') {
    RequireRole::handle(['student']);
    $billing->myBills();
    exit;
}

if ($path === '/billing/pay' && $method === 'POST') {
    RequireRole::handle(['student']);
    $billing->payBill((int)($_GET['bill_id'] ?? 0));
    exit;
}

if ($path === '/billing/invoice' && $method === 'GET') {
    RequireRole::handle(['student']);
    $billing->invoice((int)($_GET['bill_id'] ?? 0));
    exit;
}

http_response_code(404);
echo 'Not Found';

} catch (\PDOException $exception) {
    http_response_code(500);
    view('errors/500', [
        'title' => 'Database Error',
        'message' => 'Database connection failed. Please verify DB settings and that MySQL is running.',
    ]);
}
