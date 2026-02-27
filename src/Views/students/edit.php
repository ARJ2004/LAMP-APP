<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h3 page-title mb-1">Edit Student</h1>
        <p class="page-subtitle mb-0">Update student information.</p>
    </div>
    <a href="/students" class="btn btn-outline-secondary">Back</a>
</div>

<form method="post" action="/students/update?id=<?= (int)$student['id'] ?>" class="card-clean p-4">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <?php require __DIR__ . '/form-fields.php'; ?>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary">Update Student</button>
        <a href="/students" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
