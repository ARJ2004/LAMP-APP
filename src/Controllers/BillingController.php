<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

final class BillingController
{
    public function index(): void
    {
        $pdo = Database::connection();
        $feeStructures = $pdo->query(
            'SELECT fs.id, fs.title, fs.amount, fs.due_days, c.name AS course_name, c.code AS course_code
             FROM fee_structures fs
             JOIN courses c ON c.id = fs.course_id
             ORDER BY fs.id DESC'
        )->fetchAll();
        $courses = $pdo->query('SELECT id, code, name FROM courses ORDER BY name ASC')->fetchAll();

        view('billing/index', [
            'title' => 'Fee Structures',
            'feeStructures' => $feeStructures,
            'courses' => $courses,
        ]);
    }

    public function storeFeeStructure(): void
    {
        verify_csrf();
        $pdo = Database::connection();

        $stmt = $pdo->prepare(
            'INSERT INTO fee_structures (course_id, title, amount, due_days, created_by)
             VALUES (:course_id, :title, :amount, :due_days, :created_by)'
        );

        $stmt->execute([
            'course_id' => (int)($_POST['course_id'] ?? 0),
            'title' => trim($_POST['title'] ?? ''),
            'amount' => (float)($_POST['amount'] ?? 0),
            'due_days' => (int)($_POST['due_days'] ?? 30),
            'created_by' => (int)($_SESSION['user']['id'] ?? 0),
        ]);

        $feeStructureId = (int)$pdo->lastInsertId();

        $billStmt = $pdo->prepare(
            'INSERT INTO student_bills (fee_structure_id, student_id, amount_due, due_date)
             SELECT :fee_structure_id, ce.student_id, :amount,
                    DATE_ADD(CURDATE(), INTERVAL :due_days DAY)
             FROM course_enrollments ce
             WHERE ce.course_id = :course_id'
        );
        $billStmt->execute([
            'fee_structure_id' => $feeStructureId,
            'amount' => (float)($_POST['amount'] ?? 0),
            'due_days' => (int)($_POST['due_days'] ?? 30),
            'course_id' => (int)($_POST['course_id'] ?? 0),
        ]);

        redirect('/billing');
    }

    public function myBills(): void
    {
        $studentId = $this->studentIdFromSession();
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT sb.id, sb.amount_due, sb.due_date, sb.status, fs.title, c.name AS course_name
             FROM student_bills sb
             JOIN fee_structures fs ON fs.id = sb.fee_structure_id
             JOIN courses c ON c.id = fs.course_id
             WHERE sb.student_id = :student_id
             ORDER BY sb.id DESC'
        );
        $stmt->execute(['student_id' => $studentId]);

        view('billing/my-bills', [
            'title' => 'My Bills',
            'bills' => $stmt->fetchAll(),
        ]);
    }

    public function payBill(int $billId): void
    {
        verify_csrf();
        $studentId = $this->studentIdFromSession();
        $pdo = Database::connection();

        $billStmt = $pdo->prepare('SELECT * FROM student_bills WHERE id = :id AND student_id = :student_id LIMIT 1');
        $billStmt->execute(['id' => $billId, 'student_id' => $studentId]);
        $bill = $billStmt->fetch();

        if (!$bill || $bill['status'] === 'paid') {
            redirect('/billing/my-bills');
        }

        $pdo->beginTransaction();
        try {
            $updateStmt = $pdo->prepare("UPDATE student_bills SET status = 'paid', updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $updateStmt->execute(['id' => $billId]);

            $paymentStmt = $pdo->prepare(
                'INSERT INTO fee_payments (bill_id, paid_amount, payment_ref) VALUES (:bill_id, :paid_amount, :payment_ref)'
            );
            $paymentRef = 'PAY-' . strtoupper(bin2hex(random_bytes(4)));
            $paymentStmt->execute([
                'bill_id' => $billId,
                'paid_amount' => $bill['amount_due'],
                'payment_ref' => $paymentRef,
            ]);

            $pdo->commit();
        } catch (\Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }

        redirect('/billing/invoice?bill_id=' . urlencode((string)$billId));
    }

    public function invoice(int $billId): void
    {
        $studentId = $this->studentIdFromSession();
        $pdo = Database::connection();
        $stmt = $pdo->prepare(
            'SELECT sb.id, sb.amount_due, sb.due_date, sb.status,
                    fs.title, c.name AS course_name,
                    fp.payment_ref, fp.paid_at, s.full_name, s.roll_number
             FROM student_bills sb
             JOIN fee_structures fs ON fs.id = sb.fee_structure_id
             JOIN courses c ON c.id = fs.course_id
             JOIN students s ON s.id = sb.student_id
             LEFT JOIN fee_payments fp ON fp.bill_id = sb.id
             WHERE sb.id = :bill_id AND sb.student_id = :student_id
             LIMIT 1'
        );
        $stmt->execute([
            'bill_id' => $billId,
            'student_id' => $studentId,
        ]);

        $invoice = $stmt->fetch();
        if (!$invoice || empty($invoice['payment_ref'])) {
            http_response_code(404);
            exit('Invoice not found');
        }

        view('billing/invoice', [
            'title' => 'Invoice',
            'invoice' => $invoice,
        ]);
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
