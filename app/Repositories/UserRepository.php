<?php
namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository implements UserRepositoryInterface
{
    protected $userCrudLog;

    public function __construct()
    {
        $this->userCrudLog = Log::channel('userStoreLog');
    }


    public function getAll()
    {
        return User::all();
    }


    public function create(array $userData, ?UploadedFile $profilePhoto = null): User
    {
        return DB::transaction(function () use ($userData, $profilePhoto) {
            $user           = new User();
            $user->username = $userData['username'];
            $user->email    = $userData['email'];
            $user->phone    = $userData['phone'];
            $user->password = Hash::make($userData['password']);
             $user->created_by   = auth()->user()->id;


            if ($profilePhoto) {
                $filename = Str::random(40) . '.' . $profilePhoto->getClientOriginalExtension();
                $profilePhoto->storeAs('users', $filename, 'public');
                $user->profile_photo = 'users/' . $filename;
            }

            $user->save();

            $this->userCrudLog->info('User record inserted into DB', [
                'user_id'  => $user->id,
                'username' => $user->username,
                'email'    => $user->email,
            ]);

            return $user;
        });
    }

     public function find(string $id): ?User
    {
        return User::find($id);
    }



    public function update(User $user, array $userData, ?UploadedFile $profilePhoto = null): User
    {
        return DB::transaction(function () use ($user, $userData, $profilePhoto) {
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->phone = $userData['phone'];
              $user->updated_by   = auth()->user()->id;

            if (isset($userData['password']) && $userData['password']) {
                $user->password = Hash::make($userData['password']);
            }

            if ($profilePhoto) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                $filename = Str::random(40) . '.' . $profilePhoto->getClientOriginalExtension();
                $profilePhoto->storeAs('users', $filename, 'public');
                $user->profile_photo = 'users/' . $filename;
            }

            $user->save();

            $this->userCrudLog->info('User record updated in DB', [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ]);

            return $user;
        });
    }


public function delete(User $user): void
{
    DB::transaction(function () use ($user) {

        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }
        $user->deleted_by = auth()->user()->id;

        $user->delete();

        $this->userCrudLog->info('User record deleted from DB', [
            'user_id' => $user->id,
        ]);
    });
}

}
