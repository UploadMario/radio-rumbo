<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\UrlHelper;
use App\Models\Setting;
use App\Validators\SettingValidator;
use Throwable;

final class SettingService
{
    private const DEFAULT_FACEBOOK_PAGE_URL = 'https://www.facebook.com/radiorumboperu';

    public function __construct(
        private ?Setting $settings = null,
        private ?SettingValidator $validator = null
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function editableSettings(): array
    {
        $defaults = [
            'RADIO_NAME' => 'Radio Rumbo',
            'RADIO_TAGLINE' => 'La radio que se ve',
            'RADIO_LOCATION' => 'Huancayo, Peru',
            'stream_audio_url' => '',
            'live_video_widget_url' => '',
            'facebook_page_url' => '',
            'facebook_page_id' => '',
            'facebook_page_plugin_url' => '',
            'WHATSAPP_CABINA' => '',
            'WHATSAPP_PUBLICIDAD' => '',
            'RADIO_EMAIL' => '',
            'RADIO_ABOUT' => '',
        ];

        return array_merge($defaults, array_intersect_key($this->settingRepository()->all(), $defaults));
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok:bool,errors:array<string,string>,data:array<string,string>}
     */
    public function updateEditable(array $input): array
    {
        $data = [];
        foreach (SettingValidator::KEYS as $key) {
            $data[$key] = trim((string) ($input[$key] ?? ''));
        }

        $errors = $this->settingValidator()->validate($data);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'data' => $data];
        }

        $this->settingRepository()->upsertMany($data);

