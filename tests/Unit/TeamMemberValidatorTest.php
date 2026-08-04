<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\TeamMemberValidator;
use PHPUnit\Framework\TestCase;

final class TeamMemberValidatorTest extends TestCase
{
    public function testRequiresNameAndRole(): void
    {
        $errors = (new TeamMemberValidator())->validate([]);

        self::assertArrayHasKey('name', $errors);
        self::assertArrayHasKey('role', $errors);
    }

    public function testRejectsUnsafeSocialUrl(): void
    {
        $errors = (new TeamMemberValidator())->validate([
            'name' => 'Conductor principal',
            'role' => 'Conduccion',
            'facebook_url' => 'javascript:alert(1)',
        ]);

        self::assertArrayHasKey('facebook_url', $errors);
    }
}
