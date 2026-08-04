<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\SessionService;

final class AuthMiddleware
{
    public function __construct(private ?SessionService $session = null)
    {
    }

    public function handle(): bool
    {
        return $this->sessions()->user() !== null;
    }

    private function sessions(): SessionService
    {
        if (!$this->session instanceof SessionService) {
            $this->session = new SessionService();
        }

        return $this->session;
    }
}
