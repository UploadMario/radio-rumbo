<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Banner;
use App\Validators\BannerValidator;

final class BannerService
{
    public function __construct(private ?Banner $banners = null, private ?BannerValidator $validator = null)
    {
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
        $errors = $this->bannerValidator()->validate($data);
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
        $errors = $this->bannerValidator()->validate($data);
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
            'subtitle' => trim((string) ($input['subtitle'] ?? '')),
            'image_path' => trim((string) ($input['image_path'] ?? '')),
            'link_url' => trim((string) ($input['link_url'] ?? '')),
            'position' => (string) ($input['position'] ?? 'home_middle'),
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

    private function repository(): Banner
    {
        if (!$this->banners instanceof Banner) {
            $this->banners = new Banner();
        }

        return $this->banners;
    }

    private function bannerValidator(): BannerValidator
    {
        if (!$this->validator instanceof BannerValidator) {
            $this->validator = new BannerValidator();
        }

        return $this->validator;
    }
}
