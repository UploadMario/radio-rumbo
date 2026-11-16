<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\View;
use App\Services\ProgramService;
use App\Services\ProgramScheduleService;
use Throwable;

final class ProgramController
{
    public function __construct(
        private ?ProgramService $programs = null,
        private ?ProgramScheduleService $schedule = null
    ) {
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

        $selectedDay = ProgramScheduleService::selectedDay(
            is_string($_GET['dia'] ?? null) ? $_GET['dia'] : null,
            'America/Lima'
        );
        $todayKey = ProgramScheduleService::todayKey('America/Lima');
        $dayPrograms = $this->scheduleService()->programsForDay($items, $selectedDay);

        View::render('layouts.public', [
            'active' => 'programacion',
            'contentView' => 'schedule',
            'mock' => $mock,
            'pageTitle' => 'Radio Rumbo - Programación',
            'programs' => $dayPrograms,
            'selectedDay' => $selectedDay,
            'todayKey' => $todayKey,
            'dayLabels' => ProgramScheduleService::DAY_LABELS,
        ]);
    }

    private function programService(): ProgramService
    {
        if (!$this->programs instanceof ProgramService) {
            $this->programs = new ProgramService();
        }

        return $this->programs;
    }

    private function scheduleService(): ProgramScheduleService
    {
        if (!$this->schedule instanceof ProgramScheduleService) {
            $this->schedule = new ProgramScheduleService($this->programService());
        }

        return $this->schedule;
    }
}
