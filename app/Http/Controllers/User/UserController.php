<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Services\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all(); // Fetch all users from the database

        return view('users.index', compact('users')); // Pass the users to the view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

  public function store(UserStoreRequest $request): JsonResponse
    {
        $username = $request->input('username');

        $this->userCrudLog->info("Received user creation request: {$username}", ['username' => $username]);

        try {
            $user = $this->userService->createUser($request);
            $this->userCrudLog->info("User creation request processed successfully: {$username}", ['user_id' => $user->id, 'username' => $username]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], Response::HTTP_CREATED);
        } catch (\Exception $e) {

            $this->userCrudLog->error("Failed to create user {$username} via controller: " . $e->getMessage(), ['error_message' => $e->getMessage(), 'username' => $username, 'trace' => $e->getTraceAsString()]);

            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
