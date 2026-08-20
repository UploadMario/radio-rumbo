<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Models\Role;
use App\Services\AuditLogService;
use App\Services\SessionService;
use App\Services\SettingService;
use Throwable;

final class SettingController
{
    public function __construct(
        private ?SessionService $session = null,
        private ?SettingService $settings = null,
        private ?AuditLogService $audit = null
    ) {
    }

    /** @param array<string, mixed> $mock */
    public function index(array $mock): void
    {
        $user = $this->requireUser();
        try {
            $values = $this->settingService()->editableSettings();
            $error = null;
        } catch (Throwable) {
            $values = [];
            $error = 'No se pudo cargar la configuracion.';
        }

        $this->render($mock, $user, $values, [], $error, $this->sessions()->flash('success'));
    }

    /**
     * @param array<string, mixed> $mock
     * @param array<string, mixed> $input
     */
    public function update(array $mock, array $input): void
    {
        $user = $this->requireUser();
        $errors = [];

        if ($user['role'] !== Role::SUPER_ADMIN) {
            $errors['_form'] = 'Solo Super Admin puede modificar configuraciones criticas.';
        } elseif (!SecurityHelper::verifyCsrfToken((string) ($input['_csrf_token'] ?? ''))) {
            $errors['_form'] = 'La sesion del formulario vencio. Intenta nuevamente.';
        }

        if ($errors === []) {
            try {
                $result = $this->settingService()->updateEditable($input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo guardar la configuracion.'],
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->audit()->record($user['id'], 'settings.update', 'settings', null, [
                    'keys' => array_keys($result['data']),
                ]);
                $this->sessions()->flash('success', 'Configuracion actualizada correctamente.');
                $this->redirect('/admin/settings/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->render($mock, $user, $input, $errors, null, null);
    }

    /**
     * @param array<string, mixed> $mock
     * @param array{id:int,name:string,email:string,role:string} $user
     * @param array<string, mixed> $values
     * @param array<string, string> $errors
     */
    private function render(
        array $mock,
        array $user,
        array $values,
        array $errors,
        ?string $error,
        ?string $success
    ): void {
        View::render('layouts.admin', [
            'active' => 'configuracion',
            'contentView' => 'settings/index',
            'canEditSettings' => $user['role'] === Role::SUPER_ADMIN,
            'error' => $error,
            'errors' => $errors,
            'flashSuccess' => $success,
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Configuracion',
            'settingsValues' => $values,
            'user' => $user,
        ]);
    }

    /** @return array{id:int,name:string,email:string,role:string} */
    private function requireUser(): array
    {
        $user = $this->sessions()->user();
        if ($user === null) {
            $this->redirect('/admin/login/');
        }

        return $user;
    }

    private function redirect(string $path): never
    {
        header('Location: ' . $path, true, 302);
        exit;
    }

    private function sessions(): SessionService
    {
        if (!$this->session instanceof SessionService) {
            $this->session = new SessionService();
        }

        return $this->session;
    }

    private function settingService(): SettingService
    {
        if (!$this->settings instanceof SettingService) {
            $this->settings = new SettingService();
        }

        return $this->settings;
    }

    private function audit(): AuditLogService
    {
        if (!$this->audit instanceof AuditLogService) {
            $this->audit = new AuditLogService();
        }

        return $this->audit;
    }
}