        return ['ok' => true, 'errors' => [], 'data' => $data];
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
        } catch (Throwable $throwable) {
            (new LoggerService())->warning('No se pudieron cargar settings; se usaran valores por defecto.', [
                'exception' => $throwable,
            ]);
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
        $facebookPage = $this->settingValueWithSource(
            $settings,
            'facebook_page_url',
            'FACEBOOK_PAGE_URL',
            (string) ($defaults['facebook_page_url'] ?? self::DEFAULT_FACEBOOK_PAGE_URL)
        );
        $facebookPlugin = $this->settingValueWithSource(
            $settings,
            'facebook_page_plugin_url',
            'FACEBOOK_PAGE_PLUGIN_URL',
            (string) ($defaults['facebook_page_plugin_url'] ?? '')
        );
        $facebookPageUrl = UrlHelper::httpsOrEmpty($facebookPage['value']);
        $facebookPluginUrl = UrlHelper::httpsOrEmpty($facebookPlugin['value']);
        $facebookPluginSource = $facebookPlugin['source'];
        $whatsappCabina = $this->settingValue(
            $settings,
            'WHATSAPP_CABINA',
            'WHATSAPP_CABINA',
            (string) ($defaults['whatsapp_cabina'] ?? '')
        );
        $whatsappPublicidad = $this->settingValue(
            $settings,
            'WHATSAPP_PUBLICIDAD',
            'WHATSAPP_PUBLICIDAD',
            (string) ($defaults['whatsapp_publicidad'] ?? '')
        );
        $email = $this->settingValue(
            $settings,
            'RADIO_EMAIL',
            'RADIO_EMAIL',
            (string) ($defaults['email'] ?? '')
        );
        $about = $this->settingValue(
            $settings,
            'RADIO_ABOUT',
            'RADIO_ABOUT',
            (string) ($defaults['about'] ?? '')
        );

        if ($facebookPluginUrl === '' && $facebookPageUrl !== '') {
            $facebookPluginUrl = UrlHelper::facebookPagePluginUrl($facebookPageUrl);
            $facebookPluginSource = 'construida desde ' . $facebookPage['source'];
        }

        return [
            'name' => $settings['RADIO_NAME'] ?? $defaults['name'] ?? 'Radio Rumbo',
            'tagline' => $settings['RADIO_TAGLINE'] ?? $defaults['tagline'] ?? 'La radio que se ve',
            'location' => $settings['RADIO_LOCATION'] ?? $defaults['location'] ?? 'Huancayo, Peru',
            'stream_url' => UrlHelper::httpsOrEmpty($legacyStreamUrl),
            'stream_audio_url' => UrlHelper::httpsOrEmpty($streamAudioUrl),
            'live_video_widget_url' => UrlHelper::httpsOrEmpty($videoWidgetUrl),
            'facebook_page_url' => $facebookPageUrl,
            'facebook_page_id' => $this->settingValue(
                $settings,
                'facebook_page_id',
                'FACEBOOK_PAGE_ID',
                (string) ($defaults['facebook_page_id'] ?? '')
            ),
            'facebook_page_plugin_url' => $facebookPluginUrl,
            'facebook_page_source' => $facebookPageUrl !== '' ? $facebookPage['source'] : 'pendiente',
            'facebook_page_plugin_source' => $facebookPluginUrl !== '' ? $facebookPluginSource : 'pendiente',
            'facebook_status' => $facebookPluginUrl !== '' ? 'configurado' : 'pendiente',
            'stream_status' => $defaults['stream_status'] ?? 'Stream simulado',
            'whatsapp_cabina' => $whatsappCabina,
            'whatsapp_publicidad' => $whatsappPublicidad,
            'email' => $email,
            'suggested_email' => $defaults['suggested_email'] ?? '',
            'address' => $defaults['address'] ?? '',
            'maps_url' => UrlHelper::httpsOrEmpty((string) ($defaults['maps_url'] ?? '')),
            'short_location' => $defaults['short_location'] ?? 'Cabina Radio Rumbo',
            'frequency_fm' => $defaults['frequency_fm'] ?? '91.1 FM',
            'frequency_am' => $defaults['frequency_am'] ?? '1560 AM',
            'coverage' => $defaults['coverage'] ?? 'Valle del Mantaro',
            'music_segments' => $defaults['music_segments'] ?? 'Folclor, cumbia y éxitos populares',
            'social' => $defaults['social'] ?? 'Redes sociales oficiales',
            'social_links' => is_array($defaults['social_links'] ?? null) ? $defaults['social_links'] : [],
            'history' => $defaults['history'] ?? '',
            'first_song' => $defaults['first_song'] ?? '',
            'about' => $about,
        ];
    }

    /**
     * @param array<string, string> $settings
     */
    private function settingValue(array $settings, string $databaseKey, string $envKey, string $default = ''): string
    {
        return $this->settingValueWithSource($settings, $databaseKey, $envKey, $default)['value'];
    }

    /**
     * @param array<string, string> $settings
     * @return array{value:string,source:string}
     */
    private function settingValueWithSource(
        array $settings,
        string $databaseKey,
        string $envKey,
        string $default = ''
    ): array {
        $upperDatabaseKey = strtoupper($databaseKey);
        $candidates = [
            ['value' => $settings[$databaseKey] ?? null, 'source' => 'BD'],
            ['value' => $settings[$upperDatabaseKey] ?? null, 'source' => 'BD'],
            ['value' => getenv($envKey), 'source' => '.env'],
            ['value' => $_ENV[$envKey] ?? null, 'source' => '.env'],
            ['value' => $_SERVER[$envKey] ?? null, 'source' => '.env'],
            ['value' => $default, 'source' => 'fallback'],
        ];

        foreach ($candidates as $candidate) {
            $value = $candidate['value'];
            if (!is_string($value)) {
                continue;
            }

            $value = trim($value);
            if ($value !== '') {
                return [
                    'value' => $value,
                    'source' => $candidate['source'],
                ];
            }
        }

        return [
            'value' => '',
            'source' => 'pendiente',
        ];
    }

    private function settingRepository(): Setting
    {
        if (!$this->settings instanceof Setting) {
            $this->settings = new Setting();
        }

        return $this->settings;
    }

    private function settingValidator(): SettingValidator
    {
        if (!$this->validator instanceof SettingValidator) {
            $this->validator = new SettingValidator();
        }

        return $this->validator;
    }
}
