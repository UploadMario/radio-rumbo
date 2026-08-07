<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\View;
use App\Services\BannerService;
use App\Services\ContactMessageService;
use App\Services\NewsService;
use App\Services\ProgramService;
use App\Services\SessionService;
use App\Services\TeamService;
use App\Services\UrgentNoticeService;
use Throwable;

final class DashboardController
{
    public function __construct(
        private ?SessionService $session = null,
        private ?NewsService $news = null,
        private ?ProgramService $programs = null,
        private ?TeamService $team = null,
        private ?ContactMessageService $messages = null,
        private ?BannerService $banners = null,
        private ?UrgentNoticeService $notices = null
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

        try {
            $programCount = $this->programService()->activeCount();
        } catch (Throwable) {
            $programCount = 0;
        }

        try {
            $teamCount = $this->teamService()->activeCount();
        } catch (Throwable) {
            $teamCount = 0;
        }

        try {
            $newMessages = $this->messageService()->newCount();
        } catch (Throwable) {
            $newMessages = 0;
        }

        try {
            $bannerCount = $this->bannerService()->activeCount();
        } catch (Throwable) {
            $bannerCount = 0;
        }

        try {
            $noticeCount = $this->noticeService()->activeCount();
        } catch (Throwable) {
            $noticeCount = 0;
        }

        View::render('layouts.admin', [
            'active' => 'dashboard',
            'contentView' => 'dashboard',
            'mock' => $mock,
            'activeBanners' => $bannerCount,
            'activeNotices' => $noticeCount,
            'newMessages' => $newMessages,
            'newsCounts' => $counts,
            'pageTitle' => 'Radio Rumbo - Panel Administrativo',
            'programCount' => $programCount,
            'teamCount' => $teamCount,
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

    private function programService(): ProgramService
    {
        if (!$this->programs instanceof ProgramService) {
            $this->programs = new ProgramService();
        }

        return $this->programs;
    }

    private function teamService(): TeamService
    {
        if (!$this->team instanceof TeamService) {
            $this->team = new TeamService();
        }

        return $this->team;
    }

    private function messageService(): ContactMessageService
    {
        if (!$this->messages instanceof ContactMessageService) {
            $this->messages = new ContactMessageService();
        }

        return $this->messages;
    }

    private function bannerService(): BannerService
    {
        if (!$this->banners instanceof BannerService) {
            $this->banners = new BannerService();
        }

        return $this->banners;
    }

    private function noticeService(): UrgentNoticeService
    {
        if (!$this->notices instanceof UrgentNoticeService) {
            $this->notices = new UrgentNoticeService();
        }

        return $this->notices;
    }
}
