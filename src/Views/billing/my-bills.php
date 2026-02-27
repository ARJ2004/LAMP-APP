<div class="mb-3">
    <h1 class="h3 page-title mb-1">My Bills</h1>
    <p class="page-subtitle mb-0">Pay pending bills and download invoice after successful payment.</p>
</div>

<div class="card-clean p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr><th>Course</th><th>Fee</th><th>Due Date</th><th>Amount</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php if (empty($bills)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No bills yet.</td></tr>
            <?php else: ?>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?= e($bill['course_name']) ?></td>
                        <td><?= e($bill['title']) ?></td>
                        <td><?= e($bill['due_date']) ?></td>
                        <td>₹<?= number_format((float)$bill['amount_due'], 2) ?></td>
                        <td>
                            <span class="badge <?= $bill['status'] === 'paid' ? 'text-bg-success' : 'text-bg-warning' ?> text-uppercase">
                                <?= e($bill['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($bill['status'] === 'paid'): ?>
                                <a href="/billing/invoice?bill_id=<?= (int)$bill['id'] ?>" class="btn btn-outline-secondary btn-sm">Invoice</a>
                            <?php else: ?>
                                <form method="post" action="/billing/pay?bill_id=<?= (int)$bill['id'] ?>" class="m-0">
                                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                    <button class="btn btn-primary btn-sm">Pay Now</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
