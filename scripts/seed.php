<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/Core/helpers.php';
require dirname(__DIR__) . '/src/Core/Database.php';

use App\Core\Database;

$pdo = Database::connection();

$roles = ['super_admin', 'admin', 'faculty', 'student', 'accountant', 'librarian'];
$stmtRole = $pdo->prepare('INSERT IGNORE INTO roles (name) VALUES (:name)');
foreach ($roles as $role) {
    $stmtRole->execute(['name' => $role]);
}

$roleMap = $pdo->query('SELECT id, name FROM roles')->fetchAll();
$ids = [];
foreach ($roleMap as $r) {
    $ids[$r['name']] = (int)$r['id'];
}

$password = password_hash('Password@123', PASSWORD_DEFAULT);
$users = [
    ['Super Admin', 'superadmin@erp.local', 'super_admin'],
    ['Admin User', 'admin@erp.local', 'admin'],
    ['Faculty User', 'faculty@erp.local', 'faculty'],
    ['Student User', 'student@erp.local', 'student'],
    ['Accountant User', 'accounts@erp.local', 'accountant'],
    ['Librarian User', 'library@erp.local', 'librarian'],
];

$stmtUser = $pdo->prepare(
    'INSERT IGNORE INTO users (role_id, name, email, password_hash, is_active)
     VALUES (:role_id, :name, :email, :password_hash, 1)'
);

foreach ($users as [$name, $email, $role]) {
    $stmtUser->execute([
        'role_id' => $ids[$role],
        'name' => $name,
        'email' => $email,
        'password_hash' => $password,
    ]);
}

echo "Seeding complete\n";
