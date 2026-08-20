<?php

declare(strict_types=1);

namespace App\Services;

use Throwable;

final class LoggerService
{
    private const SENSITIVE_KEYS = [
        'authorization',
        'cookie',
        'csrf',
        'csrf_token',
        '_csrf_token',
        'password',
        'password_hash',
        'secret',
        'token',
    ];

    public function __construct(private ?string $logFile = null)
    {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void
    {
        $this->write('error', $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function warning(string $message, array $context = []): void
    {
        $this->write('warning', $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void
    {
        $this->write('info', $message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    private function write(string $level, string $message, array $context): void
    {
        $file = $this->logFile ?? dirname(__DIR__, 2) . '/storage/logs/app.log';
        $directory = dirname($file);

        try {
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            $line = json_encode([
                'timestamp' => date('c'),
                'level' => $level,
                'message' => $message,
                'context' => $this->sanitize($context),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            if (is_string($line)) {
                file_put_contents($file, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
        } catch (Throwable) {
            // El logging nunca debe romper una petición pública o administrativa.
        }
    }

    /**
     * @param array<string, mixed> $context
     * @return array<string, mixed>
     */
    public function sanitize(array $context): array
    {
        $clean = [];

        foreach ($context as $key => $value) {
            $normalizedKey = strtolower((string) $key);
            if (in_array($normalizedKey, self::SENSITIVE_KEYS, true)) {
                $clean[$key] = '[redacted]';
                continue;
            }

            if ($value instanceof Throwable) {
                $clean[$key] = [
                    'type' => $value::class,
                    'message' => $value->getMessage(),
                    'code' => $value->getCode(),
                ];
                continue;
            }

            if (is_array($value)) {
                /** @var array<string, mixed> $arrayValue */
                $arrayValue = $value;
                $clean[$key] = $this->sanitize($arrayValue);
                continue;
            }

            if (is_scalar($value) || $value === null) {
                $clean[$key] = $value;
            }
        }

        return $clean;
    }
}
