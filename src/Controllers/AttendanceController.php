<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class AttendanceController
{
    public function index(): void
    {
        $date = $_GET['date'] ?? date('Y-m-d');
        $department = trim($_GET['department'] ?? '');
        $semester = (int)($_GET['semester'] ?? 0);
        $batchYear = (int)($_GET['batch_year'] ?? 0);
        $subjectId = (int)($_GET['subject_id'] ?? 0);

        $pdo = Database::connection();
        $subjects = $this->fetchSubjects($pdo, $department, $semester, $batchYear);

        $sql = 'SELECT s.id, s.roll_number, s.full_name, s.department, s.semester, s.batch_year, a.status
                FROM students s
                LEFT JOIN attendance a
                  ON a.student_id = s.id AND a.attendance_date = :attendance_date AND '
                . ($subjectId > 0 ? 'a.subject_id = :subject_id' : 'a.subject_id IS NULL')
                . ' WHERE 1=1';

        $params = ['attendance_date' => $date];

        if ($department !== '') {
            $sql .= ' AND s.department = :department';
            $params['department'] = $department;
        }
        if ($semester > 0) {
            $sql .= ' AND s.semester = :semester';
            $params['semester'] = $semester;
        }
        if ($batchYear > 0) {
            $sql .= ' AND s.batch_year = :batch_year';
            $params['batch_year'] = $batchYear;
        }
        if ($subjectId > 0) {
            $sql .= ' AND EXISTS (
                SELECT 1 FROM student_subjects ss
                WHERE ss.student_id = s.id AND ss.subject_id = :subject_id
            )';
            $params['subject_id'] = $subjectId;
        }

        $sql .= ' ORDER BY s.full_name ASC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        view('attendance/index', [
            'title' => 'Mark Attendance',
            'date' => $date,
            'rows' => $stmt->fetchAll(),
            'subjects' => $subjects,
            'department' => $department,
            'semester' => $semester,
            'batchYear' => $batchYear,
            'subjectId' => $subjectId,
        ]);
    }

    public function store(): void
    {
        verify_csrf();

        $date = $_POST['attendance_date'] ?? date('Y-m-d');
        $statuses = $_POST['status'] ?? [];
        $markedBy = (int)($_SESSION['user']['id'] ?? 0);
        $subjectId = (int)($_POST['subject_id'] ?? 0);

        $pdo = Database::connection();

        $upsertSubject = $pdo->prepare(
            'INSERT INTO attendance (student_id, subject_id, attendance_date, status, marked_by)
             VALUES (:student_id, :subject_id, :attendance_date, :status, :marked_by)
             ON DUPLICATE KEY UPDATE status = VALUES(status), marked_by = VALUES(marked_by), updated_at = CURRENT_TIMESTAMP'
        );

        $updateGeneral = $pdo->prepare(
            'UPDATE attendance
             SET status = :status, marked_by = :marked_by, updated_at = CURRENT_TIMESTAMP
             WHERE student_id = :student_id AND attendance_date = :attendance_date AND subject_id IS NULL'
        );

        $insertGeneral = $pdo->prepare(
            'INSERT INTO attendance (student_id, subject_id, attendance_date, status, marked_by)
             VALUES (:student_id, NULL, :attendance_date, :status, :marked_by)'
        );

        foreach ($statuses as $studentId => $status) {
            if (!in_array($status, ['present', 'absent', 'late'], true)) {
                continue;
            }

            $studentId = (int)$studentId;

            if ($subjectId > 0) {
                $upsertSubject->execute([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'attendance_date' => $date,
                    'status' => $status,
                    'marked_by' => $markedBy,
                ]);
                continue;
            }

            $updateGeneral->execute([
                'student_id' => $studentId,
                'attendance_date' => $date,
                'status' => $status,
                'marked_by' => $markedBy,
            ]);

            if ($updateGeneral->rowCount() === 0) {
                $insertGeneral->execute([
                    'student_id' => $studentId,
                    'attendance_date' => $date,
                    'status' => $status,
                    'marked_by' => $markedBy,
                ]);
            }
        }

        $query = http_build_query([
            'date' => $date,
            'department' => trim($_POST['department'] ?? ''),
            'semester' => (int)($_POST['semester'] ?? 0),
            'batch_year' => (int)($_POST['batch_year'] ?? 0),
            'subject_id' => $subjectId,
        ]);

        redirect('/attendance?' . $query);
    }

    public function history(): void
    {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-d');

        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT a.attendance_date, a.status, s.roll_number, s.full_name, s.department, sb.subject_name
             FROM attendance a
             JOIN students s ON s.id = a.student_id
             LEFT JOIN subjects sb ON sb.id = a.subject_id
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

    private function fetchSubjects(\PDO $pdo, string $department, int $semester, int $batchYear): array
    {
        $sql = 'SELECT id, subject_name FROM subjects WHERE 1=1';
        $params = [];

        if ($department !== '') {
            $sql .= ' AND department = :department';
            $params['department'] = $department;
        }
        if ($semester > 0) {
            $sql .= ' AND semester = :semester';
            $params['semester'] = $semester;
        }
        if ($batchYear > 0) {
            $sql .= ' AND batch_year = :batch_year';
            $params['batch_year'] = $batchYear;
        }

        $sql .= ' ORDER BY subject_name ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }
}
