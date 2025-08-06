<?php
namespace App\Services;

use App\Exceptions\UserCreationException;
use App\Exceptions\UserDeletionException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserUpdateException;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserService implements UserServiceInterface
{
    protected $userRepository;
    protected $userCrudLog;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userCrudLog    = Log::channel('userStoreLog');
    }

     public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }


    public function createUser(UserStoreRequest $request): User
    {
        $username   = $request->input('username');
        $maxRetries = Config::get('user.max_retries', 3);
        $retryDelay = Config::get('user.initial_retry_delay_ms', 100);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $this->userCrudLog->info('Attempting user creation', [
                'attempt'  => $attempt,
                'username' => $username,
            ]);

            try {
                $userData     = $request->validated();
                $profilePhoto = $request->file('profile_photo');

                $user = $this->userRepository->create($userData, $profilePhoto);

                $this->userCrudLog->info('User created', [
                    'attempt'  => $attempt,
                    'user_id'  => $user->id,
                    'username' => $user->username,
                ]);

                return $user;

            } catch (QueryException $e) {
                if ($this->isDeadlockOrConnectionException($e)) {
                    $this->userCrudLog->warning('Transient DB issue during user creation, retrying...', [
                        'attempt'       => $attempt,
                        'username'      => $username,
                        'error_message' => $e->getMessage(),
                    ]);
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2;
                    continue;
                }

                $this->userCrudLog->error('Query exception during user creation', [
                    'attempt'       => $attempt,
                    'username'      => $username,
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            } catch (\Exception $e) {
                $this->userCrudLog->error('Unexpected error during user creation', [
                    'attempt'       => $attempt,
                    'username'      => $username,
                    'error_message' => $e->getMessage(),
                ]);

                throw $e;
            }
        }

        throw new UserCreationException("Failed to create user {$username} after {$maxRetries} attempts.");
    }

  public function findUser(string $id): ?User
    {
        try {
            $user = $this->userRepository->find($id);

            if (! $user) {
                $this->userCrudLog->warning("User not found", ['user_id' => $id]);
                throw new UserNotFoundException("User with ID {$id} not found.");
            }

            return $user;
        } catch (\Exception $e) {
            $this->userCrudLog->error("Error finding user", [
                'user_id'       => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

   public function updateUser(UserUpdateRequest $request, string $id): User
    {
        try {
            $user = $this->findUser($id); // Use findUser to validate existence

            $userData = $request->validated();

            $profilePhoto = $request->file('profile_photo');

            $user = $this->userRepository->update($user, $userData, $profilePhoto); // Pass User object to update

            $this->userCrudLog->info('User updated successfully', [
                'user_id' => $user->id,
            ]);

            return $user;

        } catch (UserNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->userCrudLog->error("Failed to update user", [
                'user_id'       => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw new UserUpdateException("Failed to update user: " . $e->getMessage(), 0, $e);
        }
    }

     public function deleteUser(string $id): void
    {
        try {
            $user = $this->findUser($id);

            $this->userRepository->delete($user);

            $this->userCrudLog->info("User deleted successfully", ['user_id' => $id]);

        } catch (UserNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->userCrudLog->error("Failed to delete user", [
                'user_id'       => $id,
                'error_message' => $e->getMessage(),
            ]);
            throw new UserDeletionException("Failed to delete user: " . $e->getMessage(), 0, $e);
        }
    }

    protected function isDeadlockOrConnectionException(\Exception $e): bool
    {
        $message = $e->getMessage();
        $code    = $e->getCode();

        return Str::contains($message, [
            'Deadlock found when trying to get lock',
            'Lock wait timeout exceeded',
            'SQLSTATE[HY000] [2002]',
            'Connection refused',
        ]) || in_array($code, ['40001', '40P01']);
    }
}
