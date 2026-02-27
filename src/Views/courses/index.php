<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 page-title mb-1">Courses & Subjects</h1>
        <p class="page-subtitle mb-0">Create courses and manage subject catalog.</p>
    </div>
</div>

<?php if ($isSuperAdmin): ?>
<div class="card-clean p-3 mb-3">
    <h2 class="h5 mb-3">Create Course</h2>
    <form method="post" action="/courses/store" class="row g-2">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <div class="col-md-2"><input required name="code" class="form-control" placeholder="Code"></div>
        <div class="col-md-4"><input required name="name" class="form-control" placeholder="Course name"></div>
        <div class="col-md-4"><input name="description" class="form-control" placeholder="Description"></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Create</button></div>
    </form>
</div>
<?php endif; ?>

<div class="card-clean p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
            <tr><th>Code</th><th>Course</th><th>Students</th><th>Subjects</th><th>Add Subject</th></tr>
            </thead>
            <tbody>
            <?php if (empty($courses)): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">No courses created yet.</td></tr>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= e($course['code']) ?></td>
                        <td>
                            <div class="fw-medium"><?= e($course['name']) ?></div>
                            <div class="small text-muted"><?= e((string)$course['description']) ?></div>
                        </td>
                        <td><?= (int)$course['enrolled_students'] ?></td>
                        <td><?= (int)$course['subject_count'] ?></td>
                        <td>
                            <?php if (in_array($_SESSION['user']['role_name'] ?? '', ['super_admin', 'admin'], true)): ?>
                                <form method="post" action="/courses/subjects/store?course_id=<?= (int)$course['id'] ?>" class="d-flex gap-2">
                                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                    <input required name="code" class="form-control form-control-sm" placeholder="Code" style="max-width: 95px;">
                                    <input required name="name" class="form-control form-control-sm" placeholder="Subject name">
                                    <button class="btn btn-soft btn-sm">Add</button>
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
