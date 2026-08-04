<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Models\User;
use App\Services\AuthService;
use PDO;
use PHPUnit\Framework\TestCase;

final class AuthServiceTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        if (!extension_loaded('pdo_sqlite')) {
            self::markTestSkipped('pdo_sqlite no está disponible.');
        }

        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('CREATE TABLE roles (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL)');
        $this->pdo->exec(
            'CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                role_id INTEGER NOT NULL,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                password_hash TEXT NOT NULL,
                status TEXT NOT NULL,
                last_login_at TEXT NULL
            )'
        );
        $this->pdo->exec("INSERT INTO roles (name) VALUES ('Admin')");

        $statement = $this->pdo->prepare(
            'INSERT INTO users (role_id, name, email, password_hash, status)
             VALUES (1, :name, :email, :hash, :status)'
        );
        $statement->execute([
            'name' => 'Admin',
            'email' => 'admin@example.invalid',
            'hash' => password_hash('correct-password', PASSWORD_DEFAULT),
            'status' => 'active',
        ]);
    }

    public function testValidatesCorrectPassword(): void
    {
        $user = (new AuthService(new User($this->pdo)))->attempt('admin@example.invalid', 'correct-password');

        self::assertIsArray($user);
        self::assertSame('Admin', $user['role']);
    }

    public function testRejectsIncorrectPassword(): void
    {
        $user = (new AuthService(new User($this->pdo)))->attempt('admin@example.invalid', 'wrong-password');

        self::assertNull($user);
    }
}
