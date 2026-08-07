<?php

declare(strict_types=1);

namespace App\Validators;

final class UrgentNoticeValidator
{
    /** @var list<string> */
    public const TYPES = ['info', 'warning', 'urgent', 'event'];

    /** @var list<string> */
    public const STATUSES = ['draft', 'active', 'inactive', 'archived'];

    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    public function validate(array $input): array
    {
        $errors = [];
        $title = trim((string) ($input['title'] ?? ''));
        $message = trim((string) ($input['message'] ?? ''));

        if ($title === '') {
            $errors['title'] = 'El titulo es obligatorio.';
        } elseif (mb_strlen($title) > 160) {
            $errors['title'] = 'El titulo no debe superar 160 caracteres.';
        }

        if ($message === '') {
            $errors['message'] = 'El mensaje es obligatorio.';
        } elseif (mb_strlen($message) > 500) {
            $errors['message'] = 'El mensaje no debe superar 500 caracteres.';
        }

        if (!in_array((string) ($input['type'] ?? ''), self::TYPES, true)) {
            $errors['type'] = 'El tipo no es valido.';
        }

        if (!in_array((string) ($input['status'] ?? ''), self::STATUSES, true)) {
            $errors['status'] = 'El estado no es valido.';
        }

        $start = trim(str_replace('T', ' ', (string) ($input['start_at'] ?? '')));
        $end = trim(str_replace('T', ' ', (string) ($input['end_at'] ?? '')));
        if ($start !== '' && $end !== '' && $start >= $end) {
            $errors['end_at'] = 'La fecha de fin debe ser posterior a la fecha de inicio.';
        }

        return $errors;
    }
}
