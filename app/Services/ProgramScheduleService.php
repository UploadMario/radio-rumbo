<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;
use Throwable;

final class ProgramScheduleService
{
    /** @var array<string, string> */
    public const DAY_LABELS = [
        'lunes' => 'Lunes',
        'martes' => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves' => 'Jueves',
        'viernes' => 'Viernes',
        'sabado' => 'Sábado',
        'domingo' => 'Domingo',
    ];

    /** @var array<string, string> */
    private const ENGLISH_TO_SPANISH_DAYS = [
        'monday' => 'lunes',
        'tuesday' => 'martes',
        'wednesday' => 'miercoles',
        'thursday' => 'jueves',
        'friday' => 'viernes',
        'saturday' => 'sabado',
        'sunday' => 'domingo',
    ];

    /** @var list<string> */
    private const DAY_KEYS = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

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
            $weekly = is_array($defaultContent['weekly_schedule'] ?? null) ? $defaultContent['weekly_schedule'] : [];
            $fallback = $weekly !== [] ? $weekly : (is_array($defaultContent['today_schedule'] ?? null)
                ? $defaultContent['today_schedule']
                : []);
            $programs = $this->normalizeSchedule($fallback, $timezone, $now);
        }

        $context = $this->context($programs, $timezone, $now);
        if ($usingDatabase && $context['today_schedule'] === []) {
            $weekly = is_array($defaultContent['weekly_schedule'] ?? null) ? $defaultContent['weekly_schedule'] : [];
            $programs = $this->normalizeSchedule($weekly, $timezone, $now);
            $context = $this->context($programs, $timezone, $now);
            $usingDatabase = false;
        }

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
        $today = self::todayKey($timezone, $clock);
        $normalized = [];

        foreach ($items as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            $time = (string) ($item['time'] ?? $item['start_time'] ?? '00:00');
            $next = is_array($items[$index + 1] ?? null) ? $items[$index + 1] : null;
            $nextTime = $next !== null ? (string) ($next['time'] ?? $next['start_time'] ?? '') : '';

            $normalized[] = [
                'name' => $this->presentableProgramName((string) ($item['name'] ?? 'Programación en pausa')),
                'description' => $this->publicProgramSummary((string) ($item['description'] ?? $item['summary'] ?? '')),
                'host_name' => (string) ($item['host_name'] ?? $item['host'] ?? 'Cabina Radio Rumbo'),
                'day_of_week' => self::normalizeDay((string) ($item['day_of_week'] ?? $today)) ?? $today,
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
        $today = self::todayKey($timezone, $clock);
        $previousDay = self::previousDayKey($today);
        $todayPrograms = $this->programsForDay($programs, $today);
        $overnightPrograms = $this->overnightProgramsFromPreviousDay($programs, $previousDay);
        $currentTime = $clock->format('H:i');

        $current = null;
        $next = null;

        foreach ($overnightPrograms as $program) {
            if ($this->isCurrentProgram((string) $program['start_time'], (string) $program['end_time'], $currentTime)) {
                $current = $program;
                break;
            }
        }

        foreach ($todayPrograms as $program) {
            $isCurrent = $this->isCurrentProgram(
                (string) $program['start_time'],
                (string) $program['end_time'],
                $currentTime
            );

            if ($current === null && $isCurrent) {
                $current = $program;
                continue;
            }

            if ((string) $program['start_time'] > $currentTime && $next === null) {
                $next = $program;
            }
        }

        if ($current === null) {
            $current = [
                'name' => $todayPrograms === [] ? 'Sin programa registrado' : 'Programación en pausa',
                'host' => 'Radio Rumbo',
                'schedule' => $next !== null ? 'Próximo bloque: ' . (string) $next['time'] : 'Fuera de horario',
                'category' => 'Programación',
                'status' => 'Programación en pausa',
                'summary' => 'La transmisión de audio puede estar fuera de horario '
                    . 'o pendiente de activación desde cabina.',
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

    public static function todayKey(string $timezone, ?DateTimeImmutable $now = null): string
    {
        $clock = $now ?? new DateTimeImmutable('now', new DateTimeZone($timezone));
        $englishDay = strtolower($clock->format('l'));

        return self::ENGLISH_TO_SPANISH_DAYS[$englishDay] ?? 'lunes';
    }

    public static function normalizeDay(?string $day): ?string
    {
        if ($day === null) {
            return null;
        }

        $normalized = strtolower(trim(strtr($day, [
            'Á' => 'a',
            'É' => 'e',
            'Í' => 'i',
            'Ó' => 'o',
            'Ú' => 'u',
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
        ])));

        if (isset(self::ENGLISH_TO_SPANISH_DAYS[$normalized])) {
            return self::ENGLISH_TO_SPANISH_DAYS[$normalized];
        }

        return isset(self::DAY_LABELS[$normalized]) ? $normalized : null;
    }

    public static function selectedDay(?string $requestedDay, string $timezone, ?DateTimeImmutable $now = null): string
    {
        return self::normalizeDay($requestedDay) ?? self::todayKey($timezone, $now);
    }

    public static function previousDayKey(string $day): string
    {
        $normalizedDay = self::normalizeDay($day) ?? 'lunes';
        $index = array_search($normalizedDay, self::DAY_KEYS, true);

        if (!is_int($index)) {
            return 'domingo';
        }

        return self::DAY_KEYS[($index + count(self::DAY_KEYS) - 1) % count(self::DAY_KEYS)];
    }

    /**
     * @param list<array<string, mixed>> $programs
     * @return list<array<string, mixed>>
     */
    public function programsForDay(array $programs, string $selectedDay): array
    {
        $normalizedDay = self::normalizeDay($selectedDay) ?? $selectedDay;
        $dayPrograms = [];

        foreach ($programs as $program) {
            $programDay = self::normalizeDay((string) ($program['day_of_week'] ?? $normalizedDay)) ?? $normalizedDay;
            if ($programDay !== $normalizedDay) {
                continue;
            }

            $viewData = $this->programViewData($program, false);
            $viewData['day_of_week'] = $programDay;
            $dayPrograms[] = $viewData;
        }

        usort(
            $dayPrograms,
            static fn (array $a, array $b): int => strcmp((string) $a['start_time'], (string) $b['start_time'])
        );

        return $dayPrograms;
    }

    /**
     * @param list<array<string, mixed>> $programs
     * @return list<array<string, mixed>>
     */
    private function overnightProgramsFromPreviousDay(array $programs, string $previousDay): array
    {
        return array_values(array_filter(
            $this->programsForDay($programs, $previousDay),
            fn (array $program): bool => $this->crossesMidnight(
                (string) $program['start_time'],
                (string) $program['end_time']
            )
        ));
    }

    private function isCurrentProgram(string $start, string $end, string $currentTime): bool
    {
        if ($this->crossesMidnight($start, $end)) {
            return $currentTime >= $start || $currentTime < $end;
        }

        return $start <= $currentTime && $end > $currentTime;
    }

    private function crossesMidnight(string $start, string $end): bool
    {
        return $start > $end;
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
            'name' => $this->presentableProgramName((string) ($program['name'] ?? 'Programación en pausa')),
            'host' => (string) ($program['host_name'] ?? $program['host'] ?? 'Cabina Radio Rumbo'),
            'schedule' => $start . ' - ' . $end,
            'category' => (string) ($program['category'] ?? 'Radio Rumbo'),
            'status' => $live ? 'EN VIVO' : 'Programado',
            'summary' => $this->publicProgramSummary((string) ($program['description'] ?? $program['summary'] ?? '')),
            'time' => $start,
            'start_time' => $start,
            'end_time' => $end,
            'day_of_week' => self::normalizeDay((string) ($program['day_of_week'] ?? '')) ?? '',
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

    private function presentableProgramName(string $name): string
    {
        return trim($name) === 'Programa matinal' ? 'Rumbo Mañanero' : $name;
    }

    private function publicProgramSummary(string $summary): string
    {
        if (preg_match('/desarrollo|mock|demo|pendiente|por confirmar/i', $summary) === 1) {
            return '';
        }

        return str_replace([' del dia', ' al dia'], [' del día', ' al día'], $summary);
    }
}
