<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface UserRepositoryInterface
{

    public function getAll();
    public function create(array $userData, ?UploadedFile $profilePhoto = null): User;
    public function find(string $id): ?User;
    public function update(User $user, array $userData, ?UploadedFile $profilePhoto = null): User;
    public function delete(User $user): void;

}
