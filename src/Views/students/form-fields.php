<?php $student = $student ?? []; ?>
<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Roll Number <span class="text-danger">*</span></label>
        <input class="form-control" name="roll_number" value="<?= e($student['roll_number'] ?? '') ?>" required>
    </div>
    <div class="col-md-8">
        <label class="form-label">Full Name <span class="text-danger">*</span></label>
        <input class="form-control" name="full_name" value="<?= e($student['full_name'] ?? '') ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?= e($student['email'] ?? '') ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Phone</label>
        <input class="form-control" name="phone" value="<?= e($student['phone'] ?? '') ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Department</label>
        <input class="form-control" name="department" value="<?= e($student['department'] ?? '') ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Semester</label>
        <input type="number" min="1" max="12" class="form-control" name="semester" value="<?= e((string)($student['semester'] ?? '1')) ?>">
    </div>
    <div class="col-md-4">
        <label class="form-label">Batch Year</label>
        <input type="number" min="2000" max="2100" class="form-control" name="batch_year" value="<?= e((string)($student['batch_year'] ?? date('Y'))) ?>">
    </div>
</div>
