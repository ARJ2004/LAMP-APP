<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class CourseController
{
    public function index(): void
    {
        $pdo = Database::connection();
        $courses = $pdo->query(
            'SELECT c.*, COUNT(DISTINCT ce.student_id) AS enrolled_students, COUNT(DISTINCT s.id) AS subject_count
             FROM courses c
             LEFT JOIN course_enrollments ce ON ce.course_id = c.id
             LEFT JOIN subjects s ON s.course_id = c.id
             GROUP BY c.id
             ORDER BY c.id DESC'
        )->fetchAll();

        view('courses/index', [
            'title' => 'Courses',
            'courses' => $courses,
            'isSuperAdmin' => (($_SESSION['user']['role_name'] ?? '') === 'super_admin'),
        ]);
    }

    public function store(): void
    {
        verify_csrf();
        $pdo = Database::connection();

        $stmt = $pdo->prepare('INSERT INTO courses (code, name, description, created_by) VALUES (:code, :name, :description, :created_by)');
        $stmt->execute([
            'code' => strtoupper(trim($_POST['code'] ?? '')),
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'created_by' => (int)($_SESSION['user']['id'] ?? 0),
        ]);

        redirect('/courses');
    }

    public function addSubject(int $courseId): void
    {
        verify_csrf();
        $pdo = Database::connection();

        $stmt = $pdo->prepare('INSERT INTO subjects (course_id, code, name) VALUES (:course_id, :code, :name)');
        $stmt->execute([
            'course_id' => $courseId,
            'code' => strtoupper(trim($_POST['code'] ?? '')),
            'name' => trim($_POST['name'] ?? ''),
        ]);

        redirect('/courses');
    }

    public function registerForm(): void
    {
        $studentId = $this->studentIdFromSession();
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT c.id, c.code, c.name, c.description,
                    CASE WHEN ce.id IS NULL THEN 0 ELSE 1 END AS is_registered
             FROM courses c
             LEFT JOIN course_enrollments ce ON ce.course_id = c.id AND ce.student_id = :student_id
             ORDER BY c.name ASC'
        );
        $stmt->execute(['student_id' => $studentId]);

        view('courses/register', [
            'title' => 'Course Registration',
            'courses' => $stmt->fetchAll(),
        ]);
    }

    public function register(): void
    {
        verify_csrf();
        $studentId = $this->studentIdFromSession();
        $courseId = (int)($_POST['course_id'] ?? 0);

        $pdo = Database::connection();
        $stmt = $pdo->prepare('INSERT IGNORE INTO course_enrollments (course_id, student_id) VALUES (:course_id, :student_id)');
        $stmt->execute([
            'course_id' => $courseId,
            'student_id' => $studentId,
        ]);

        redirect('/courses/register');
    }

    private function studentIdFromSession(): int
    {
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT id FROM students WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $student = $stmt->fetch();

        if (!$student) {
            http_response_code(403);
            exit('Student profile not linked. Contact super admin.');
        }

        return (int)$student['id'];
    }
}
