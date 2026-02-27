<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 page-title mb-1">Attendance History</h1>
        <p class="page-subtitle mb-0">View attendance records by date range.</p>
    </div>
    <a class="btn btn-outline-secondary" href="/attendance">Back to Marking</a>
</div>

<div class="card-clean p-3 mb-3">
    <form class="row g-2 align-items-end" method="get" action="/attendance/history">
        <div class="col-md-4 col-lg-3">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="<?= e($from) ?>">
        </div>
        <div class="col-md-4 col-lg-3">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="<?= e($to) ?>">
        </div>
        <div class="col-md-3 col-lg-2">
            <button class="btn btn-soft w-100">Filter</button>
        </div>
    </form>
</div>

<div class="card-clean p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr><th>Date</th><th>Roll</th><th>Name</th><th>Department</th><th>Subject</th><th>Status</th></tr>
                <tr><th>Date</th><th>Roll</th><th>Name</th><th>Course</th><th>Subject</th><th>Status</th></tr>
            </thead>
            <tbody>
            <?php if (empty($records)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No attendance records found in selected range.</td></tr>
            <?php else: ?>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= e($record['attendance_date']) ?></td>
                        <td><?= e($record['roll_number']) ?></td>
                        <td class="fw-medium"><?= e($record['full_name']) ?></td>
                        <td><?= e($record['department']) ?></td>
                        <td><?= e($record['subject_name'] ?? 'General') ?></td>
                        <td><?= e($record['course_name']) ?></td>
                        <td><?= e($record['subject_name']) ?></td>
                        <td><span class="badge text-bg-light border text-capitalize"><?= e($record['status']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
