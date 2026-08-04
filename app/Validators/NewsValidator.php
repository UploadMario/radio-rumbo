<?php

declare(strict_types=1);

namespace App\Validators;

final class NewsValidator
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    public function validate(array $input): array
    {
        $errors = [];
        $title = trim((string) ($input['title'] ?? ''));
        $summary = trim((string) ($input['summary'] ?? ''));
        $body = trim((string) ($input['body'] ?? ''));
        $category = trim((string) ($input['category'] ?? ''));
        $status = (string) ($input['status'] ?? self::STATUS_DRAFT);

        if ($title === '') {
            $errors['title'] = 'El título es obligatorio.';
        } elseif (mb_strlen($title) > 180) {
            $errors['title'] = 'El título no debe superar 180 caracteres.';
        }

        if ($summary === '') {
            $errors['summary'] = 'El resumen es obligatorio.';
        } elseif (mb_strlen($summary) > 255) {
            $errors['summary'] = 'El resumen no debe superar 255 caracteres.';
        }

        if ($body === '') {
            $errors['body'] = 'El contenido es obligatorio.';
        }

        if ($category === '') {
            $errors['category'] = 'La categoría es obligatoria.';
        } elseif (mb_strlen($category) > 80) {
            $errors['category'] = 'La categoría no debe superar 80 caracteres.';
        }

        if (!in_array($status, [self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ARCHIVED], true)) {
            $errors['status'] = 'El estado de la noticia no es válido.';
        }

        return $errors;
    }
}
