<h2 class="mb-3">Add Student</h2>
<form method="post" action="/students/store" class="card card-body">
    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
    <?php require __DIR__ . '/form-fields.php'; ?>
    <button class="btn btn-primary">Save</button>
</form>
