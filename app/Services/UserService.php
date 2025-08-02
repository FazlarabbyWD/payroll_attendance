<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Database\QueryException;
use App\Repositories\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    protected $userRepository;
    protected $userCrudLog;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userCrudLog = Log::channel('userStoreLog');
    }

    public function createUser(UserStoreRequest $request): User
    {
        $maxRetries = 3;
        $retryDelay = 100;
        $username = $request->input('username');

        $this->userCrudLog->info("Attempting to create user: {$username}", ['attempt' => 1, 'username' => $username]);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $userData = $request->validated();
                $profilePhoto = $request->file('profile_photo');

                $user = $this->userRepository->create($userData, $profilePhoto);

                $this->userCrudLog->info("User created successfully: {$username}", ['attempt' => $attempt, 'user_id' => $user->id, 'username' => $username]);

                return $user;

            } catch (QueryException $e) {
                if ($this->isDeadlockException($e) || $this->isConnectionException($e)) {
                    $this->userCrudLog->warning("Deadlock or connection error on attempt {$attempt} for user {$username}. Retrying after {$retryDelay}ms.", ['attempt' => $attempt, 'error_message' => $e->getMessage(), 'username' => $username]);

                    usleep($retryDelay * 1000);
                    $retryDelay *= 2;
                    continue;
                }

                $this->userCrudLog->error("Database error during user creation for {$username}: " . $e->getMessage(), ['attempt' => $attempt, 'error_message' => $e->getMessage(), 'username' => $username]);

                throw $e;
            } catch (\Exception $e) {
                $this->userCrudLog->error("Error creating user {$username}: " . $e->getMessage(), ['attempt' => $attempt, 'error_message' => $e->getMessage(), 'username' => $username]); // Log other error

            }
          }

        $this->userCrudLog->error("Failed to create user {$username} after {$maxRetries} attempts.", ['username' => $username]); 
        throw new \Exception("Failed to create user after {$maxRetries} attempts.");
    }

    protected function isDeadlockException(\Exception $e): bool
    {
        $message = $e->getMessage();
        $deadlockErrorCodes = [
            '40001',
            '40P01',
        ];
        return Str::contains($message, [
            'Deadlock found when trying to get lock',
            'Lock wait timeout exceeded',
        ]) || in_array(optional($e->getCode()), array_merge($deadlockErrorCodes));
    }

    protected function isConnectionException(\Exception $e): bool
    {
        $message = $e->getMessage();

        return Str::contains($message, [
            'SQLSTATE[HY000] [2002]',
            'Connection refused',
        ]);
    }
}
