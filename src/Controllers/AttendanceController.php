<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class AttendanceController
{
    public function index(): void
    {
        $date = $_GET['date'] ?? date('Y-m-d');
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'SELECT s.id, s.roll_number, s.full_name, s.department, s.semester,
                    a.status
             FROM students s
             LEFT JOIN attendance a
               ON a.student_id = s.id AND a.attendance_date = :attendance_date
             ORDER BY s.full_name ASC'
        );
        $stmt->execute(['attendance_date' => $date]);

        view('attendance/index', [
            'title' => 'Mark Attendance',
            'date' => $date,
            'rows' => $stmt->fetchAll(),
        ]);
    }

    public function store(): void
    {
        verify_csrf();

        $date = $_POST['attendance_date'] ?? date('Y-m-d');
        $statuses = $_POST['status'] ?? [];
        $markedBy = (int)($_SESSION['user']['id'] ?? 0);

        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO attendance (student_id, attendance_date, status, marked_by)
             VALUES (:student_id, :attendance_date, :status, :marked_by)
             ON DUPLICATE KEY UPDATE status = VALUES(status), marked_by = VALUES(marked_by), updated_at = CURRENT_TIMESTAMP'
        );

        foreach ($statuses as $studentId => $status) {
            if (!in_array($status, ['present', 'absent', 'late'], true)) {
                continue;
            }

            $stmt->execute([
                'student_id' => (int)$studentId,
                'attendance_date' => $date,
                'status' => $status,
                'marked_by' => $markedBy,
            ]);
        }

        redirect('/attendance?date=' . urlencode($date));
    }

    public function history(): void
    {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-d');

        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT a.attendance_date, a.status, s.roll_number, s.full_name, s.department
             FROM attendance a
             JOIN students s ON s.id = a.student_id
             WHERE a.attendance_date BETWEEN :from_date AND :to_date
             ORDER BY a.attendance_date DESC, s.full_name ASC'
        );

        $stmt->execute([
            'from_date' => $from,
            'to_date' => $to,
        ]);

        view('attendance/history', [
            'title' => 'Attendance History',
            'records' => $stmt->fetchAll(),
            'from' => $from,
            'to' => $to,
        ]);
    }
}
