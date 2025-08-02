<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface UserRepositoryInterface
{
    public function create(array $userData, ?UploadedFile $profilePhoto = null): User;
   
}
