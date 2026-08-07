<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\BannerService;
use App\Services\SessionService;
use Throwable;

final class BannerController
{
    public function __construct(private ?SessionService $session = null, private ?BannerService $banners = null)
    {
    }

    /** @param array<string, mixed> $mock */
    public function index(array $mock): void
    {
        $user = $this->requireUser();
        try {
            $items = $this->bannerService()->allForAdmin();
            $error = null;
        } catch (Throwable) {
            $items = [];
            $error = 'No se pudo cargar banners.';
        }

        View::render('layouts.admin', [
            'active' => 'banners',
            'banners' => $items,
            'contentView' => 'banners/index',
            'error' => $error,
            'flashSuccess' => $this->sessions()->flash('success'),
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Banners',
            'user' => $user,
        ]);
    }

    /** @param array<string, mixed> $mock */
    public function create(array $mock): void
    {
        $this->renderForm($mock, $this->requireUser(), $this->blank(), [], '/admin/banners/create/', 'Crear banner');
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
                $result = $this->bannerService()->create($input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo guardar el banner.'],
                    'id' => null,
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->sessions()->flash('success', 'Banner creado correctamente.');
                $this->redirect('/admin/banners/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/banners/create/', 'Crear banner');
    }

    /** @param array<string, mixed> $mock */
    public function edit(array $mock, int $id): void
    {
        $this->renderForm(
            $mock,
            $this->requireUser(),
            $this->findOrRedirect($id),
            [],
            '/admin/banners/edit/?id=' . $id,
            'Editar banner'
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
                $result = $this->bannerService()->update($id, $input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'errors' => ['_form' => 'No se pudo actualizar el banner.'],
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->sessions()->flash('success', 'Banner actualizado correctamente.');
                $this->redirect('/admin/banners/');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/banners/edit/?id=' . $id, 'Editar banner');
    }

    /** @param array<string, mixed> $input */
    public function action(array $input): void
    {
        $this->requireUser();
        if ($this->csrfErrors($input) === []) {
            $id = (int) ($input['id'] ?? 0);
            try {
                if ($id > 0 && (string) ($input['_action'] ?? '') === 'toggle') {
                    $this->bannerService()->toggle($id);
                    $this->sessions()->flash('success', 'Estado del banner actualizado.');
                } elseif ($id > 0 && (string) ($input['_action'] ?? '') === 'archive') {
                    $this->bannerService()->archive($id);
                    $this->sessions()->flash('success', 'Banner archivado.');
                }
            } catch (Throwable) {
                $this->sessions()->flash('success', 'No se pudo completar la accion.');
            }
        }

        $this->redirect('/admin/banners/');
    }

    /** @return array<string, mixed> */
    private function blank(): array
    {
        return [
            'title' => '',
            'subtitle' => '',
            'image_path' => '',
            'link_url' => '',
            'position' => 'home_middle',
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
            'active' => 'banners',
            'action' => $action,
            'contentView' => 'banners/form',
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
            $item = $this->bannerService()->find($id);
        } catch (Throwable) {
            $item = null;
        }

        if ($item === null) {
            $this->sessions()->flash('success', 'El banner solicitado no esta disponible.');
            $this->redirect('/admin/banners/');
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

    private function bannerService(): BannerService
    {
        if (!$this->banners instanceof BannerService) {
            $this->banners = new BannerService();
        }

        return $this->banners;
    }
}
