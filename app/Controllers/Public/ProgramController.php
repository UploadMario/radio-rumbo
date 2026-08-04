<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\View;
use App\Services\ProgramService;
use Throwable;

final class ProgramController
{
    public function __construct(private ?ProgramService $programs = null)
    {
    }

    /**
     * @param array<string, mixed> $mock
     */
    public function index(array $mock): void
    {
        try {
            $items = $this->programService()->active();
        } catch (Throwable) {
            $items = [];
        }

        View::render('layouts.public', [
            'active' => 'programacion',
            'contentView' => 'schedule',
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Programacion',
            'programs' => $items,
        ]);
    }

    private function programService(): ProgramService
    {
        if (!$this->programs instanceof ProgramService) {
            $this->programs = new ProgramService();
        }

        return $this->programs;
    }
}
