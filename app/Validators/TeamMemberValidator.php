<?php

declare(strict_types=1);

namespace App\Validators;

use App\Helpers\UrlHelper;

final class TeamMemberValidator
{
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    public function validate(array $input): array
    {
        $errors = [];
        $name = trim((string) ($input['name'] ?? ''));
        $role = trim((string) ($input['role'] ?? ''));

        if ($name === '') {
            $errors['name'] = 'El nombre es obligatorio.';
        } elseif (mb_strlen($name) > 140) {
            $errors['name'] = 'El nombre no debe superar 140 caracteres.';
        }

        if ($role === '') {
            $errors['role'] = 'El rol es obligatorio.';
        } elseif (mb_strlen($role) > 140) {
            $errors['role'] = 'El rol no debe superar 140 caracteres.';
        }

        foreach (['facebook_url' => 'Facebook', 'instagram_url' => 'Instagram'] as $field => $label) {
            $url = trim((string) ($input[$field] ?? ''));
            if ($url !== '' && UrlHelper::httpsOrEmpty($url) === '') {
                $errors[$field] = 'La URL de ' . $label . ' debe iniciar con https://.';
            }
        }

        return $errors;
    }
}
