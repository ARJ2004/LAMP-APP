<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 page-title mb-1">Student Management</h1>
        <p class="page-subtitle mb-0">Search and maintain student records.</p>
    </div>
    <a href="/students/create" class="btn btn-primary">Add Student</a>
</div>

<div class="card-clean p-3 mb-3">
    <form class="row g-2" method="get" action="/students">
        <div class="col-md-8 col-lg-5">
            <input class="form-control" name="q" value="<?= e($query ?? '') ?>" placeholder="Search by name or roll number">
        </div>
        <div class="col-md-4 col-lg-2">
            <button class="btn btn-soft w-100">Search</button>
        </div>
        <div class="col-md-4 col-lg-2">
            <a class="btn btn-outline-secondary w-100" href="/students">Reset</a>
        </div>
    </form>
</div>

<div class="card-clean p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th>#</th><th>Roll</th><th>Name</th><th>Email</th><th>Department</th><th>Sem</th><th>Batch</th><th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($students)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No students found.</td></tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= (int)$student['id'] ?></td>
                        <td><?= e($student['roll_number']) ?></td>
                        <td class="fw-medium"><?= e($student['full_name']) ?></td>
                        <td><?= e($student['email']) ?></td>
                        <td><?= e($student['department']) ?></td>
                        <td><?= (int)$student['semester'] ?></td>
                        <td><?= (int)($student['batch_year'] ?? 0) ?></td>
                        <td class="text-end pe-3">
                            <a class="btn btn-sm btn-outline-primary" href="/students/edit?id=<?= (int)$student['id'] ?>">Edit</a>
                            <form class="d-inline" method="post" action="/students/delete?id=<?= (int)$student['id'] ?>">
                                <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this student?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
