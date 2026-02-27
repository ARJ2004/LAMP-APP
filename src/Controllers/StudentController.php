<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class StudentController
{
    public function index(): void
    {
        $query = trim($_GET['q'] ?? '');
        $pdo = Database::connection();

        if ($query !== '') {
            $stmt = $pdo->prepare('SELECT * FROM students WHERE full_name LIKE :q OR roll_number LIKE :q ORDER BY id DESC');
            $stmt->execute(['q' => "%{$query}%"]);
        } else {
            $stmt = $pdo->query('SELECT * FROM students ORDER BY id DESC');
        }

        view('students/index', [
            'title' => 'Students',
            'students' => $stmt->fetchAll(),
            'query' => $query,
        ]);
    }

    public function create(): void
    {
        view('students/create', ['title' => 'Add Student']);
    }

    public function store(): void
    {
        verify_csrf();
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO students (roll_number, full_name, email, phone, department, semester)
             VALUES (:roll_number, :full_name, :email, :phone, :department, :semester)'
        );

        $stmt->execute([
            'roll_number' => trim($_POST['roll_number'] ?? ''),
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'semester' => (int)($_POST['semester'] ?? 1),
        ]);

        redirect('/students');
    }

    public function edit(int $id): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $student = $stmt->fetch();

        if (!$student) {
            http_response_code(404);
            exit('Student not found');
        }

        view('students/edit', ['title' => 'Edit Student', 'student' => $student]);
    }

    public function update(int $id): void
    {
        verify_csrf();
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'UPDATE students
             SET roll_number = :roll_number, full_name = :full_name, email = :email, phone = :phone,
                 department = :department, semester = :semester
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'roll_number' => trim($_POST['roll_number'] ?? ''),
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'semester' => (int)($_POST['semester'] ?? 1),
        ]);

        redirect('/students');
    }

    public function delete(int $id): void
    {
        verify_csrf();
        $pdo = Database::connection();
        $stmt = $pdo->prepare('DELETE FROM students WHERE id = :id');
        $stmt->execute(['id' => $id]);

        redirect('/students');
    }
}
