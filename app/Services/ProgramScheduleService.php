<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;
use Throwable;

final class ProgramScheduleService
{
    public function __construct(private ?ProgramService $programs = null, private ?LoggerService $logger = null)
    {
    }

    /**
     * @param array<string, mixed> $defaultContent
     * @return array<string, mixed>
     */
    public function applyContext(array $defaultContent, string $timezone, ?DateTimeImmutable $now = null): array
    {
        $programs = $this->publicPrograms();
        $usingDatabase = $programs !== [];

        if (!$usingDatabase) {
            $fallback = is_array($defaultContent['today_schedule'] ?? null) ? $defaultContent['today_schedule'] : [];
            $programs = $this->normalizeSchedule($fallback, $timezone, $now);
        }

        $context = $this->context($programs, $timezone, $now);
        $defaultContent['today_schedule'] = $context['today_schedule'];
        $defaultContent['current_program'] = $context['current_program'];
        $defaultContent['next_program'] = $context['next_program'];
        $defaultContent['program_source'] = $usingDatabase ? 'database' : 'reference';

        return $defaultContent;
    }

    /** @return list<array<string, mixed>> */
    public function publicPrograms(): array
    {
        try {
            return $this->programService()->active();
        } catch (Throwable $throwable) {
            $this->logger()->warning('No se pudo cargar programación pública.', ['exception' => $throwable]);
            return [];
        }
    }

    /**
     * @param array<int, mixed> $items
     * @return list<array<string, mixed>>
     */
    public function normalizeSchedule(array $items, string $timezone, ?DateTimeImmutable $now = null): array
    {
        $clock = $now ?? new DateTimeImmutable('now', new DateTimeZone($timezone));
        $today = strtolower($clock->format('l'));
        $normalized = [];

        foreach ($items as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            $time = (string) ($item['time'] ?? $item['start_time'] ?? '00:00');
            $next = is_array($items[$index + 1] ?? null) ? $items[$index + 1] : null;
            $nextTime = $next !== null ? (string) ($next['time'] ?? $next['start_time'] ?? '') : '';

            $normalized[] = [
                'name' => (string) ($item['name'] ?? 'Programación en pausa'),
                'description' => (string) ($item['description'] ?? $item['summary'] ?? ''),
                'host_name' => (string) ($item['host_name'] ?? $item['host'] ?? 'Cabina Radio Rumbo'),
                'day_of_week' => (string) ($item['day_of_week'] ?? $today),
                'start_time' => substr($time, 0, 5),
                'end_time' => substr($nextTime !== '' ? $nextTime : (string) ($item['end_time'] ?? '23:59'), 0, 5),
                'category' => (string) ($item['category'] ?? 'Radio Rumbo'),
                'display_order' => (int) ($item['display_order'] ?? $index),
            ];
        }

        return $normalized;
    }

    /**
     * @param list<array<string, mixed>> $programs
     * @return array{current_program:array<string,mixed>,next_program:array<string,mixed>|null,today_schedule:list<array<string,mixed>>}
     */
    public function context(array $programs, string $timezone, ?DateTimeImmutable $now = null): array
    {
        $clock = $now ?? new DateTimeImmutable('now', new DateTimeZone($timezone));
        $today = strtolower($clock->format('l'));
        $currentTime = $clock->format('H:i');
        $todayPrograms = [];

        foreach ($programs as $program) {
            if ((string) ($program['day_of_week'] ?? $today) !== $today) {
                continue;
            }

            $todayPrograms[] = $this->programViewData($program, false);
        }

        usort(
            $todayPrograms,
            static fn (array $a, array $b): int => strcmp((string) $a['start_time'], (string) $b['start_time'])
        );

        $current = null;
        $next = null;

        foreach ($todayPrograms as $program) {
            if ((string) $program['start_time'] <= $currentTime && (string) $program['end_time'] > $currentTime) {
                $current = $program;
                continue;
            }

            if ((string) $program['start_time'] > $currentTime && $next === null) {
                $next = $program;
            }
        }

        if ($current === null) {
            $current = [
                'name' => 'Programación en pausa',
                'host' => 'Radio Rumbo',
                'schedule' => $next !== null ? 'Próximo bloque: ' . (string) $next['time'] : 'Fuera de horario',
                'category' => 'Señal fuera de horario',
                'status' => 'EN PAUSA',
                'summary' => 'La señal puede encontrarse fuera de horario o pendiente de activación desde cabina.',
                'time' => $next !== null ? (string) $next['time'] : '',
                'start_time' => $next !== null ? (string) $next['start_time'] : '',
                'end_time' => $next !== null ? (string) $next['end_time'] : '',
                'live' => false,
            ];
        } else {
            $current['live'] = true;
            $current['status'] = 'EN VIVO';
        }

        $todaySchedule = [];
        foreach ($todayPrograms as $program) {
            $program['live'] = (string) $program['name'] === (string) $current['name']
                && (string) $program['start_time'] === (string) ($current['start_time'] ?? '');
            $todaySchedule[] = $program;
        }

        return [
            'current_program' => $current,
            'next_program' => $next,
            'today_schedule' => $todaySchedule,
        ];
    }

    /**
     * @param array<string, mixed> $program
     * @return array<string, mixed>
     */
    public function programViewData(array $program, bool $live): array
    {
        $start = substr((string) ($program['start_time'] ?? $program['time'] ?? '00:00'), 0, 5);
        $end = substr((string) ($program['end_time'] ?? '23:59'), 0, 5);

        return [
            'name' => (string) ($program['name'] ?? 'Programación en pausa'),
            'host' => (string) ($program['host_name'] ?? $program['host'] ?? 'Cabina Radio Rumbo'),
            'schedule' => $start . ' - ' . $end,
            'category' => (string) ($program['category'] ?? 'Radio Rumbo'),
            'status' => $live ? 'EN VIVO' : 'Programado',
            'summary' => (string) ($program['description'] ?? $program['summary'] ?? ''),
            'time' => $start,
            'start_time' => $start,
            'end_time' => $end,
            'live' => $live,
        ];
    }

    private function programService(): ProgramService
    {
        if (!$this->programs instanceof ProgramService) {
            $this->programs = new ProgramService();
        }

        return $this->programs;
    }

    private function logger(): LoggerService
    {
        if (!$this->logger instanceof LoggerService) {
            $this->logger = new LoggerService();
        }

        return $this->logger;
    }
}
