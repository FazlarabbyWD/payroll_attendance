<?php
namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\UserServiceInterface;
use App\Exceptions\UserUpdateException;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Exceptions\UserDeletionException;
use App\Exceptions\UserNotFoundException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;
    protected $userCrudLog;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
        $this->userCrudLog = Log::channel('userStoreLog');
    }

   public function store(UserStoreRequest $request): RedirectResponse
{
    $username = $request->input('username');

    $this->userCrudLog->info('Received user creation request', ['username' => $username]);

    try {
        $user = $this->userService->createUser($request);

        $this->userCrudLog->info('User created successfully', [
            'username' => $user->username,
            'user_id'  => $user->id,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');

    } catch (\Exception $e) {
        $this->userCrudLog->error('Failed to create user', [
            'username'      => $username,
            'error_message' => $e->getMessage(),
            'trace'         => $e->getTraceAsString(),
        ]);

        return redirect()->back()->withInput()->withErrors([
            'error' => 'Failed to create user: ' . $e->getMessage(),
        ]);
    }
}

    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }



public function edit(string $id)
{
    try {
        $user = $this->userService->findUser($id);

        return view('users.edit', compact('user'));

    } catch (UserNotFoundException $e) {
        return redirect()->route('users.index')->withErrors([
            'error' => $e->getMessage(),
        ]);

    } catch (\Exception $e) {
        $this->userCrudLog->error("Failed to retrieve user for editing", [
            'user_id' => $id,
            'error_message' => $e->getMessage(),
        ]);

        return redirect()->route('users.index')->withErrors([
            'error' => 'Failed to retrieve user for editing: ' . $e->getMessage(),
        ]);
    }
}


 public function update(UserUpdateRequest $request, string $id)
{
    try {
        $user = $this->userService->updateUser($request, $id);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');

    } catch (UserNotFoundException $e) {
        return redirect()->route('users.index')->withErrors([
            'error' => $e->getMessage(),
        ]);

    } catch (UserUpdateException $e) {
        return redirect()->back()->withInput()->withErrors([
            'error' => 'Failed to update user: ' . $e->getMessage(),
        ]);

    } catch (\Exception $e) {
        $this->userCrudLog->error("Failed to update user", [
            'user_id' => $id,
            'error_message' => $e->getMessage(),
        ]);

        return redirect()->back()->withInput()->withErrors([
            'error' => 'Unexpected error occurred while updating user.',
        ]);
    }
}


public function destroy(string $id)
{
    try {
        $this->userService->deleteUser($id);

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');

    } catch (UserNotFoundException $e) {
        return redirect()->route('users.index')->withErrors([
            'error' => $e->getMessage(),
        ]);

    } catch (UserDeletionException $e) {
        return redirect()->route('users.index')->withErrors([
            'error' => 'Failed to delete user: ' . $e->getMessage(),
        ]);

    } catch (\Exception $e) {
        $this->userCrudLog->error("Failed to delete user", [
            'user_id' => $id,
            'error_message' => $e->getMessage(),
        ]);

        return redirect()->route('users.index')->withErrors([
            'error' => 'Unexpected error occurred while deleting user.',
        ]);
    }
}

}
