<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\AuditLogService;
use App\Services\SessionService;
use App\Services\TeamService;
use Throwable;

final class TeamController
{
    public function __construct(
        private ?SessionService $session = null,
        private ?TeamService $team = null,
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
            $items = $this->teamService()->allForAdmin();
            $error = null;
        } catch (Throwable) {
            $items = [];
            $error = 'No se pudo cargar el equipo.';
        }

        View::render('layouts.admin', [
            'active' => 'equipo',
            'contentView' => 'team/index',
            'error' => $error,
            'flashSuccess' => $this->sessions()->flash('success'),
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Equipo',
            'teamMembers' => $items,
            'user' => $user,
        ]);
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function create(array $mock): void
    {
        $this->renderForm($mock, $this->requireUser(), $this->blank(), [], '/admin/team/create/', 'Crear integrante');
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
                $result = $this->teamService()->create($input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo guardar el integrante.'],
                    'id' => null,
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->audit()->record($user['id'], 'team.create', 'team_member', $result['id']);
                $this->sessions()->flash('success', 'Integrante creado correctamente.');
                $this->redirect('/admin/team/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/team/create/', 'Crear integrante');
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
            '/admin/team/edit/?id=' . $id,
            'Editar integrante'
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
                $result = $this->teamService()->update($id, $input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo actualizar el integrante.'],
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->audit()->record($user['id'], 'team.update', 'team_member', $id);
                $this->sessions()->flash('success', 'Integrante actualizado correctamente.');
                $this->redirect('/admin/team/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/team/edit/?id=' . $id, 'Editar integrante');
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
                    $this->teamService()->toggle($id);
                    $this->audit()->record($user['id'], 'team.toggle', 'team_member', $id);
                    $this->sessions()->flash('success', 'Estado del integrante actualizado.');
                } elseif ($id > 0 && $action === 'delete') {
                    $this->teamService()->delete($id);
                    $this->audit()->record($user['id'], 'team.delete', 'team_member', $id);
                    $this->sessions()->flash('success', 'Integrante eliminado correctamente.');
                }
            } catch (Throwable) {
                $this->sessions()->flash('success', 'No se pudo completar la accion solicitada.');
            }
        }

        $this->redirect('/admin/team/');
    }

    /**
     * @return array<string, mixed>
     */
    private function blank(): array
    {
        return [
            'name' => '',
            'role' => '',
            'program_name' => '',
            'bio' => '',
            'photo_path' => '',
            'facebook_url' => '',
            'instagram_url' => '',
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
            'active' => 'equipo',
            'action' => $action,
            'contentView' => 'team/form',
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
            $item = $this->teamService()->find($id);
        } catch (Throwable) {
            $item = null;
        }

        if ($item === null) {
            $this->sessions()->flash('success', 'El integrante solicitado no esta disponible.');
            $this->redirect('/admin/team/');
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

    private function teamService(): TeamService
    {
        if (!$this->team instanceof TeamService) {
            $this->team = new TeamService();
        }

        return $this->team;
    }

    private function audit(): AuditLogService
    {
        if (!$this->audit instanceof AuditLogService) {
            $this->audit = new AuditLogService();
        }

        return $this->audit;
    }
}
