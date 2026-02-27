<div class="login-card card-clean p-4 p-md-5 mt-4">
    <div class="text-center mb-4">
        <h1 class="h4 page-title mb-1">Welcome back</h1>
        <p class="page-subtitle mb-0">Sign in to continue to College ERP.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="/login" novalidate>
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control form-control-lg" placeholder="you@college.edu" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
        </div>

        <button class="btn btn-primary w-100 btn-lg">Sign In</button>
    </form>
</div>
