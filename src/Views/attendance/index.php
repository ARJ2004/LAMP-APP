<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 page-title mb-1">Mark Attendance</h1>
        <p class="page-subtitle mb-0">Mark attendance by class (department, semester, batch) and optional subject.</p>
        <p class="page-subtitle mb-0">Mark by course + subject so only registered students are called.</p>
    </div>
    <a class="btn btn-outline-secondary" href="/attendance/history">View History</a>
</div>

<div class="card-clean p-3 mb-3">
    <form method="get" class="row g-2 align-items-end" action="/attendance">
        <div class="col-md-3 col-lg-2">
            <label class="form-label">Attendance Date</label>
            <input type="date" name="date" class="form-control" value="<?= e($date) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Department</label>
            <input class="form-control" name="department" value="<?= e($department) ?>" placeholder="e.g. Computer Science">
        </div>
        <div class="col-md-2">
            <label class="form-label">Semester</label>
            <input type="number" min="1" max="12" class="form-control" name="semester" value="<?= $semester > 0 ? (int)$semester : '' ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Batch Year</label>
            <input type="number" min="2000" max="2100" class="form-control" name="batch_year" value="<?= $batchYear > 0 ? (int)$batchYear : '' ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Subject (optional)</label>
            <select name="subject_id" class="form-select">
                <option value="">General Class Attendance</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= (int)$subject['id'] ?>" <?= (int)$subjectId === (int)$subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['subject_name']) ?>
        <div class="col-md-3">
            <label class="form-label">Attendance Date</label>
            <input type="date" name="date" class="form-control" value="<?= e($date) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Course</label>
            <select name="course_id" class="form-select">
                <option value="">Select course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?= (int)$course['id'] ?>" <?= (int)$courseId === (int)$course['id'] ? 'selected' : '' ?>>
                        <?= e($course['code'] . ' - ' . $course['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Subject</label>
            <select name="subject_id" class="form-select">
                <option value="">Select subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= (int)$subject['id'] ?>" <?= (int)$subjectId === (int)$subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['code'] . ' - ' . $subject['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 col-lg-1">
        <div class="col-md-2">
            <button class="btn btn-soft w-100">Load</button>
        </div>
    </form>
</div>

<form method="post" action="/attendance/mark" class="card-clean p-0 overflow-hidden">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="attendance_date" value="<?= e($date) ?>">
    <input type="hidden" name="department" value="<?= e($department) ?>">
    <input type="hidden" name="semester" value="<?= (int)$semester ?>">
    <input type="hidden" name="batch_year" value="<?= (int)$batchYear ?>">
    <input type="hidden" name="course_id" value="<?= (int)$courseId ?>">
    <input type="hidden" name="subject_id" value="<?= (int)$subjectId ?>">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
            <tr>
                <th>Roll</th><th>Name</th><th>Department</th><th>Semester</th><th>Batch</th><th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No students found for selected class/subject.</td></tr>
            <?php if ((int)$subjectId === 0): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">Select course and subject to load class list.</td></tr>
            <?php elseif (empty($rows)): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">No enrolled students found for selected course.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= e($row['roll_number']) ?></td>
                        <td class="fw-medium"><?= e($row['full_name']) ?></td>
                        <td><?= e((string)$row['department']) ?></td>
                        <td><?= (int)$row['semester'] ?></td>
                        <td><?= (int)($row['batch_year'] ?? 0) ?></td>
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
        <button class="btn btn-primary" <?= (int)$subjectId === 0 ? 'disabled' : '' ?>>Save Attendance</button>
    </div>
</form>
