<?php

namespace App\Services;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;

interface UserServiceInterface
{
    public function createUser(UserStoreRequest $request): User;
    public function findUser(string $id): ?User;
    public function updateUser(UserUpdateRequest $request, string $id): User;
    public function deleteUser(string $id): void;
}
