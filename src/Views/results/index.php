<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 page-title mb-1">Result Entry</h1>
        <p class="page-subtitle mb-0">Enter marks by class and subject. Only students mapped to the selected subject are shown.</p>
    </div>
</div>

<div class="card-clean p-3 mb-3">
    <form method="get" action="/results" class="row g-2 align-items-end">
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
        <div class="col-md-3">
            <label class="form-label">Subject</label>
            <select class="form-select" name="subject_id">
                <option value="">Select Subject</option>
                <?php foreach ($subjects as $subject): ?>
                    <option value="<?= (int)$subject['id'] ?>" <?= (int)$subjectId === (int)$subject['id'] ? 'selected' : '' ?>>
                        <?= e($subject['subject_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Exam</label>
            <input class="form-control" name="exam_name" value="<?= e($examName) ?>">
        </div>
        <div class="col-md-12 col-lg-2">
            <button class="btn btn-soft w-100">Load</button>
        </div>
    </form>
</div>

<form method="post" action="/results/store" class="card-clean p-0 overflow-hidden">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <input type="hidden" name="department" value="<?= e($department) ?>">
    <input type="hidden" name="semester" value="<?= (int)$semester ?>">
    <input type="hidden" name="batch_year" value="<?= (int)$batchYear ?>">
    <input type="hidden" name="subject_id" value="<?= (int)$subjectId ?>">
    <input type="hidden" name="exam_name" value="<?= e($examName) ?>">

    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
            <tr>
                <th>Roll</th><th>Name</th><th>Department</th><th>Semester</th><th>Batch</th><th>Marks (0-100)</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($students)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">Select class + subject to load enrolled students.</td></tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= e($student['roll_number']) ?></td>
                        <td class="fw-medium"><?= e($student['full_name']) ?></td>
                        <td><?= e($student['department']) ?></td>
                        <td><?= (int)$student['semester'] ?></td>
                        <td><?= (int)$student['batch_year'] ?></td>
                        <td>
                            <input
                                class="form-control form-control-sm"
                                type="number"
                                min="0"
                                max="100"
                                step="0.01"
                                style="max-width: 170px;"
                                name="marks[<?= (int)$student['id'] ?>]"
                                value="<?= $student['marks'] !== null ? e((string)$student['marks']) : '' ?>"
                            >
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top bg-white">
        <button class="btn btn-primary" <?= empty($students) ? 'disabled' : '' ?>>Save Results</button>
    </div>
</form>
