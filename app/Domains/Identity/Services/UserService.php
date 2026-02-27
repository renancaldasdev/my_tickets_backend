<?php

declare(strict_types=1);

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Interfaces\UserRepositoryInterface;
use App\Domains\Identity\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function listUsers(): Collection
    {
        /** @var Collection<int, User> $users */
        $users = $this->userRepository->all();

        return $users;
    }

    public function createUser(array $data, User $manager): User
    {
        $data['customer_id'] = $manager->customer_id;

        $data['password'] = Hash::make($data['password']);

        $data['role'] = $data['role'] ?? 'user';

        /** @var User $user */
        $user = $this->userRepository->create($data);

        event(new Registered($user));

        return $user;
    }

    public function getUser(string $uuid): User
    {
        /** @var User $user */
        $user = $this->userRepository->findByUuid($uuid);

        return $user;
    }

    public function updateUser(string $uuid, array $data): User
    {
        /** @var User $user */
        $user = $this->userRepository->findByUuid($uuid);

        if (isset($data['password']) && ! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        /** @var User $updatedUser */
        $updatedUser = $this->userRepository->update($user, $data);

        return $updatedUser;
    }

    public function deactivateUser(string $uuid): User
    {
        /** @var User $user */
        $user = $this->userRepository->findByUuid($uuid);

        /** @var User $deactivatedUser */
        $deactivatedUser = $this->userRepository->update($user, [
            'is_active' => false,
        ]);

        return $deactivatedUser;
    }
}
