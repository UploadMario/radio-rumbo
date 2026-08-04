<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\UrlHelper;
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

        $streamAudioUrl = $this->settingValue(
            $settings,
            'stream_audio_url',
            'STREAM_AUDIO_URL',
            (string) ($defaults['stream_audio_url'] ?? '')
        );
        $legacyStreamUrl = $this->settingValue(
            $settings,
            'STREAM_URL',
            'STREAM_URL',
            (string) ($defaults['stream_url'] ?? '')
        );
        $videoWidgetUrl = $this->settingValue(
            $settings,
            'live_video_widget_url',
            'LIVE_VIDEO_WIDGET_URL',
            (string) ($defaults['live_video_widget_url'] ?? '')
        );
        $facebookPageUrl = $this->settingValue(
            $settings,
            'facebook_page_url',
            'FACEBOOK_PAGE_URL',
            (string) ($defaults['facebook_page_url'] ?? '')
        );
        $facebookPluginUrl = $this->settingValue(
            $settings,
            'facebook_page_plugin_url',
            'FACEBOOK_PAGE_PLUGIN_URL',
            (string) ($defaults['facebook_page_plugin_url'] ?? '')
        );

        return [
            'name' => $settings['RADIO_NAME'] ?? $defaults['name'] ?? 'Radio Rumbo',
            'tagline' => $settings['RADIO_TAGLINE'] ?? $defaults['tagline'] ?? 'La radio que se ve',
            'location' => $settings['RADIO_LOCATION'] ?? $defaults['location'] ?? 'Huancayo, Perú',
            'stream_url' => UrlHelper::httpsOrEmpty($legacyStreamUrl),
            'stream_audio_url' => UrlHelper::httpsOrEmpty($streamAudioUrl),
            'live_video_widget_url' => UrlHelper::httpsOrEmpty($videoWidgetUrl),
            'facebook_page_url' => UrlHelper::httpsOrEmpty($facebookPageUrl),
            'facebook_page_id' => $this->settingValue(
                $settings,
                'facebook_page_id',
                'FACEBOOK_PAGE_ID',
                (string) ($defaults['facebook_page_id'] ?? '')
            ),
            'facebook_page_plugin_url' => UrlHelper::httpsOrEmpty($facebookPluginUrl),
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

    /**
     * @param array<string, string> $settings
     */
    private function settingValue(array $settings, string $databaseKey, string $envKey, string $default = ''): string
    {
        $upperDatabaseKey = strtoupper($databaseKey);
        $value = $settings[$databaseKey] ?? $settings[$upperDatabaseKey] ?? $_ENV[$envKey] ?? $default;

        return is_string($value) ? $value : '';
    }

    private function settingRepository(): Setting
    {
        if (!$this->settings instanceof Setting) {
            $this->settings = new Setting();
        }

        return $this->settings;
    }
}
