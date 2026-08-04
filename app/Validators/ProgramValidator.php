<?php

declare(strict_types=1);

namespace App\Validators;

final class ProgramValidator
{
    /** @var list<string> */
    public const DAYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    public function validate(array $input): array
    {
        $errors = [];
        $name = trim((string) ($input['name'] ?? ''));
        $day = (string) ($input['day_of_week'] ?? '');
        $start = (string) ($input['start_time'] ?? '');
        $end = (string) ($input['end_time'] ?? '');

        if ($name === '') {
            $errors['name'] = 'El nombre del programa es obligatorio.';
        } elseif (mb_strlen($name) > 140) {
            $errors['name'] = 'El nombre no debe superar 140 caracteres.';
        }

        if (!in_array($day, self::DAYS, true)) {
            $errors['day_of_week'] = 'El dia seleccionado no es valido.';
        }

        if (!$this->isTime($start)) {
            $errors['start_time'] = 'La hora de inicio no es valida.';
        }

        if (!$this->isTime($end)) {
            $errors['end_time'] = 'La hora de fin no es valida.';
        }

        if ($this->isTime($start) && $this->isTime($end) && $start >= $end) {
            $errors['end_time'] = 'La hora de fin debe ser mayor que la hora de inicio.';
        }

        return $errors;
    }

    private function isTime(string $value): bool
    {
        return (bool) preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $value);
    }
}
