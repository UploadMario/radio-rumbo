<?php

declare(strict_types=1);

namespace App\Validators;

final class LoginValidator
{
    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    public function validate(array $input): array
    {
        $errors = [];
        $identifier = trim((string) ($input['identifier'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        if ($identifier === '') {
            $errors['identifier'] = 'Ingresa tu usuario o correo.';
        }

        if ($password === '') {
            $errors['password'] = 'Ingresa tu contraseña.';
        }

        return $errors;
    }
}
