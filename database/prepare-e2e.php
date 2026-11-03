<?php

declare(strict_types=1);

use App\Core\Database;
use Dotenv\Dotenv;

$rootPath = dirname(__DIR__);
$autoloadPath = $rootPath . '/vendor/autoload.php';

if (!is_file($autoloadPath)) {
    fwrite(STDERR, "Falta vendor/autoload.php. Ejecuta composer install.\n");
    exit(1);
}

require_once $autoloadPath;

if (class_exists(Dotenv::class) && is_file($rootPath . '/.env')) {
    Dotenv::createImmutable($rootPath)->safeLoad();
}

$env = static function (string $key, string $default = ''): string {
    foreach ([getenv($key), $_ENV[$key] ?? null, $_SERVER[$key] ?? null] as $value) {
        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }
    }

    return $default;
};

$appEnvironment = strtolower($env('APP_ENV'));
$databaseName = $env('DB_DATABASE');

if ($appEnvironment !== 'e2e' || $databaseName !== 'radio_rumbo_e2e') {
    fwrite(STDERR, "Abortado: el preparador exige APP_ENV=e2e y DB_DATABASE=radio_rumbo_e2e.\n");
    exit(1);
}

$required = [
    'E2E_SUPERADMIN_EMAIL',
    'E2E_SUPERADMIN_PASSWORD',
    'E2E_ADMIN_EMAIL',
    'E2E_ADMIN_PASSWORD',
];

foreach ($required as $key) {
    if ($env($key) === '') {
        fwrite(STDERR, "Falta la variable externa {$key}.\n");
        exit(1);
    }
}

try {
    $pdo = Database::connection();
    $connectedDatabase = $pdo->query('SELECT DATABASE()')->fetchColumn();
} catch (Throwable) {
    fwrite(STDERR, "No se pudo conectar a la base E2E. Verifica MySQL/MariaDB y las variables externas.\n");
    exit(1);
}

if ($connectedDatabase !== 'radio_rumbo_e2e') {
    fwrite(STDERR, "Abortado: la conexión no apunta a radio_rumbo_e2e.\n");
    exit(1);
}

$tables = $pdo->query('SHOW TABLES')->fetchAll(\PDO::FETCH_COLUMN);
if ($tables !== []) {
    fwrite(STDERR, "Abortado: radio_rumbo_e2e no está vacía; no se ejecutan escrituras destructivas ni se reutiliza una base poblada.\n");
    exit(1);
}

$splitSqlStatements = static function (string $sql): array {
    $statements = [];
    $buffer = '';
    $quote = null;
    $length = strlen($sql);

    for ($index = 0; $index < $length; $index++) {
        $character = $sql[$index];
        $nextCharacter = $index + 1 < $length ? $sql[$index + 1] : '';

        if ($quote !== null) {
            $buffer .= $character;
            if ($character === '\\' && $nextCharacter !== '') {
                $buffer .= $nextCharacter;
                $index++;
                continue;
            }
            if ($character === $quote && $nextCharacter === $quote) {
                $buffer .= $nextCharacter;
                $index++;
                continue;
            }
            if ($character === $quote) {
                $quote = null;
            }
            continue;
        }

        if ($character === "'" || $character === '"' || $character === '`') {
            $quote = $character;
            $buffer .= $character;
            continue;
        }

        if ($character === ';') {
            if (trim($buffer) !== '') {
                $statements[] = trim($buffer);
            }
            $buffer = '';
            continue;
        }

        $buffer .= $character;
    }

    if (trim($buffer) !== '') {
        $statements[] = trim($buffer);
    }

    return $statements;
};

$executeSqlFile = static function (PDO $connection, string $path, string $phase) use ($splitSqlStatements): void {
    $sql = file_get_contents($path);
    if ($sql === false) {
        throw new RuntimeException('No se pudo leer ' . $path);
    }

    $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
    foreach ($splitSqlStatements($sql) as $statementIndex => $statement) {
        try {
            $connection->exec($statement);
        } catch (Throwable $exception) {
            throw new RuntimeException(
                sprintf('%s, sentencia %d: %s', $phase, $statementIndex + 1, $exception->getMessage()),
                (int) $exception->getCode(),
                $exception
            );
        }
    }
};

try {
    $executeSqlFile($pdo, $rootPath . '/database/schema.sql', 'schema.sql');
    // schema.sql representa el esquema final; las migraciones historicas ya estan reflejadas.
    $executeSqlFile($pdo, $rootPath . '/database/seed.sql', 'seed.sql');

    $roles = ['Super Admin', 'Admin'];
    $roleStatement = $pdo->prepare('SELECT id FROM roles WHERE name = :name LIMIT 1');
    $userStatement = $pdo->prepare(
        'INSERT INTO users (role_id, name, email, password_hash, status)
         VALUES (:role_id, :name, :email, :password_hash, :status)'
    );

    $fixtures = [
        ['role' => $roles[0], 'name' => 'E2E Super Admin', 'email' => $env('E2E_SUPERADMIN_EMAIL'), 'password' => $env('E2E_SUPERADMIN_PASSWORD')],
        ['role' => $roles[1], 'name' => 'E2E Admin', 'email' => $env('E2E_ADMIN_EMAIL'), 'password' => $env('E2E_ADMIN_PASSWORD')],
    ];

    foreach ($fixtures as $fixture) {
        $roleStatement->execute(['name' => $fixture['role']]);
        $roleId = $roleStatement->fetchColumn();
        if ($roleId === false) {
            throw new RuntimeException('Falta un rol requerido en seed.sql.');
        }

        $userStatement->execute([
            'role_id' => (int) $roleId,
            'name' => $fixture['name'],
            'email' => $fixture['email'],
            'password_hash' => password_hash($fixture['password'], PASSWORD_DEFAULT),
            'status' => 'active',
        ]);
    }
} catch (Throwable $exception) {
    fwrite(STDERR, "No se pudo preparar la base E2E: " . $exception->getMessage() . "\n");
    fwrite(STDERR, "La preparación no elimina ni revierte tablas creadas previamente; verifica la base E2E antes de reintentar.\n");
    exit(1);
}

echo "Base E2E preparada: radio_rumbo_e2e\n";
