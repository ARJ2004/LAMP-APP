<h2 class="mb-3">Edit Student</h2>
<form method="post" action="/students/update?id=<?= (int)$student['id'] ?>" class="card card-body">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <?php require __DIR__ . '/form-fields.php'; ?>
    <button class="btn btn-primary">Update</button>
</form>
