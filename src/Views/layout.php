<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'College ERP') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/dashboard">College ERP</a>
        <?php if (!empty($_SESSION['user'])): ?>
            <div class="d-flex align-items-center gap-2 text-white">
                <span><?= e($_SESSION['user']['name']) ?> (<?= e($_SESSION['user']['role_name']) ?>)</span>
                <form action="/logout" method="post" class="m-0">
                    <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                    <button class="btn btn-sm btn-light">Logout</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</nav>
<main class="container py-4">
    <?php require $templatePath; ?>
</main>
</body>
</html>
