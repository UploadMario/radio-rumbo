<?php

declare(strict_types=1);

namespace App\Validators;

use App\Helpers\UrlHelper;

final class SettingValidator
{
    /** @var list<string> */
    public const KEYS = [
        'RADIO_NAME',
        'RADIO_TAGLINE',
        'RADIO_LOCATION',
        'stream_audio_url',
        'live_video_widget_url',
        'facebook_page_url',
        'facebook_page_id',
        'facebook_page_plugin_url',
        'WHATSAPP_CABINA',
        'WHATSAPP_PUBLICIDAD',
        'RADIO_EMAIL',
        'RADIO_ABOUT',
    ];

    /**
     * @param array<string, mixed> $input
     * @return array<string, string>
     */
    public function validate(array $input): array
    {
        $errors = [];

        foreach (['RADIO_NAME', 'RADIO_TAGLINE', 'RADIO_LOCATION'] as $key) {
            $value = trim((string) ($input[$key] ?? ''));
            if ($value === '') {
                $errors[$key] = 'Este campo es obligatorio.';
            } elseif (mb_strlen($value) > 160) {
                $errors[$key] = 'No debe superar 160 caracteres.';
            }
        }

        $urlKeys = ['stream_audio_url', 'live_video_widget_url', 'facebook_page_url', 'facebook_page_plugin_url'];
        foreach ($urlKeys as $key) {
            $value = trim((string) ($input[$key] ?? ''));
            if ($value !== '' && UrlHelper::httpsOrEmpty($value) === '') {
                $errors[$key] = 'La URL debe iniciar con https://.';
            }
        }

        $email = trim((string) ($input['RADIO_EMAIL'] ?? ''));
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['RADIO_EMAIL'] = 'El correo institucional no es valido.';
        }

        foreach (['WHATSAPP_CABINA', 'WHATSAPP_PUBLICIDAD'] as $key) {
            $value = trim((string) ($input[$key] ?? ''));
            if ($value !== '' && (!preg_match('/^[0-9 +()-]{6,40}$/', $value))) {
                $errors[$key] = 'El WhatsApp debe tener un formato razonable.';
            }
        }

        if (mb_strlen(trim((string) ($input['RADIO_ABOUT'] ?? ''))) > 600) {
            $errors['RADIO_ABOUT'] = 'El texto institucional no debe superar 600 caracteres.';
        }

        return $errors;
    }
}
