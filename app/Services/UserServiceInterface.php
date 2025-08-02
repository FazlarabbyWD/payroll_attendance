<?php

namespace App\Services;

use App\Models\User;
use App\Http\Requests\UserStoreRequest;

interface UserServiceInterface
{
    public function createUser(UserStoreRequest $request): User;
}
