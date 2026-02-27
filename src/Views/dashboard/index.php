<section class="mb-4">
    <h1 class="page-title h3 mb-1">Dashboard</h1>
    <p class="page-subtitle mb-0">A minimal control center for core college ERP operations.</p>
</section>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card-clean p-3 h-100">
            <div class="metric-label">Total Students</div>
            <div class="metric-value"><?= (int)($studentCount ?? 0) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-clean p-3 h-100">
            <div class="metric-label">Role</div>
            <div class="metric-value text-capitalize"><?= e($user['role_name'] ?? 'guest') ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-clean p-3 h-100">
            <div class="metric-label">Module Count</div>
            <div class="metric-value"><?= count($modules ?? []) ?></div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card-clean p-3 h-100">
            <div class="metric-label">Platform</div>
            <div class="metric-value">LAMP</div>
        </div>
    </div>
</div>

<div class="card-clean p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between gap-2 mb-3 align-items-center">
        <h2 class="h5 mb-0">Modules</h2>
        <div class="d-flex gap-2">
            <a href="/students" class="btn btn-primary btn-sm">Students</a>
            <a href="/attendance" class="btn btn-outline-primary btn-sm">Attendance</a>
            <a href="/results" class="btn btn-outline-primary btn-sm">Results</a>
            <?php if (($user['role_name'] ?? '') === 'faculty'): ?>
                <a href="/teacher/dashboard" class="btn btn-outline-primary btn-sm">Teacher Dashboard</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <?php foreach (($modules ?? []) as $module): ?>
            <a class="feature-chip text-decoration-none" href="<?= e($module['route']) ?>">
                <span><?= e($module['name']) ?></span>
                <span class="badge text-bg-light border"><?= e($module['status']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
