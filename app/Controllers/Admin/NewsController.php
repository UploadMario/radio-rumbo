<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\NewsService;
use App\Services\SessionService;
use Throwable;

final class NewsController
{
    public function __construct(
        private ?SessionService $session = null,
        private ?NewsService $news = null
    ) {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function index(array $mock): void
    {
        $user = $this->requireUser();

        try {
            $items = $this->newsService()->allForAdmin();
            $error = null;
        } catch (Throwable) {
            $items = [];
            $error = 'No se pudo cargar la lista de noticias.';
        }

        View::render('layouts.admin', [
            'active' => 'noticias',
            'contentView' => 'news-index',
            'error' => $error,
            'flashSuccess' => $this->sessions()->flash('success'),
            'mock' => $mock,
            'newsItems' => $items,
            'pageTitle' => 'Radio Rumbo - Noticias',
            'user' => $user,
        ]);
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function create(array $mock): void
    {
        $user = $this->requireUser();

        $this->renderForm($mock, $user, [
            'title' => '',
            'slug' => '',
            'summary' => '',
            'body' => '',
            'category' => '',
            'status' => 'draft',
            'published_at' => '',
        ], [], '/admin/news/create/', 'Crear noticia');
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
                $result = $this->newsService()->create($input, $user['id']);
                $ok = $result['ok'];
                $resultErrors = $result['errors'];
                $resultData = $result['data'];
            } catch (Throwable) {
                $ok = false;
                $resultErrors = ['_form' => 'No se pudo guardar la noticia.'];
                $resultData = $input;
            }

            if ($ok === true) {
                $this->sessions()->flash('success', 'Noticia creada correctamente.');
                $this->redirect('/admin/news/');
            }

            $errors = array_merge($errors, $resultErrors);
            $input = $resultData;
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/news/create/', 'Crear noticia');
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function edit(array $mock, int $id): void
    {
        $user = $this->requireUser();
        $item = $this->findOrRedirect($id);

        $this->renderForm($mock, $user, $item, [], '/admin/news/edit/?id=' . $id, 'Editar noticia');
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
                $result = $this->newsService()->update($id, $input, $user['id']);
                $ok = $result['ok'];
                $resultErrors = $result['errors'];
                $resultData = $result['data'];
            } catch (Throwable) {
                $ok = false;
                $resultErrors = ['_form' => 'No se pudo actualizar la noticia.'];
                $resultData = $input;
            }

            if ($ok === true) {
                $this->sessions()->flash('success', 'Noticia actualizada correctamente.');
                $this->redirect('/admin/news/');
            }

            $errors = array_merge($errors, $resultErrors);
            $input = $resultData;
        }

        $this->renderForm($mock, $user, $input, $errors, '/admin/news/edit/?id=' . $id, 'Editar noticia');
    }

    /**
     * @param array<string, mixed> $input
     */
    public function archive(array $input): void
    {
        $user = $this->requireUser();

        if ($this->csrfErrors($input) === []) {
            $id = (int) ($input['id'] ?? 0);

            if ($id > 0) {
                try {
                    $this->newsService()->archive($id, $user['id']);
                    $this->sessions()->flash('success', 'Noticia archivada correctamente.');
                } catch (Throwable) {
                    $this->sessions()->flash('success', 'No se pudo archivar la noticia.');
                }
            }
        }

        $this->redirect('/admin/news/');
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
            'active' => 'noticias',
            'action' => $action,
            'contentView' => 'news-form',
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
            $item = $this->newsService()->find($id);
        } catch (Throwable) {
            $item = null;
        }

        if ($item === null) {
            $this->sessions()->flash('success', 'La noticia solicitada no está disponible.');
            $this->redirect('/admin/news/');
        }

        return $item;
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    private function csrfErrors(array $input): array
    {
        $token = (string) ($input['_csrf_token'] ?? '');

        if (!SecurityHelper::verifyCsrfToken($token)) {
            return ['_form' => 'La sesión del formulario venció. Intenta nuevamente.'];
        }

        return [];
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

    private function newsService(): NewsService
    {
        if (!$this->news instanceof NewsService) {
            $this->news = new NewsService();
        }

        return $this->news;
    }
}
