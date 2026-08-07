<?php

declare(strict_types=1);

namespace App\Validators;

use App\Helpers\UrlHelper;

final class BannerValidator
{
    /** @var list<string> */
    public const POSITIONS = ['home_hero', 'home_middle', 'sidebar', 'footer'];

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
        $imagePath = trim((string) ($input['image_path'] ?? ''));
        $linkUrl = trim((string) ($input['link_url'] ?? ''));
        $position = (string) ($input['position'] ?? '');
        $status = (string) ($input['status'] ?? '');

        if ($title === '') {
            $errors['title'] = 'El titulo es obligatorio.';
        } elseif (mb_strlen($title) > 160) {
            $errors['title'] = 'El titulo no debe superar 160 caracteres.';
        }

        if (!in_array($position, self::POSITIONS, true)) {
            $errors['position'] = 'La posicion no es valida.';
        }

        if (!in_array($status, self::STATUSES, true)) {
            $errors['status'] = 'El estado no es valido.';
        }

        if ($imagePath !== '' && str_starts_with($imagePath, 'http') && UrlHelper::httpsOrEmpty($imagePath) === '') {
            $errors['image_path'] = 'La imagen externa debe usar https://.';
        }

        if ($linkUrl !== '' && UrlHelper::httpsOrEmpty($linkUrl) === '') {
            $errors['link_url'] = 'El enlace debe iniciar con https://.';
        }

        if (!$this->validDateRange($input)) {
            $errors['end_at'] = 'La fecha de fin debe ser posterior a la fecha de inicio.';
        }

        return $errors;
    }

    /**
     * @param array<string, mixed> $input
     */
    private function validDateRange(array $input): bool
    {
        $start = trim(str_replace('T', ' ', (string) ($input['start_at'] ?? '')));
        $end = trim(str_replace('T', ' ', (string) ($input['end_at'] ?? '')));

        return $start === '' || $end === '' || $start < $end;
    }
}
