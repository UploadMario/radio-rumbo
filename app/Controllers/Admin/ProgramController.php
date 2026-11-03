<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\AuditLogService;
use App\Services\ProgramService;
use App\Services\SessionService;
use Throwable;

final class ProgramController
{
    public function __construct(
        private ?SessionService $session = null,
        private ?ProgramService $programs = null,
        private ?AuditLogService $audit = null
    ) {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function index(array $mock): void
    {
        $user = $this->requireUser();

        try {
            $items = $this->programService()->allForAdmin();
            $error = null;
        } catch (Throwable) {
            $items = [];
            $error = 'No se pudo cargar la programacion.';
        }

        View::render('layouts.admin', [
            'active' => 'programacion',
            'contentView' => 'programs/index',
            'error' => $error,
            'flashSuccess' => $this->sessions()->flash('success'),
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Programacion',
            'programs' => $items,
            'user' => $user,
        ]);
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function create(array $mock): void
    {
        $this->renderForm($mock, $this->requireUser(), $this->blank(), [], '/admin/programs/create/', 'Crear programa');
    }

    /**
     * @param array<string, mixed> $mock
     * @param array<string, mixed> $input
     */
    public function store(array $mock, array $input): void
    {
        $user = $this->requireUser();
        $errors = $this->csrfErrors($input);

        if ($errors === []) {
            try {
                $result = $this->programService()->create($input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo guardar el programa.'],
                    'id' => null,
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->audit()->record($user['id'], 'program.create', 'program', $result['id']);
                $this->sessions()->flash('success', 'Programa creado correctamente.');
                $this->redirect('/admin/programs/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/programs/create/', 'Crear programa');
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function edit(array $mock, int $id): void
    {
        $this->renderForm(
            $mock,
            $this->requireUser(),
            $this->findOrRedirect($id),
            [],
            '/admin/programs/edit/?id=' . $id,
            'Editar programa'
        );
    }

    /**
     * @param array<string, mixed> $mock
     * @param array<string, mixed> $input
     */
    public function update(array $mock, int $id, array $input): void
    {
        $user = $this->requireUser();
        $this->findOrRedirect($id);
        $errors = $this->csrfErrors($input);

        if ($errors === []) {
            try {
                $result = $this->programService()->update($id, $input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo actualizar el programa.'],
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->audit()->record($user['id'], 'program.update', 'program', $id);
                $this->sessions()->flash('success', 'Programa actualizado correctamente.');
                $this->redirect('/admin/programs/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/programs/edit/?id=' . $id, 'Editar programa');
    }

    /**
     * @param array<string, mixed> $input
     */
    public function action(array $input): void
    {
        $user = $this->requireUser();
        if ($this->csrfErrors($input) === []) {
            $id = (int) ($input['id'] ?? 0);
            $action = (string) ($input['_action'] ?? '');
            try {
                if ($id > 0 && $action === 'toggle') {
                    $this->programService()->toggle($id);
                    $this->audit()->record($user['id'], 'program.toggle', 'program', $id);
                    $this->sessions()->flash('success', 'Estado del programa actualizado.');
                } elseif ($id > 0 && ($action === 'archive' || $action === 'delete')) {
                    $this->programService()->archive($id);
                    $this->audit()->record($user['id'], 'program.archive', 'program', $id);
                    $this->sessions()->flash('success', 'Programa archivado correctamente.');
                }
            } catch (Throwable) {
                $this->sessions()->flash('success', 'No se pudo completar la accion solicitada.');
            }
        }

        $this->redirect('/admin/programs/');
    }

    /**
     * @return array<string, mixed>
     */
    private function blank(): array
    {
        return [
            'name' => '',
            'slug' => '',
            'description' => '',
            'host_name' => '',
            'day_of_week' => 'monday',
            'start_time' => '06:00',
            'end_time' => '09:00',
            'category' => '',
            'is_active' => 1,
            'display_order' => 0,
        ];
    }

    /**
     * @param array<string, mixed> $mock
     * @param array{id:int,name:string,email:string,role:string} $user
     * @param array<string, mixed> $old
     * @param array<string, string> $errors
     */
    private function renderForm(
        array $mock,
        array $user,
        array $old,
        array $errors,
        string $action,
        string $heading
    ): void {
        View::render('layouts.admin', [
            'active' => 'programacion',
            'action' => $action,
            'contentView' => 'programs/form',
            'errors' => $errors,
            'heading' => $heading,
            'mock' => $mock,
            'old' => $old,
            'pageTitle' => 'Radio Rumbo - ' . $heading,
            'user' => $user,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function findOrRedirect(int $id): array
    {
        try {
            $item = $this->programService()->find($id);
        } catch (Throwable) {
            $item = null;
        }

        if ($item === null) {
            $this->sessions()->flash('success', 'El programa solicitado no esta disponible.');
            $this->redirect('/admin/programs/');
        }

        return $item;
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    private function csrfErrors(array $input): array
    {
        if (!SecurityHelper::verifyCsrfToken((string) ($input['_csrf_token'] ?? ''))) {
            return ['_form' => 'La sesion del formulario vencio. Intenta nuevamente.'];
        }

        return [];
    }

    /**
     * @return array{id:int,name:string,email:string,role:string}
     */
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

    private function programService(): ProgramService
    {
        if (!$this->programs instanceof ProgramService) {
            $this->programs = new ProgramService();
        }

        return $this->programs;
    }

    private function audit(): AuditLogService
    {
        if (!$this->audit instanceof AuditLogService) {
            $this->audit = new AuditLogService();
        }

        return $this->audit;
    }
}
