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
                    <?php $role = $_SESSION['user']['role_name'] ?? ''; ?>
                    <div class="d-none d-md-flex gap-2 flex-wrap">
                        <?php if (in_array($role, ['super_admin', 'admin', 'faculty'], true)): ?>
                            <a href="/students" class="btn btn-sm btn-soft">Students</a>
                            <a href="/courses" class="btn btn-sm btn-soft">Courses</a>
                            <a href="/attendance" class="btn btn-sm btn-soft">Attendance</a>
                            <a href="/results" class="btn btn-sm btn-soft">Results</a>
                        <?php endif; ?>
                        <?php if ($role === 'faculty'): ?>
                            <a href="/teacher/dashboard" class="btn btn-sm btn-soft">Teacher Dashboard</a>
                        <?php endif; ?>
                        <?php if ($role === 'super_admin'): ?>
                            <a href="/students/register-account" class="btn btn-sm btn-soft">Register Student</a>
                            <a href="/billing" class="btn btn-sm btn-soft">Fee Structures</a>
                        <?php endif; ?>
                        <?php if ($role === 'student'): ?>
                            <a href="/courses/register" class="btn btn-sm btn-soft">Register Course</a>
                            <a href="/billing/my-bills" class="btn btn-sm btn-soft">My Bills</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="d-flex align-items-center gap-2">
                <?php if (!empty($_SESSION['user'])): ?>
                    <span class="small text-secondary">Hi, <?= e($_SESSION['user']['name']) ?> (<?= e($_SESSION['user']['role_name']) ?>)</span>
                    <form method="post" action="/logout" class="m-0">
                        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
                        <button class="btn btn-sm btn-outline-danger">Logout</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="container pb-5 flex-grow-1">
        <?php if ($flash = flash_get()): ?>
            <div class="alert alert-<?= e($flash['type']) ?> mt-3" role="alert"><?= e($flash['message']) ?></div>
        <?php endif; ?>
        <?php require $viewFile; ?>
    </main>
</div>
</body>
</html>
