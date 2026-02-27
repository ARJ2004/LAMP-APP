<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class ResultController
{
    public function index(): void
    {
        $department = trim($_GET['department'] ?? '');
        $semester = (int)($_GET['semester'] ?? 0);
        $batchYear = (int)($_GET['batch_year'] ?? 0);
        $subjectId = (int)($_GET['subject_id'] ?? 0);
        $examName = trim($_GET['exam_name'] ?? 'Mid Term');

        $pdo = Database::connection();

        $subjectsStmt = $pdo->prepare(
            'SELECT id, subject_name
             FROM subjects
             WHERE (:department = "" OR department = :department)
               AND (:semester = 0 OR semester = :semester)
               AND (:batch_year = 0 OR batch_year = :batch_year)
             ORDER BY subject_name ASC'
        );
        $subjectsStmt->execute([
            'department' => $department,
            'semester' => $semester,
            'batch_year' => $batchYear,
        ]);

        $students = [];
        if ($subjectId > 0) {
            $studentStmt = $pdo->prepare(
                'SELECT s.id, s.roll_number, s.full_name, s.department, s.semester, s.batch_year,
                        r.marks
                 FROM student_subjects ss
                 JOIN students s ON s.id = ss.student_id
                 JOIN subjects sb ON sb.id = ss.subject_id
                 LEFT JOIN results r ON r.student_id = s.id AND r.subject_id = sb.id AND r.exam_name = :exam_name
                 WHERE ss.subject_id = :subject_id
                   AND (:department = "" OR s.department = :department)
                   AND (:semester = 0 OR s.semester = :semester)
                   AND (:batch_year = 0 OR s.batch_year = :batch_year)
                 ORDER BY s.full_name ASC'
            );

            $studentStmt->execute([
                'exam_name' => $examName,
                'subject_id' => $subjectId,
                'department' => $department,
                'semester' => $semester,
                'batch_year' => $batchYear,
            ]);
            $students = $studentStmt->fetchAll();
        }

        view('results/index', [
            'title' => 'Result Entry',
            'department' => $department,
            'semester' => $semester,
            'batchYear' => $batchYear,
            'subjectId' => $subjectId,
            'examName' => $examName,
            'subjects' => $subjectsStmt->fetchAll(),
            'students' => $students,
        ]);
    }

    public function store(): void
    {
        verify_csrf();

        $subjectId = (int)($_POST['subject_id'] ?? 0);
        $examName = trim($_POST['exam_name'] ?? 'Mid Term');
        $marksList = $_POST['marks'] ?? [];
        $enteredBy = (int)($_SESSION['user']['id'] ?? 0);

        if ($subjectId <= 0 || $examName === '') {
            redirect('/results');
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'INSERT INTO results (student_id, subject_id, exam_name, marks, entered_by)
             VALUES (:student_id, :subject_id, :exam_name, :marks, :entered_by)
             ON DUPLICATE KEY UPDATE marks = VALUES(marks), entered_by = VALUES(entered_by), updated_at = CURRENT_TIMESTAMP'
        );

        foreach ($marksList as $studentId => $marks) {
            $marksValue = is_numeric($marks) ? (float)$marks : null;
            if ($marksValue === null || $marksValue < 0 || $marksValue > 100) {
                continue;
            }

            $stmt->execute([
                'student_id' => (int)$studentId,
                'subject_id' => $subjectId,
                'exam_name' => $examName,
                'marks' => $marksValue,
                'entered_by' => $enteredBy,
            ]);
        }

        $query = http_build_query([
            'department' => trim($_POST['department'] ?? ''),
            'semester' => (int)($_POST['semester'] ?? 0),
            'batch_year' => (int)($_POST['batch_year'] ?? 0),
            'subject_id' => $subjectId,
            'exam_name' => $examName,
        ]);

        redirect('/results?' . $query);
    }
}
