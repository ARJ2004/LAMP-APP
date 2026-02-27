<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? 'College ERP') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/app.css" rel="stylesheet">
</head>
<body>
<div class="app-shell d-flex flex-column">
    <nav class="topbar py-3">
        <div class="container d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="d-flex align-items-center gap-3">
                <a class="navbar-brand fw-semibold d-flex align-items-center gap-2 mb-0" href="/dashboard">
                    <span class="brand-dot"></span>
                    College ERP
                </a>
                <?php if (!empty($_SESSION['user'])): ?>
                    <div class="d-none d-md-flex gap-2">
                        <a href="/students" class="btn btn-sm btn-soft">Students</a>
                        <a href="/attendance" class="btn btn-sm btn-soft">Attendance</a>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($_SESSION['user'])): ?>
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted d-none d-md-inline">Welcome, <?= e($_SESSION['user']['name']) ?></span>
                    <span class="badge badge-role rounded-pill"><?= e($_SESSION['user']['role_name']) ?></span>
                    <form action="/logout" method="post" class="m-0">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <button class="btn btn-sm btn-outline-secondary">Logout</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </nav>
    <main class="container py-4 flex-grow-1">
        <?php require $templatePath; ?>
    </main>
</div>
</body>
</html>
