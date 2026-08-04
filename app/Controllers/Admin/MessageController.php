<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\ContactMessageService;
use App\Services\SessionService;
use Throwable;

final class MessageController
{
    public function __construct(
        private ?SessionService $session = null,
        private ?ContactMessageService $messages = null
    ) {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function index(array $mock): void
    {
        $user = $this->requireUser();
        $status = is_string($_GET['status'] ?? null) ? $_GET['status'] : null;

        try {
            $items = $this->messageService()->allForAdmin($status);
            $error = null;
        } catch (Throwable) {
            $items = [];
            $error = 'No se pudo cargar la bandeja de mensajes.';
        }

        View::render('layouts.admin', [
            'active' => 'mensajes',
            'contentView' => 'messages/index',
            'error' => $error,
            'flashSuccess' => $this->sessions()->flash('success'),
            'messages' => $items,
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Mensajes',
            'statusFilter' => $status,
            'user' => $user,
        ]);
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function view(array $mock, int $id): void
    {
        $user = $this->requireUser();
        $message = $this->findOrRedirect($id);

        View::render('layouts.admin', [
            'active' => 'mensajes',
            'contentView' => 'messages/view',
            'message' => $message,
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Mensaje',
            'user' => $user,
        ]);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function action(array $input): void
    {
        $this->requireUser();
        if (!SecurityHelper::verifyCsrfToken((string) ($input['_csrf_token'] ?? ''))) {
            $this->redirect('/admin/messages/');
        }

        $id = (int) ($input['id'] ?? 0);
        $action = (string) ($input['_action'] ?? '');

        try {
            if ($id > 0 && $action === 'mark-read') {
                $this->messageService()->markRead($id);
                $this->sessions()->flash('success', 'Mensaje marcado como leido.');
            } elseif ($id > 0 && $action === 'archive') {
                $this->messageService()->archive($id);
                $this->sessions()->flash('success', 'Mensaje archivado.');
            }
        } catch (Throwable) {
            $this->sessions()->flash('success', 'No se pudo actualizar el mensaje.');
        }

        $this->redirect('/admin/messages/');
    }

    /**
     * @return array<string, mixed>
     */
    private function findOrRedirect(int $id): array
    {
        try {
            $message = $this->messageService()->find($id);
        } catch (Throwable) {
            $message = null;
        }

        if ($message === null) {
            $this->sessions()->flash('success', 'El mensaje solicitado no esta disponible.');
            $this->redirect('/admin/messages/');
        }

        return $message;
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

    private function messageService(): ContactMessageService
    {
        if (!$this->messages instanceof ContactMessageService) {
            $this->messages = new ContactMessageService();
        }

        return $this->messages;
    }
}
