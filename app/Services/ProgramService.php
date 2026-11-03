<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\StringHelper;
use App\Models\Program;
use App\Validators\ProgramValidator;

final class ProgramService
{
    public function __construct(
        private ?Program $program = null,
        private ?ProgramValidator $validator = null
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
    public function active(): array
    {
        return $this->repository()->active();
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
    public function create(array $input): array
    {
        $data = $this->normalize($input);
        $errors = $this->programValidator()->validate($data);

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
        $errors = $this->programValidator()->validate($data);

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

    public function delete(int $id): void
    {
        $this->archive($id);
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
        $name = trim((string) ($input['name'] ?? ''));
        $slug = trim((string) ($input['slug'] ?? ''));

        return [
            'name' => $name,
            'slug' => $slug !== '' ? StringHelper::slug($slug) : StringHelper::slug($name),
            'description' => trim((string) ($input['description'] ?? '')),
            'host_name' => trim((string) ($input['host_name'] ?? '')),
            'day_of_week' => (string) ($input['day_of_week'] ?? ''),
            'start_time' => substr((string) ($input['start_time'] ?? ''), 0, 5),
            'end_time' => substr((string) ($input['end_time'] ?? ''), 0, 5),
            'category' => trim((string) ($input['category'] ?? '')),
            'is_active' => isset($input['is_active']) && (string) $input['is_active'] === '1' ? 1 : 0,
            'display_order' => max(0, (int) ($input['display_order'] ?? 0)),
        ];
    }

    private function repository(): Program
    {
        if (!$this->program instanceof Program) {
            $this->program = new Program();
        }

        return $this->program;
    }

    private function programValidator(): ProgramValidator
    {
        if (!$this->validator instanceof ProgramValidator) {
            $this->validator = new ProgramValidator();
        }

        return $this->validator;
    }
}
