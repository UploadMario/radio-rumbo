<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\EnvironmentHelper;
use Throwable;

final class LoginRateLimiterService
{
    public function __construct(private ?string $storagePath = null)
    {
    }

    public function tooManyAttempts(string $identifier, ?string $ip = null): bool
    {
        $attempts = $this->recentAttempts($identifier, $ip);

        return count($attempts) >= $this->maxAttempts();
    }

    public function recordFailure(string $identifier, ?string $ip = null): void
    {
        $attempts = $this->recentAttempts($identifier, $ip);
        $attempts[] = time();
        $this->writeAttempts($identifier, $ip, $attempts);
    }

    public function clear(string $identifier, ?string $ip = null): void
    {
        $file = $this->filePath($identifier, $ip);

        if (is_file($file)) {
            @unlink($file);
        }
    }

    public function clientIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        return is_string($ip) && filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
    }

    public function maxAttempts(): int
    {
        return EnvironmentHelper::int('LOGIN_MAX_ATTEMPTS', 5, 1);
    }

    public function windowSeconds(): int
    {
        return EnvironmentHelper::int('LOGIN_WINDOW_SECONDS', 900, 60);
    }

    /**
     * @return list<int>
     */
    private function recentAttempts(string $identifier, ?string $ip): array
    {
        $file = $this->filePath($identifier, $ip);
        if (!is_file($file)) {
            return [];
        }

        try {
            $payload = json_decode((string) file_get_contents($file), true);
        } catch (Throwable) {
            return [];
        }

        $cutoff = time() - $this->windowSeconds();
        $attempts = [];

        foreach (is_array($payload['attempts'] ?? null) ? $payload['attempts'] : [] as $attempt) {
            if (is_int($attempt) && $attempt >= $cutoff) {
                $attempts[] = $attempt;
            }
        }

        return $attempts;
    }

    /**
     * @param list<int> $attempts
     */
    private function writeAttempts(string $identifier, ?string $ip, array $attempts): void
    {
        $file = $this->filePath($identifier, $ip);
        $directory = dirname($file);

        try {
            if (!is_dir($directory)) {
                mkdir($directory, 0775, true);
            }

            file_put_contents($file, json_encode(['attempts' => $attempts]), LOCK_EX);
        } catch (Throwable $throwable) {
            (new LoggerService())->warning('No se pudo registrar rate limit de login.', ['exception' => $throwable]);
        }
    }

    private function filePath(string $identifier, ?string $ip): string
    {
        $root = $this->storagePath ?? dirname(__DIR__, 2) . '/storage/rate-limit';
        $safeIp = $ip !== null && filter_var($ip, FILTER_VALIDATE_IP) ? $ip : $this->clientIp();
        $key = hash('sha256', strtolower(trim($identifier)) . '|' . $safeIp);

        return $root . '/' . $key . '.json';
    }
}
