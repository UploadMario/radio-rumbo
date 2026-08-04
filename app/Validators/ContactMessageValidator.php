<?php

declare(strict_types=1);

namespace App\Validators;

final class ContactMessageValidator
{
    /** @var list<string> */
    public const TYPES = ['general', 'advertising', 'news', 'technical'];

    /** @var list<string> */
    public const STATUSES = ['new', 'read', 'archived'];

    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    public function validate(array $input): array
    {
        $errors = [];
        $name = trim((string) ($input['name'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $phone = trim((string) ($input['phone'] ?? ''));
        $subject = trim((string) ($input['subject'] ?? ''));
        $message = trim((string) ($input['message'] ?? ''));
        $type = (string) ($input['type'] ?? 'general');

        if ($name === '') {
            $errors['name'] = 'El nombre es obligatorio.';
        } elseif (mb_strlen($name) > 140) {
            $errors['name'] = 'El nombre no debe superar 140 caracteres.';
        }

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'El correo ingresado no es valido.';
        }

        if ($phone !== '' && (mb_strlen($phone) < 6 || mb_strlen($phone) > 40)) {
            $errors['phone'] = 'El telefono debe tener una longitud razonable.';
        }

        if ($subject === '') {
            $errors['subject'] = 'El asunto es obligatorio.';
        } elseif (mb_strlen($subject) > 160) {
            $errors['subject'] = 'El asunto no debe superar 160 caracteres.';
        }

        if ($message === '') {
            $errors['message'] = 'El mensaje es obligatorio.';
        } elseif (mb_strlen($message) > 2000) {
            $errors['message'] = 'El mensaje no debe superar 2000 caracteres.';
        }

        if (!in_array($type, self::TYPES, true)) {
            $errors['type'] = 'El tipo de consulta no es valido.';
        }

        return $errors;
    }

    /**
     * @param array<string, mixed> $input
     */
    public function hasSpamTrap(array $input): bool
    {
        return trim((string) ($input['website'] ?? '')) !== '';
    }
}
