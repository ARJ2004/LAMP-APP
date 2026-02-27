<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class AttendanceController
{
    public function index(): void
    {
        $date = $_GET['date'] ?? date('Y-m-d');
        $courseId = (int)($_GET['course_id'] ?? 0);
        $subjectId = (int)($_GET['subject_id'] ?? 0);
        $pdo = Database::connection();

        $courses = $pdo->query('SELECT id, code, name FROM courses ORDER BY name ASC')->fetchAll();
        $subjects = [];
        $rows = [];

        if ($courseId > 0) {
            $subjectStmt = $pdo->prepare('SELECT id, code, name FROM subjects WHERE course_id = :course_id ORDER BY name ASC');
            $subjectStmt->execute(['course_id' => $courseId]);
            $subjects = $subjectStmt->fetchAll();
        }

        if ($subjectId > 0) {
            $stmt = $pdo->prepare(
                'SELECT s.id, s.roll_number, s.full_name, s.department, s.semester,
                        a.status
                 FROM course_enrollments ce
                 JOIN students s ON s.id = ce.student_id
                 LEFT JOIN attendance a
                    ON a.student_id = s.id
                   AND a.subject_id = :subject_id
                   AND a.attendance_date = :attendance_date
                 WHERE ce.course_id = :course_id
                 ORDER BY s.full_name ASC'
            );
            $stmt->execute([
                'subject_id' => $subjectId,
                'attendance_date' => $date,
                'course_id' => $courseId,
            ]);
            $rows = $stmt->fetchAll();
        }

        view('attendance/index', [
            'title' => 'Mark Attendance',
            'date' => $date,
            'rows' => $rows,
            'courses' => $courses,
            'subjects' => $subjects,
            'courseId' => $courseId,
            'subjectId' => $subjectId,
        ]);
    }

    public function store(): void
    {
        verify_csrf();

        $date = $_POST['attendance_date'] ?? date('Y-m-d');
        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $statuses = $_POST['status'] ?? [];
        $markedBy = (int)($_SESSION['user']['id'] ?? 0);

        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO attendance (student_id, subject_id, attendance_date, status, marked_by)
             VALUES (:student_id, :subject_id, :attendance_date, :status, :marked_by)
             ON DUPLICATE KEY UPDATE status = VALUES(status), marked_by = VALUES(marked_by), updated_at = CURRENT_TIMESTAMP'
        );

        foreach ($statuses as $studentId => $status) {
            if (!in_array($status, ['present', 'absent', 'late'], true)) {
                continue;
            }

            $stmt->execute([
                'student_id' => (int)$studentId,
                'subject_id' => $subjectId,
                'attendance_date' => $date,
                'status' => $status,
                'marked_by' => $markedBy,
            ]);
        }

        $courseId = (int)($_POST['course_id'] ?? 0);
        redirect('/attendance?date=' . urlencode($date) . '&course_id=' . $courseId . '&subject_id=' . $subjectId);
    }

    public function history(): void
    {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-d');

        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT a.attendance_date, a.status, s.roll_number, s.full_name, sub.name AS subject_name, c.name AS course_name
             FROM attendance a
             JOIN students s ON s.id = a.student_id
             JOIN subjects sub ON sub.id = a.subject_id
             JOIN courses c ON c.id = sub.course_id
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
