<div class="mb-3">
    <h1 class="h3 page-title mb-1">Register for Course</h1>
    <p class="page-subtitle mb-0">Choose a course to activate your class and billing workflow.</p>
</div>

<div class="card-clean p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="bg-light">
                <tr><th>Code</th><th>Course</th><th>Description</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php if (empty($courses)): ?>
                <tr><td colspan="5" class="text-center py-4 text-muted">No courses available.</td></tr>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?= e($course['code']) ?></td>
                        <td class="fw-medium"><?= e($course['name']) ?></td>
                        <td><?= e((string)$course['description']) ?></td>
                        <td>
                            <?php if ((int)$course['is_registered'] === 1): ?>
                                <span class="badge text-bg-success">Registered</span>
                            <?php else: ?>
                                <span class="badge text-bg-light border">Not Registered</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ((int)$course['is_registered'] === 0): ?>
                                <form method="post" action="/courses/register" class="m-0">
                                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="course_id" value="<?= (int)$course['id'] ?>">
                                    <button class="btn btn-primary btn-sm">Register</button>
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
