<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\ProgramScheduleService;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

final class ProgramScheduleServiceTest extends TestCase
{
    public function testDetectsProgramBeforeCurrentBlock(): void
    {
        $context = $this->service()->context($this->programs(), 'America/Lima', $this->time('2026-08-17 05:30'));

        self::assertSame('Programación en pausa', $context['current_program']['name']);
        self::assertSame('Rumbo Mañanero', $context['next_program']['name']);
    }

    public function testDetectsProgramDuringCurrentBlock(): void
    {
        $context = $this->service()->context($this->programs(), 'America/Lima', $this->time('2026-08-17 07:00'));

        self::assertSame('Rumbo Mañanero', $context['current_program']['name']);
        self::assertTrue($context['current_program']['live']);
    }

    public function testDetectsProgramBetweenBlocks(): void
    {
        $context = $this->service()->context($this->programs(), 'America/Lima', $this->time('2026-08-17 09:30'));

        self::assertSame('Programación en pausa', $context['current_program']['name']);
        self::assertSame('Mediodía Local', $context['next_program']['name']);
    }

    public function testDetectsAfterLastProgram(): void
    {
        $context = $this->service()->context($this->programs(), 'America/Lima', $this->time('2026-08-17 20:00'));

        self::assertSame('Programación en pausa', $context['current_program']['name']);
        self::assertNull($context['next_program']);
    }

    public function testDetectsOvernightProgramBeforeMidnight(): void
    {
        $context = $this->service()->context(
            $this->overnightPrograms(),
            'America/Lima',
            $this->time('2026-08-17 23:30')
        );

        self::assertSame('Noches de Radio', $context['current_program']['name']);
        self::assertTrue($context['current_program']['live']);
    }

    public function testDetectsOvernightProgramAfterMidnight(): void
    {
        $context = $this->service()->context(
            $this->overnightPrograms(),
            'America/Lima',
            $this->time('2026-08-18 01:30')
        );

        self::assertSame('Noches de Radio', $context['current_program']['name']);
        self::assertSame('Rumbo Mañanero', $context['next_program']['name']);
    }

    public function testIgnoresOtherDays(): void
    {
        $context = $this->service()->context($this->programs(), 'America/Lima', $this->time('2026-08-18 07:00'));

        self::assertSame([], $context['today_schedule']);
    }

    public function testNormalizesSpanishAndEnglishDays(): void
    {
        self::assertSame('miercoles', ProgramScheduleService::normalizeDay('Miércoles'));
        self::assertSame('martes', ProgramScheduleService::normalizeDay('tuesday'));
        self::assertNull(ProgramScheduleService::normalizeDay('feriado'));
    }

    public function testSelectedDayFallsBackToTodayInLima(): void
    {
        $selected = ProgramScheduleService::selectedDay(
            'no-valido',
            'America/Lima',
            $this->time('2026-09-01 08:00')
        );

        self::assertSame('martes', $selected);
    }

    public function testProgramsForDayUsesOneSelectedDay(): void
    {
        $programs = $this->service()->programsForDay($this->programs(), 'lunes');

        self::assertCount(2, $programs);
        self::assertSame('lunes', $programs[0]['day_of_week']);
    }

    public function testDefaultWeeklyFallbackCoversSevenDays(): void
    {
        $data = require dirname(__DIR__, 2) . '/config/default-content.php';
        $weekly = is_array($data['weekly_schedule'] ?? null) ? $data['weekly_schedule'] : [];
        $days = [];

        foreach ($weekly as $program) {
            if (is_array($program) && isset($program['day_of_week'])) {
                $days[(string) $program['day_of_week']] = true;
            }
        }

        self::assertSame(array_keys(ProgramScheduleService::DAY_LABELS), array_keys($days));
    }

    private function service(): ProgramScheduleService
    {
        return new ProgramScheduleService();
    }

    private function time(string $value): DateTimeImmutable
    {
        return new DateTimeImmutable($value, new DateTimeZone('America/Lima'));
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function programs(): array
    {
        return [
            [
                'name' => 'Rumbo Mañanero',
                'host_name' => 'Cabina Radio Rumbo',
                'day_of_week' => 'monday',
                'start_time' => '06:00',
                'end_time' => '09:00',
            ],
            [
                'name' => 'Mediodía Local',
                'host_name' => 'Cabina Radio Rumbo',
                'day_of_week' => 'monday',
                'start_time' => '12:00',
                'end_time' => '14:00',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function overnightPrograms(): array
    {
        return [
            [
                'name' => 'Noches de Radio',
                'host_name' => 'Equipo Radio Rumbo',
                'day_of_week' => 'monday',
                'start_time' => '22:00',
                'end_time' => '02:00',
            ],
            [
                'name' => 'Rumbo Mañanero',
                'host_name' => 'Equipo Radio Rumbo',
                'day_of_week' => 'tuesday',
                'start_time' => '06:00',
                'end_time' => '09:00',
            ],
        ];
    }
}
