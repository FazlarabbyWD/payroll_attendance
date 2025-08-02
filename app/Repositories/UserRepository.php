<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 

class UserRepository implements UserRepositoryInterface
{
    protected $userCrudLog;

    public function __construct()
    {
        $this->userCrudLog = Log::channel('userStoreLog');
    }

    public function create(array $userData, ?UploadedFile $profilePhoto = null): User
    {
        try {
            return DB::transaction(function () use ($userData, $profilePhoto) {
                $user = new User();
                $user->username = $userData['username'];
                $user->email = $userData['email'];
                $user->phone = $userData['phone'];
                $user->password = Hash::make($userData['password']);

                if ($profilePhoto) {
                    $filename = Str::random(40) . '.' . $profilePhoto->getClientOriginalExtension();
                    $profilePhoto->storeAs('public/users', $filename);
                    $user->profile_photo = 'users/' . $filename;
                }

                $user->save();

                // Log successful user creation to custom log
                $this->userCrudLog->info('User created successfully', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                ]);


                return $user;
            });
        } catch (\Exception $e) {


            // Log the error to the user log
            $this->userCrudLog->error('User creation failed', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_data' => $userData,
            ]);


            throw $e;
        }
    }
}
