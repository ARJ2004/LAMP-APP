<div class="card-clean p-4">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Payment Invoice</h1>
            <div class="text-muted small">Reference: <?= e($invoice['payment_ref']) ?></div>
        </div>
        <a href="/billing/my-bills" class="btn btn-outline-secondary btn-sm">Back to Bills</a>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div><strong>Student:</strong> <?= e($invoice['full_name']) ?></div>
            <div><strong>Roll No:</strong> <?= e($invoice['roll_number']) ?></div>
        </div>
        <div class="col-md-6">
            <div><strong>Course:</strong> <?= e($invoice['course_name']) ?></div>
            <div><strong>Fee Title:</strong> <?= e($invoice['title']) ?></div>
        </div>
        <div class="col-md-6">
            <div><strong>Paid On:</strong> <?= e($invoice['paid_at']) ?></div>
        </div>
        <div class="col-md-6">
            <div><strong>Amount Paid:</strong> ₹<?= number_format((float)$invoice['amount_due'], 2) ?></div>
        </div>
    </div>
</div>
