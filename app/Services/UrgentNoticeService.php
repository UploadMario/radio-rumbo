<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UrgentNotice;
use App\Validators\UrgentNoticeValidator;

final class UrgentNoticeService
{
    public function __construct(
        private ?UrgentNotice $notices = null,
        private ?UrgentNoticeValidator $validator = null
    ) {
    }

    /** @return list<array<string, mixed>> */
    public function allForAdmin(): array
    {
        return $this->repository()->allForAdmin();
    }

    /** @return list<array<string, mixed>> */
    public function active(): array
    {
        return $this->repository()->active(date('Y-m-d H:i:s'));
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->repository()->find($id);
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok:bool,errors:array<string,string>,id:int|null,data:array<string,mixed>}
     */
    public function create(array $input): array
    {
        $data = $this->normalize($input);
        $errors = $this->noticeValidator()->validate($data);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'id' => null, 'data' => $data];
        }

        return ['ok' => true, 'errors' => [], 'id' => $this->repository()->create($data), 'data' => $data];
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok:bool,errors:array<string,string>,data:array<string,mixed>}
     */
    public function update(int $id, array $input): array
    {
        $data = $this->normalize($input);
        $errors = $this->noticeValidator()->validate($data);
        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'data' => $data];
        }

        $this->repository()->update($id, $data);

        return ['ok' => true, 'errors' => [], 'data' => $data];
    }

    public function toggle(int $id): void
    {
        $this->repository()->toggle($id);
    }

    public function archive(int $id): void
    {
        $this->repository()->archive($id);
    }

    public function activeCount(): int
    {
        return $this->repository()->activeCount();
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function normalize(array $input): array
    {
        return [
            'title' => trim((string) ($input['title'] ?? '')),
            'message' => trim((string) ($input['message'] ?? '')),
            'type' => (string) ($input['type'] ?? 'info'),
            'status' => (string) ($input['status'] ?? 'draft'),
            'start_at' => $this->dateOrNull($input['start_at'] ?? null),
            'end_at' => $this->dateOrNull($input['end_at'] ?? null),
            'display_order' => max(0, (int) ($input['display_order'] ?? 0)),
        ];
    }

    private function dateOrNull(mixed $value): ?string
    {
        $date = trim(str_replace('T', ' ', is_string($value) ? $value : ''));

        return $date !== '' ? $date : null;
    }

    private function repository(): UrgentNotice
    {
        if (!$this->notices instanceof UrgentNotice) {
            $this->notices = new UrgentNotice();
        }

        return $this->notices;
    }

    private function noticeValidator(): UrgentNoticeValidator
    {
        if (!$this->validator instanceof UrgentNoticeValidator) {
            $this->validator = new UrgentNoticeValidator();
        }

        return $this->validator;
    }
}
