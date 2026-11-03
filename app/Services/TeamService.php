<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\UrlHelper;
use App\Models\TeamMember;
use App\Validators\TeamMemberValidator;

final class TeamService
{
    public function __construct(
        private ?TeamMember $team = null,
        private ?TeamMemberValidator $validator = null
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
        $errors = $this->teamValidator()->validate($input);

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
        $errors = $this->teamValidator()->validate($input);

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
        return [
            'name' => trim((string) ($input['name'] ?? '')),
            'role' => trim((string) ($input['role'] ?? '')),
            'program_name' => trim((string) ($input['program_name'] ?? '')),
            'bio' => trim((string) ($input['bio'] ?? '')),
            'photo_path' => trim((string) ($input['photo_path'] ?? '')),
            'facebook_url' => UrlHelper::httpsOrEmpty(trim((string) ($input['facebook_url'] ?? ''))),
            'instagram_url' => UrlHelper::httpsOrEmpty(trim((string) ($input['instagram_url'] ?? ''))),
            'is_active' => isset($input['is_active']) && (string) $input['is_active'] === '1' ? 1 : 0,
            'display_order' => max(0, (int) ($input['display_order'] ?? 0)),
        ];
    }

    private function repository(): TeamMember
    {
        if (!$this->team instanceof TeamMember) {
            $this->team = new TeamMember();
        }

        return $this->team;
    }

    private function teamValidator(): TeamMemberValidator
    {
        if (!$this->validator instanceof TeamMemberValidator) {
            $this->validator = new TeamMemberValidator();
        }

        return $this->validator;
    }
}
