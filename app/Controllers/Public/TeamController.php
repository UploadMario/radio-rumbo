<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\View;
use App\Services\TeamService;
use Throwable;

final class TeamController
{
    public function __construct(private ?TeamService $team = null)
    {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function index(array $mock): void
    {
        try {
            $items = $this->teamService()->active();
        } catch (Throwable) {
            $items = [];
        }

        View::render('layouts.public', [
            'active' => 'equipo',
            'contentView' => 'team',
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Equipo',
            'teamMembers' => $items,
        ]);
    }

    private function teamService(): TeamService
    {
        if (!$this->team instanceof TeamService) {
            $this->team = new TeamService();
        }

        return $this->team;
    }
}
