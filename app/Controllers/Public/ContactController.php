<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\View;
use App\Helpers\SecurityHelper;
use App\Services\ContactMessageService;
use App\Services\SessionService;
use Throwable;

final class ContactController
{
    public function __construct(
        private ?ContactMessageService $messages = null,
        private ?SessionService $session = null
    ) {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function index(array $mock): void
    {
        $this->render($mock, [], [], $this->sessions()->flash('contact_success'));
    }

    /**
     * @param array<string, mixed> $mock
     * @param array<string, mixed> $input
     */
    public function store(array $mock, array $input): void
    {
        $errors = [];

        if (!SecurityHelper::verifyCsrfToken((string) ($input['_csrf_token'] ?? ''))) {
            $errors['_form'] = 'La sesion del formulario vencio. Intenta nuevamente.';
        }

        if ($errors === []) {
            try {
                $result = $this->messageService()->create($input);
            } catch (Throwable) {
                $result = [
                    'ok' => false,
                    'spam' => false,
                    'errors' => ['_form' => 'No se pudo guardar el mensaje.'],
                    'id' => null,
                    'data' => $input,
                ];
            }

            if ($result['ok'] === true) {
                $this->sessions()->flash('contact_success', 'Gracias. Tu mensaje fue recibido por Radio Rumbo.');
                $this->redirect('/publicidad-contacto/#contacto');
            }

            $errors = array_merge($errors, $result['errors']);
            $input = $result['data'];
        }

        $this->render($mock, $input, $errors, null);
    }

    /**
     * @param array<string, mixed> $mock
     * @param array<string, mixed> $old
     * @param array<string, string> $errors
     */
    private function render(array $mock, array $old, array $errors, ?string $success): void
    {
        View::render('layouts.public', [
            'active' => 'publicidad-contacto',
            'contentView' => 'advertising-contact',
            'contactErrors' => $errors,
            'contactOld' => $old,
            'contactSuccess' => $success,
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Publicidad y Contacto',
        ]);
    }

    private function redirect(string $path): never
    {
        header('Location: ' . $path, true, 302);
        exit;
    }

    private function messageService(): ContactMessageService
    {
        if (!$this->messages instanceof ContactMessageService) {
            $this->messages = new ContactMessageService();
        }

        return $this->messages;
    }

    private function sessions(): SessionService
    {
        if (!$this->session instanceof SessionService) {
            $this->session = new SessionService();
        }

        return $this->session;
    }
}
