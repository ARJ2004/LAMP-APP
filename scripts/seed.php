<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/Core/helpers.php';
require dirname(__DIR__) . '/src/Core/Database.php';

use App\Core\Database;

$pdo = Database::connection();

$roles = ['super_admin', 'admin', 'faculty', 'student', 'accountant', 'librarian'];
$stmtRole = $pdo->prepare('INSERT IGNORE INTO roles (name) VALUES (:name)');
foreach ($roles as $role) {
    $stmtRole->execute(['name' => $role]);
}

$roleMap = $pdo->query('SELECT id, name FROM roles')->fetchAll();
$ids = [];
foreach ($roleMap as $r) {
    $ids[$r['name']] = (int)$r['id'];
}

$password = password_hash('Password@123', PASSWORD_DEFAULT);
$users = [
    ['Super Admin', 'superadmin@erp.local', 'super_admin'],
    ['Admin User', 'admin@erp.local', 'admin'],
    ['Faculty User', 'faculty@erp.local', 'faculty'],
    ['Student User', 'student@erp.local', 'student'],
    ['Accountant User', 'accounts@erp.local', 'accountant'],
    ['Librarian User', 'library@erp.local', 'librarian'],
];

$stmtUser = $pdo->prepare(
    'INSERT IGNORE INTO users (role_id, name, email, password_hash, is_active)
     VALUES (:role_id, :name, :email, :password_hash, 1)'
);

foreach ($users as [$name, $email, $role]) {
    $stmtUser->execute([
        'role_id' => $ids[$role],
        'name' => $name,
        'email' => $email,
        'password_hash' => $password,
    ]);
}

$studentUserStmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$studentUserStmt->execute(['email' => 'student@erp.local']);
$studentUserId = (int)($studentUserStmt->fetch()['id'] ?? 0);

$students = [
    ['CSE-001', 'Aarav Sharma', 'aarav@college.local', '9000011111', 'Computer Science', 3, $studentUserId],
    ['ECE-014', 'Diya Patel', 'diya@college.local', '9000022222', 'Electronics', 5, null],
    ['ME-022', 'Rahul Verma', 'rahul@college.local', '9000033333', 'Mechanical', 2, null],
];

$stmtStudent = $pdo->prepare(
    'INSERT IGNORE INTO students (user_id, roll_number, full_name, email, phone, department, semester)
     VALUES (:user_id, :roll_number, :full_name, :email, :phone, :department, :semester)'
);

foreach ($students as [$roll, $name, $email, $phone, $department, $semester, $userId]) {
    $stmtStudent->execute([
        'user_id' => $userId,
        'roll_number' => $roll,
        'full_name' => $name,
        'email' => $email,
        'phone' => $phone,
        'department' => $department,
        'semester' => $semester,
    ]);
}

$superAdminStmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
$superAdminStmt->execute(['email' => 'superadmin@erp.local']);
$superAdminId = (int)($superAdminStmt->fetch()['id'] ?? 0);

$courseStmt = $pdo->prepare('INSERT IGNORE INTO courses (code, name, description, created_by) VALUES (:code, :name, :description, :created_by)');
$courseStmt->execute([
    'code' => 'CSE-BTECH',
    'name' => 'B.Tech Computer Science',
    'description' => 'Core UG program',
    'created_by' => $superAdminId ?: null,
]);

$courseIdStmt = $pdo->prepare('SELECT id FROM courses WHERE code = :code LIMIT 1');
$courseIdStmt->execute(['code' => 'CSE-BTECH']);
$courseId = (int)($courseIdStmt->fetch()['id'] ?? 0);

if ($courseId > 0) {
    $subjectStmt = $pdo->prepare('INSERT IGNORE INTO subjects (course_id, code, name) VALUES (:course_id, :code, :name)');
    $subjectStmt->execute(['course_id' => $courseId, 'code' => 'CS101', 'name' => 'Programming Fundamentals']);
    $subjectStmt->execute(['course_id' => $courseId, 'code' => 'CS102', 'name' => 'Database Systems']);

    $enrollStmt = $pdo->prepare(
        'INSERT IGNORE INTO course_enrollments (course_id, student_id)
         SELECT :course_id, s.id FROM students s WHERE s.roll_number IN ("CSE-001", "ECE-014")'
    );
    $enrollStmt->execute(['course_id' => $courseId]);
}

echo "Seeding complete\n";
