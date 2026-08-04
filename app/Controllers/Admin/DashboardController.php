<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Services\NewsService;
use App\Services\SessionService;
use Throwable;

final class DashboardController
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
            $counts = $this->newsService()->counts();
        } catch (Throwable) {
            $counts = ['total' => 0, 'published' => 0, 'draft' => 0];
        }

        View::render('layouts.admin', [
            'active' => 'dashboard',
            'contentView' => 'dashboard',
            'mock' => $mock,
            'newsCounts' => $counts,
            'pageTitle' => 'Radio Rumbo - Panel Administrativo',
            'user' => $user,
        ]);
    }

    /**
     * @return array{id:int,name:string,email:string,role:string}
     */
    private function requireUser(): array
    {
        $user = $this->sessions()->user();

        if ($user === null) {
            header('Location: /admin/login/', true, 302);
            exit;
        }

        return $user;
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
