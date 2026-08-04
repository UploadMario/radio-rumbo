<?php

declare(strict_types=1);

namespace App\Services;

final class SessionService
{
    public function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }

    /**
     * @param array{id:int,name:string,email:string,role:string} $user
     */
    public function login(array $user): void
    {
        $this->start();
        session_regenerate_id(true);
        $_SESSION['user'] = $user;
        $_SESSION['last_activity'] = time();
    }

    /**
     * @return array{id:int,name:string,email:string,role:string}|null
     */
    public function user(): ?array
    {
        $this->start();
        $user = $_SESSION['user'] ?? null;

        if (!is_array($user)) {
            return null;
        }

        if (
            !isset($user['id'], $user['name'], $user['email'], $user['role'])
            || !is_numeric($user['id'])
            || !is_string($user['name'])
            || !is_string($user['email'])
            || !is_string($user['role'])
        ) {
            return null;
        }

        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    }

    public function logout(): void
    {
        $this->start();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                (bool) $params['secure'],
                (bool) $params['httponly']
            );
        }

        session_destroy();
    }

    public function flash(string $key, ?string $message = null): ?string
    {
        $this->start();

        if ($message !== null) {
            $_SESSION['_flash'][$key] = $message;
            return null;
        }

        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);

        return is_string($value) ? $value : null;
    }
}
