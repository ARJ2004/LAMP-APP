<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class StudentRegistrationController
{
    public function create(): void
    {
        $pdo = Database::connection();
        $courses = $pdo->query('SELECT id, code, name FROM courses ORDER BY name ASC')->fetchAll();

        view('students/register-account', [
            'title' => 'Register Student Account',
            'courses' => $courses,
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        $pdo = Database::connection();

        $roleStmt = $pdo->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
        $roleStmt->execute(['name' => 'student']);
        $roleId = (int)($roleStmt->fetch()['id'] ?? 0);

        if ($roleId === 0) {
            http_response_code(500);
            exit('Student role missing. Seed roles first.');
        }

        $pdo->beginTransaction();
        try {
            $userStmt = $pdo->prepare(
                'INSERT INTO users (role_id, name, email, password_hash, is_active)
                 VALUES (:role_id, :name, :email, :password_hash, 1)'
            );
            $userStmt->execute([
                'role_id' => $roleId,
                'name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'password_hash' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT),
            ]);

            $userId = (int)$pdo->lastInsertId();
            $studentStmt = $pdo->prepare(
                'INSERT INTO students (user_id, roll_number, full_name, email, phone, department, semester)
                 VALUES (:user_id, :roll_number, :full_name, :email, :phone, :department, :semester)'
            );
            $studentStmt->execute([
                'user_id' => $userId,
                'roll_number' => trim($_POST['roll_number'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'department' => trim($_POST['department'] ?? ''),
                'semester' => (int)($_POST['semester'] ?? 1),
            ]);

            $studentId = (int)$pdo->lastInsertId();
            $courseId = (int)($_POST['course_id'] ?? 0);
            if ($courseId > 0) {
                $enrollStmt = $pdo->prepare('INSERT IGNORE INTO course_enrollments (course_id, student_id) VALUES (:course_id, :student_id)');
                $enrollStmt->execute([
                    'course_id' => $courseId,
                    'student_id' => $studentId,
                ]);
            }

            $pdo->commit();
        } catch (\Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }

        redirect('/students');
    }
}
