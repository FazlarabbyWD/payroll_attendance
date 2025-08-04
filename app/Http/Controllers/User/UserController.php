<?php
namespace App\Http\Controllers\User;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\UserServiceInterface;
use App\Exceptions\UserUpdateException;
use App\Http\Requests\UserStoreRequest;
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

    public function store(UserStoreRequest $request): JsonResponse
    {
        $username = $request->input('username');

        $this->userCrudLog->info('Received user creation request', ['username' => $username]);

        try {
            $user = $this->userService->createUser($request);

            $this->userCrudLog->info('User created successfully', [
                'username' => $user->username,
                'user_id'  => $user->id,
            ]);

            return response()->json([
                'message' => 'User created successfully',
                'user'    => $user,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            $this->userCrudLog->error('Failed to create user', [
                'username'      => $username,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to create user',
                'error'   => $e->getMessage(),
            ], 500);
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

            return response()->json([
                'message' => 'User retrieved successfully',
                'user' => $user,
            ], 200);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            $this->userCrudLog->error("Failed to retrieve user for editing", [
                'user_id' => $id,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to retrieve user for editing',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


       public function update(UserUpdateRequest $request, string $id)
    {

        dd($request->all());

        $id = $request->input('user_id');



        try {
            $user = $this->userService->updateUser($request, $id);

            return redirect()->route('users.index')->with('success', 'User updated successfully!');

        } catch (UserNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);

        } catch (UserUpdateException $e) {
            return response()->json([
                'message' => 'Failed to update user',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Exception $e) {
            $this->userCrudLog->error("Failed to update user", [
                'user_id' => $id,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to update user',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy(string $id)
    {
        try {
            $this->userService->deleteUser($id);

            return redirect()->route('users.index')->with('success', 'User deleted successfully!');

        } catch (UserNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);

        } catch (UserDeletionException $e) {
            return response()->json([
                'message' => 'Failed to delete user',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Exception $e) {
            $this->userCrudLog->error("Failed to delete user", [
                'user_id' => $id,
                'error_message' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Failed to delete user',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(string $id)
    {
        //
    }
}
