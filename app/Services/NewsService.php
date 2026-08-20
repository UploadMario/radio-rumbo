<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\StringHelper;
use App\Models\News;
use App\Validators\NewsValidator;

final class NewsService
{
    public function __construct(
        private ?News $news = null,
        private ?NewsValidator $validator = null,
        private ?AuditLogService $audit = null
    ) {
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allForAdmin(): array
    {
        return $this->repository()->allForAdmin();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function published(): array
    {
        return $this->repository()->published();
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
     * @return array{ok:bool,errors:array<string,string>,id:int|null,data:array<string,mixed>}
     */
    public function create(array $input, int $userId): array
    {
        $data = $this->normalize($input, $userId);
        $errors = $this->newsValidator()->validate($data);

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'id' => null, 'data' => $data];
        }

        $id = $this->repository()->create($data);
        $this->audit()->record($userId, 'news.create', 'news', $id, ['status' => $data['status']]);

        return ['ok' => true, 'errors' => [], 'id' => $id, 'data' => $data];
    }

    /**
     * @param array<string, mixed> $input
     * @return array{ok:bool,errors:array<string,string>,data:array<string,mixed>}
     */
    public function update(int $id, array $input, int $userId): array
    {
        $data = $this->normalize($input, $userId);
        $errors = $this->newsValidator()->validate($data);

        if ($errors !== []) {
            return ['ok' => false, 'errors' => $errors, 'data' => $data];
        }

        $this->repository()->update($id, $data);
        $this->audit()->record($userId, 'news.update', 'news', $id, ['status' => $data['status']]);

        return ['ok' => true, 'errors' => [], 'data' => $data];
    }

    public function archive(int $id, int $userId): void
    {
        $this->repository()->archive($id, $userId);
        $this->audit()->record($userId, 'news.archive', 'news', $id);
    }

    /**
     * @return array{total:int,published:int,draft:int}
     */
    public function counts(): array
    {
        return $this->repository()->counts();
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>
     */
    private function normalize(array $input, int $userId): array
    {
        $status = (string) ($input['status'] ?? NewsValidator::STATUS_DRAFT);
        $title = trim((string) ($input['title'] ?? ''));
        $slug = trim((string) ($input['slug'] ?? ''));
        $publishedAt = str_replace('T', ' ', trim((string) ($input['published_at'] ?? '')));

        return [
            'title' => $title,
            'slug' => $slug !== '' ? StringHelper::slug($slug) : StringHelper::slug($title),
            'summary' => trim((string) ($input['summary'] ?? '')),
            'body' => trim((string) ($input['body'] ?? '')),
            'category' => trim((string) ($input['category'] ?? '')),
            'status' => $status,
            'published_at' => $status === NewsValidator::STATUS_PUBLISHED
                ? ($publishedAt !== '' ? $publishedAt : date('Y-m-d H:i:s'))
                : null,
            'user_id' => $userId,
        ];
    }

    private function repository(): News
    {
        if (!$this->news instanceof News) {
            $this->news = new News();
        }

        return $this->news;
    }

    private function newsValidator(): NewsValidator
    {
        if (!$this->validator instanceof NewsValidator) {
            $this->validator = new NewsValidator();
        }

        return $this->validator;
    }

    private function audit(): AuditLogService
    {
        if (!$this->audit instanceof AuditLogService) {
            $this->audit = new AuditLogService();
        }

        return $this->audit;
    }
}
