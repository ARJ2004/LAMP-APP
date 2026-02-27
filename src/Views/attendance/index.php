<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 page-title mb-1">Mark Attendance</h1>
        <p class="page-subtitle mb-0">Mark daily attendance by student and save in one click.</p>
    </div>
    <a class="btn btn-outline-secondary" href="/attendance/history">View History</a>
</div>

<div class="card-clean p-3 mb-3">
    <form method="get" class="row g-2 align-items-end" action="/attendance">
        <div class="col-md-4 col-lg-3">
            <label class="form-label">Attendance Date</label>
            <input type="date" name="date" class="form-control" value="<?= e($date) ?>">
        </div>
        <div class="col-md-3 col-lg-2">
            <button class="btn btn-soft w-100">Load</button>
        </div>
    </form>
</div>

<form method="post" action="/attendance/mark" class="card-clean p-0 overflow-hidden">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="attendance_date" value="<?= e($date) ?>">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
            <tr>
                <th>Roll</th><th>Name</th><th>Department</th><th>Semester</th><th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">No students found. Add students first.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e($row['roll_number']) ?></td>
                        <td class="fw-medium"><?= e($row['full_name']) ?></td>
                        <td><?= e($row['department']) ?></td>
                        <td><?= (int)$row['semester'] ?></td>
                        <td>
                            <?php $current = $row['status'] ?? 'present'; ?>
                            <select name="status[<?= (int)$row['id'] ?>]" class="form-select form-select-sm" style="max-width: 150px;">
                                <option value="present" <?= $current === 'present' ? 'selected' : '' ?>>Present</option>
                                <option value="absent" <?= $current === 'absent' ? 'selected' : '' ?>>Absent</option>
                                <option value="late" <?= $current === 'late' ? 'selected' : '' ?>>Late</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top bg-white">
        <button class="btn btn-primary">Save Attendance</button>
    </div>
</form>
