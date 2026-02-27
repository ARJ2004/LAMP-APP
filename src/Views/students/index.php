<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Students</h2>
    <a href="/students/create" class="btn btn-success">Add Student</a>
</div>
<form class="row g-2 mb-3" method="get" action="/students">
    <div class="col-md-4"><input class="form-control" name="q" value="<?= e($query ?? '') ?>" placeholder="Search name or roll number"></div>
    <div class="col-md-2"><button class="btn btn-outline-primary w-100">Search</button></div>
</form>
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>#</th><th>Roll</th><th>Name</th><th>Email</th><th>Dept</th><th>Sem</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?= (int)$student['id'] ?></td>
                    <td><?= e($student['roll_number']) ?></td>
                    <td><?= e($student['full_name']) ?></td>
                    <td><?= e($student['email']) ?></td>
                    <td><?= e($student['department']) ?></td>
                    <td><?= (int)$student['semester'] ?></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="/students/edit?id=<?= (int)$student['id'] ?>">Edit</a>
                        <form class="d-inline" method="post" action="/students/delete?id=<?= (int)$student['id'] ?>">
                            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this student?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
