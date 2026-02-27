<div class="mb-3">
    <h1 class="h3 page-title mb-1">Register Student Account</h1>
    <p class="page-subtitle mb-0">Creates login account + student profile in one step.</p>
</div>

<form method="post" action="/students/register-account" class="card-clean p-3 row g-3">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

    <div class="col-md-4">
        <label class="form-label">Full Name</label>
        <input required name="full_name" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Email</label>
        <input required type="email" name="email" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Temporary Password</label>
        <input required type="password" name="password" class="form-control" minlength="8">
    </div>

    <div class="col-md-3">
        <label class="form-label">Roll Number</label>
        <input required name="roll_number" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Phone</label>
        <input name="phone" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Department</label>
        <input name="department" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Semester</label>
        <input type="number" min="1" max="12" name="semester" value="1" class="form-control">
    </div>

    <div class="col-md-4">
        <label class="form-label">Enroll in Course (optional)</label>
        <select name="course_id" class="form-select">
            <option value="">Select</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= (int)$course['id'] ?>"><?= e($course['code'] . ' - ' . $course['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <button class="btn btn-primary">Create Student Account</button>
    </div>
</form>
