<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Role;
use App\Models\User;

final class AuthService
{
    public function __construct(private ?User $users = null)
    {
    }

    /**
     * @return array{id:int,name:string,email:string,role:string}|null
     */
    public function attempt(string $identifier, string $password): ?array
    {
        $user = $this->userRepository()->findActiveByIdentifier($identifier);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        if (!in_array($user['role'], Role::allowed(), true)) {
            return null;
        }

        $this->userRepository()->touchLastLogin($user['id']);

        return [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    }

    private function userRepository(): User
    {
        if (!$this->users instanceof User) {
            $this->users = new User();
        }

        return $this->users;
    }
}
