<?php

declare(strict_types=1);

use App\Core\Database;
use Dotenv\Dotenv;

$rootPath = dirname(__DIR__);
$autoloadPath = $rootPath . '/vendor/autoload.php';

if (!is_file($autoloadPath)) {
    fwrite(STDERR, "Ejecuta composer install antes de crear usuarios locales.\n");
    exit(1);
}

require_once $autoloadPath;

if (is_file($rootPath . '/.env')) {
    Dotenv::createImmutable($rootPath)->safeLoad();
}

if (($_ENV['APP_ENV'] ?? '') !== 'local') {
    fwrite(STDERR, "Abortado: create-local-users.php solo puede ejecutarse con APP_ENV=local.\n");
    exit(1);
}

$users = [
    [
        'role' => 'Super Admin',
        'name' => 'Super Admin Local',
        'email' => $_ENV['LOCAL_SUPERADMIN_EMAIL'] ?? 'superadmin.local@radiorumbo.test',
        'password' => $_ENV['LOCAL_SUPERADMIN_PASSWORD'] ?? '',
    ],
    [
        'role' => 'Admin',
        'name' => 'Admin Local',
        'email' => $_ENV['LOCAL_ADMIN_EMAIL'] ?? 'admin.local@radiorumbo.test',
        'password' => $_ENV['LOCAL_ADMIN_PASSWORD'] ?? '',
    ],
];

$pdo = Database::connection();
$roleStatement = $pdo->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
$userStatement = $pdo->prepare(
    'INSERT INTO users (role_id, name, email, password_hash, status)
     VALUES (:role_id, :name, :email, :password_hash, :status)
     ON DUPLICATE KEY UPDATE
        role_id = VALUES(role_id),
        name = VALUES(name),
        password_hash = VALUES(password_hash),
        status = VALUES(status)'
);

foreach ($users as $user) {
    if (!is_string($user['email']) || !is_string($user['password']) || $user['password'] === '') {
        fwrite(STDERR, "Faltan credenciales locales para {$user['role']}.\n");
        exit(1);
    }

    $roleStatement->execute(['name' => $user['role']]);
    $roleId = $roleStatement->fetchColumn();

    if ($roleId === false) {
        fwrite(STDERR, "No existe el rol {$user['role']}. Importa database/seed.sql primero.\n");
        exit(1);
    }

    $userStatement->execute([
        'role_id' => (int) $roleId,
        'name' => $user['name'],
        'email' => $user['email'],
        'password_hash' => password_hash($user['password'], PASSWORD_DEFAULT),
        'status' => 'active',
    ]);

    echo "Usuario local actualizado: {$user['email']} ({$user['role']})\n";
}

echo "Listo. Estas credenciales son solo para desarrollo local; no usarlas en producción.\n";
