<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\SessionService;
use App\Services\UrgentNoticeService;
use Throwable;

final class UrgentNoticeController
{
    public function __construct(
        private ?SessionService $session = null,
        private ?UrgentNoticeService $notices = null
    ) {
    }

    /** @param array<string, mixed> $mock */
    public function index(array $mock): void
    {
        $user = $this->requireUser();
        try {
            $items = $this->noticeService()->allForAdmin();
            $error = null;
        } catch (Throwable) {
            $items = [];
            $error = 'No se pudo cargar avisos urgentes.';
        }

        View::render('layouts.admin', [
            'active' => 'avisos',
            'contentView' => 'urgent-notices/index',
            'error' => $error,
            'flashSuccess' => $this->sessions()->flash('success'),
            'mock' => $mock,
            'notices' => $items,
            'pageTitle' => 'Radio Rumbo - Avisos urgentes',
            'user' => $user,
        ]);
    }

    /** @param array<string, mixed> $mock */
    public function create(array $mock): void
    {
        $this->renderForm(
            $mock,
            $this->requireUser(),
            $this->blank(),
            [],
            '/admin/urgent-notices/create/',
            'Crear aviso'
        );
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
                $result = $this->noticeService()->create($input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo guardar el aviso.'],
                    'id' => null,
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->sessions()->flash('success', 'Aviso creado correctamente.');
                $this->redirect('/admin/urgent-notices/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/urgent-notices/create/', 'Crear aviso');
    }

    /** @param array<string, mixed> $mock */
    public function edit(array $mock, int $id): void
    {
        $this->renderForm(
            $mock,
            $this->requireUser(),
            $this->findOrRedirect($id),
            [],
            '/admin/urgent-notices/edit/?id=' . $id,
            'Editar aviso'
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
                $result = $this->noticeService()->update($id, $input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo actualizar el aviso.'],
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->sessions()->flash('success', 'Aviso actualizado correctamente.');
                $this->redirect('/admin/urgent-notices/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/urgent-notices/edit/?id=' . $id, 'Editar aviso');
    }

    /** @param array<string, mixed> $input */
    public function action(array $input): void
    {
        $this->requireUser();
        if ($this->csrfErrors($input) === []) {
            $id = (int) ($input['id'] ?? 0);
            $action = (string) ($input['_action'] ?? '');
            try {
                if ($id > 0 && $action === 'toggle') {
                    $this->noticeService()->toggle($id);
                    $this->sessions()->flash('success', 'Estado del aviso actualizado.');
                } elseif ($id > 0 && $action === 'archive') {
                    $this->noticeService()->archive($id);
                    $this->sessions()->flash('success', 'Aviso archivado.');
                }
            } catch (Throwable) {
                $this->sessions()->flash('success', 'No se pudo completar la accion.');
            }
        }

        $this->redirect('/admin/urgent-notices/');
    }

    /** @return array<string, mixed> */
    private function blank(): array
    {
        return [
            'title' => '',
            'message' => '',
            'type' => 'info',
            'status' => 'draft',
            'start_at' => '',
            'end_at' => '',
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
            'active' => 'avisos',
            'action' => $action,
            'contentView' => 'urgent-notices/form',
            'errors' => $errors,
            'heading' => $heading,
            'mock' => $mock,
            'old' => $old,
            'pageTitle' => 'Radio Rumbo - ' . $heading,
            'user' => $user,
        ]);
    }

    /** @return array<string, mixed> */
    private function findOrRedirect(int $id): array
    {
        try {
            $item = $this->noticeService()->find($id);
        } catch (Throwable) {
            $item = null;
        }

        if ($item === null) {
            $this->sessions()->flash('success', 'El aviso solicitado no esta disponible.');
            $this->redirect('/admin/urgent-notices/');
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

    private function noticeService(): UrgentNoticeService
    {
        if (!$this->notices instanceof UrgentNoticeService) {
            $this->notices = new UrgentNoticeService();
        }

        return $this->notices;
    }
}
