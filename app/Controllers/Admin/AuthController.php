<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\AuthService;
use App\Services\SessionService;
use App\Validators\LoginValidator;
use Throwable;

final class AuthController
{
    public function __construct(
        private ?AuthService $auth = null,
        private ?SessionService $session = null,
        private ?LoginValidator $validator = null
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
        $token = (string) ($input['_csrf_token'] ?? '');

        if (!SecurityHelper::verifyCsrfToken($token)) {
            $errors['_form'] = 'La sesión del formulario venció. Intenta nuevamente.';
        }

        if ($errors === []) {
            try {
                $user = $this->authService()->attempt(
                    trim((string) ($input['identifier'] ?? '')),
                    (string) ($input['password'] ?? '')
                );
            } catch (Throwable) {
                $user = null;
                $errors['_form'] = 'No se pudo validar el acceso en este momento.';
            }

            if ($user !== null) {
                $this->sessions()->login($user);
                $this->redirect('/admin/dashboard/');
            }

            $errors['_form'] ??= 'Credenciales inválidas o usuario inactivo.';
        }

        $this->renderLogin($mock, $errors, ['identifier' => (string) ($input['identifier'] ?? '')]);
    }

    public function logout(): void
    {
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
}
