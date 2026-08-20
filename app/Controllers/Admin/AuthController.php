<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\AuditLogService;
use App\Services\AuthService;
use App\Services\LoggerService;
use App\Services\LoginRateLimiterService;
use App\Services\SessionService;
use App\Validators\LoginValidator;
use Throwable;

final class AuthController
{
    public function __construct(
        private ?AuthService $auth = null,
        private ?SessionService $session = null,
        private ?LoginValidator $validator = null,
        private ?LoginRateLimiterService $limiter = null,
        private ?AuditLogService $audit = null,
        private ?LoggerService $logger = null
    ) {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function showLogin(array $mock): void
    {
        if ($this->sessions()->user() !== null) {
            $this->redirect('/admin/dashboard/');
        }

        $this->renderLogin($mock, [], ['identifier' => '']);
    }

    /**
     * @param array<string, mixed> $mock
     * @param array<string, mixed> $input
     */
    public function login(array $mock, array $input): void
    {
        $this->sessions()->start();
        $errors = $this->loginValidator()->validate($input);
        $identifier = trim((string) ($input['identifier'] ?? ''));
        $token = (string) ($input['_csrf_token'] ?? '');

        if (!SecurityHelper::verifyCsrfToken($token)) {
            $errors['_form'] = 'La sesión del formulario venció. Intenta nuevamente.';
        }

        if ($errors === [] && $this->rateLimiter()->tooManyAttempts($identifier)) {
            $errors['_form'] = 'No se pudo validar el acceso en este momento. Intenta nuevamente más tarde.';
        }

        if ($errors === []) {
            try {
                $user = $this->authService()->attempt(
                    $identifier,
                    (string) ($input['password'] ?? '')
                );
            } catch (Throwable $throwable) {
                $user = null;
                $errors['_form'] = 'No se pudo validar el acceso en este momento.';
                $this->logger()->error('Error interno durante login administrativo.', [
                    'identifier' => $identifier,
                    'exception' => $throwable,
                ]);
            }

            if ($user !== null) {
                $this->rateLimiter()->clear($identifier);
                $this->sessions()->login($user);
                $this->audit()->record($user['id'], 'admin.login', 'user', $user['id']);
                $this->redirect('/admin/dashboard/');
            }

            $this->rateLimiter()->recordFailure($identifier);
            $errors['_form'] ??= 'Credenciales inválidas o usuario inactivo.';
        }

        $this->renderLogin($mock, $errors, ['identifier' => (string) ($input['identifier'] ?? '')]);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function logout(array $input): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->redirect('/admin/dashboard/');
        }

        if (!SecurityHelper::verifyCsrfToken((string) ($input['_csrf_token'] ?? ''))) {
            $this->redirect('/admin/dashboard/');
        }

        $user = $this->sessions()->user();
        $userId = is_array($user) ? $user['id'] : null;
        $this->audit()->record($userId, 'admin.logout', 'user', $userId);
        $this->sessions()->logout();
        $this->redirect('/admin/login/');
    }

    /**
     * @param array<string, mixed> $mock
     * @param array<string, string> $errors
     * @param array<string, string> $old
     */
    private function renderLogin(array $mock, array $errors, array $old): void
    {
        $this->sessions()->start();

        View::render('layouts.admin', [
            'active' => 'login',
            'contentView' => 'login',
            'errors' => $errors,
            'mock' => $mock,
            'old' => $old,
            'pageTitle' => 'Radio Rumbo - Acceso administrativo',
        ]);
    }

    private function redirect(string $path): never
    {
        header('Location: ' . $path, true, 302);
        exit;
    }

    private function authService(): AuthService
    {
        if (!$this->auth instanceof AuthService) {
            $this->auth = new AuthService();
        }

        return $this->auth;
    }

    private function sessions(): SessionService
    {
        if (!$this->session instanceof SessionService) {
            $this->session = new SessionService();
        }

        return $this->session;
    }

    private function loginValidator(): LoginValidator
    {
        if (!$this->validator instanceof LoginValidator) {
            $this->validator = new LoginValidator();
        }

        return $this->validator;
    }

    private function rateLimiter(): LoginRateLimiterService
    {
        if (!$this->limiter instanceof LoginRateLimiterService) {
            $this->limiter = new LoginRateLimiterService();
        }

        return $this->limiter;
    }

    private function audit(): AuditLogService
    {
        if (!$this->audit instanceof AuditLogService) {
            $this->audit = new AuditLogService();
        }

        return $this->audit;
    }

    private function logger(): LoggerService
    {
        if (!$this->logger instanceof LoggerService) {
            $this->logger = new LoggerService();
        }

        return $this->logger;
    }
}
