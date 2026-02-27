<div class="mb-3">
    <h1 class="h3 page-title mb-1">Fee Structures</h1>
    <p class="page-subtitle mb-0">Add fee plans by course and auto-generate student bills.</p>
</div>

<div class="card-clean p-3 mb-3">
    <h2 class="h5 mb-3">Create Fee Structure</h2>
    <form method="post" action="/billing/fee-structures/store" class="row g-2">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <div class="col-md-4">
            <select name="course_id" class="form-select" required>
                <option value="">Select course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= (int)$course['id'] ?>"><?= e($course['code'] . ' - ' . $course['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3"><input required name="title" class="form-control" placeholder="Fee title"></div>
        <div class="col-md-2"><input required name="amount" type="number" step="0.01" min="1" class="form-control" placeholder="Amount"></div>
        <div class="col-md-2"><input required name="due_days" type="number" min="1" class="form-control" value="30" placeholder="Due days"></div>
        <div class="col-md-1"><button class="btn btn-primary w-100">Save</button></div>
    </form>
</div>

<div class="card-clean p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr><th>Course</th><th>Title</th><th>Amount</th><th>Due (days)</th></tr>
            </thead>
            <tbody>
            <?php if (empty($feeStructures)): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">No fee structures added yet.</td></tr>
            <?php else: ?>
                <?php foreach ($feeStructures as $fee): ?>
                    <tr>
                        <td><?= e($fee['course_code'] . ' - ' . $fee['course_name']) ?></td>
                        <td><?= e($fee['title']) ?></td>
                        <td>₹<?= number_format((float)$fee['amount'], 2) ?></td>
                        <td><?= (int)$fee['due_days'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
