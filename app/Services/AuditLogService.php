<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use PDO;
use Throwable;

final class AuditLogService
{
    public function __construct(private ?PDO $connection = null, private ?LoggerService $logger = null)
    {
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function record(
        ?int $userId,
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        array $metadata = []
    ): void {
        try {
            $statement = $this->pdo()->prepare(
                'INSERT INTO audit_logs
                    (user_id, action, entity_type, entity_id, ip_address, user_agent, metadata)
                 VALUES
                    (:user_id, :action, :entity_type, :entity_id, :ip_address, :user_agent, :metadata)'
            );

            $statement->execute([
                'user_id' => $userId,
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'ip_address' => $this->clientIp(),
                'user_agent' => $this->userAgent(),
                'metadata' => $this->metadata($metadata),
            ]);
        } catch (Throwable $throwable) {
            $this->logger()->warning('No se pudo registrar auditoría administrativa.', [
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'exception' => $throwable,
            ]);
        }
    }

    /**
     * @param array<string, mixed> $metadata
     */
    private function metadata(array $metadata): string
    {
        $encoded = json_encode($this->logger()->sanitize($metadata), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return is_string($encoded) ? $encoded : '{}';
    }

    private function clientIp(): ?string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;

        return is_string($ip) && filter_var($ip, FILTER_VALIDATE_IP) ? $ip : null;
    }

    private function userAgent(): ?string
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        return is_string($userAgent) ? substr($userAgent, 0, 255) : null;
    }

    private function pdo(): PDO
    {
        return $this->connection ?? Database::connection();
    }

    private function logger(): LoggerService
    {
        if (!$this->logger instanceof LoggerService) {
            $this->logger = new LoggerService();
        }

        return $this->logger;
    }
}
