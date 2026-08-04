<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactMessage;
use App\Validators\ContactMessageValidator;

final class ContactMessageService
{
    public function __construct(
        private ?ContactMessage $messages = null,
        private ?ContactMessageValidator $validator = null
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForAdmin(?string $status = null): array
    {
        return $this->repository()->allForAdmin($status);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->repository()->find($id);
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok:bool,spam:bool,errors:array<string,string>,id:int|null,data:array<string,mixed>}
     */
    public function create(array $input): array
    {
        $data = $this->normalize($input);

        if ($this->messageValidator()->hasSpamTrap($input)) {
            return ['ok' => true, 'spam' => true, 'errors' => [], 'id' => null, 'data' => $data];
        }

        $errors = $this->messageValidator()->validate($data);
        if ($errors !== []) {
            return ['ok' => false, 'spam' => false, 'errors' => $errors, 'id' => null, 'data' => $data];
        }

        return [
            'ok' => true,
            'spam' => false,
            'errors' => [],
            'id' => $this->repository()->create($data),
            'data' => $data,
        ];
    }

    public function markRead(int $id): void
    {
        $this->repository()->markRead($id);
    }

    public function archive(int $id): void
    {
        $this->repository()->archive($id);
    }

    public function newCount(): int
    {
        return $this->repository()->newCount();
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function normalize(array $input): array
    {
        return [
            'name' => trim((string) ($input['name'] ?? '')),
            'email' => trim((string) ($input['email'] ?? '')),
            'phone' => trim((string) ($input['phone'] ?? '')),
            'subject' => trim((string) ($input['subject'] ?? '')),
            'message' => trim((string) ($input['message'] ?? '')),
            'type' => (string) ($input['type'] ?? 'general'),
            'status' => 'new',
            'ip_address' => substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
            'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
        ];
    }

    private function repository(): ContactMessage
    {
        if (!$this->messages instanceof ContactMessage) {
            $this->messages = new ContactMessage();
        }

        return $this->messages;
    }

    private function messageValidator(): ContactMessageValidator
    {
        if (!$this->validator instanceof ContactMessageValidator) {
            $this->validator = new ContactMessageValidator();
        }

        return $this->validator;
    }
}
