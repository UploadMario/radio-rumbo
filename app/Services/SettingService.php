<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Throwable;

final class SettingService
{
    public function __construct(private ?Setting $settings = null)
    {
    }

    /**
     * @param array<string, mixed> $fallback
     * @return array<string, mixed>
     */
    public function station(array $fallback): array
    {
        $defaults = is_array($fallback['station'] ?? null) ? $fallback['station'] : [];

        try {
            $settings = $this->settingRepository()->all();
        } catch (Throwable) {
            $settings = [];
        }

        return [
            'name' => $settings['RADIO_NAME'] ?? $defaults['name'] ?? 'Radio Rumbo',
            'tagline' => $settings['RADIO_TAGLINE'] ?? $defaults['tagline'] ?? 'La radio que se ve',
            'location' => $settings['RADIO_LOCATION'] ?? $defaults['location'] ?? 'Huancayo, Perú',
            'stream_url' => $settings['STREAM_URL'] ?? $_ENV['STREAM_URL'] ?? 'https://example.com/stream',
            'stream_status' => $defaults['stream_status'] ?? 'Stream simulado',
            'whatsapp_cabina' => $settings['WHATSAPP_CABINA'] ?? $defaults['whatsapp_cabina'] ?? 'WhatsApp de cabina',
            'whatsapp_publicidad' => $settings['WHATSAPP_PUBLICIDAD']
                ?? $defaults['whatsapp_publicidad']
                ?? 'WhatsApp de publicidad',
            'email' => $defaults['email'] ?? 'Correo institucional pendiente',
            'address' => $defaults['address'] ?? 'Dirección pendiente de confirmar',
            'social' => $defaults['social'] ?? 'Redes sociales oficiales',
        ];
    }

    private function settingRepository(): Setting
    {
        if (!$this->settings instanceof Setting) {
            $this->settings = new Setting();
        }

        return $this->settings;
    }
}
