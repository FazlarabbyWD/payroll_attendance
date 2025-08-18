<?php
namespace App\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use App\Http\Requests\LeaveStoreRequest;
use App\Models\Leave;
use App\Services\LeaveServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class LeaveManageController extends Controller
{

    protected $leaveService;
    protected $employeeService;

    public function __construct(LeaveServiceInterface $leaveService)
    {
        $this->leaveService = $leaveService;

    }

    public function index()
    {
        try {

            $leaves = $this->leaveService->getAllLeaves();
            return view('leaves.index', compact('leaves'));
        } catch (\Exception $e) {
            Log::error('Error fetching leaves: ' . $e->getMessage());
            abort(500, 'Failed to fetch leaves.');
        }
    }

    public function create()
    {

        $employees = $this->leaveService->getAllEmployee();

        return view('leaves.create', compact('employees'));
    }

    public function store(LeaveStoreRequest $request)
    {

        try {
            $validatedData = $request->validated();
            $leave         = $this->leaveService->createLeave($validatedData);
            return redirect()->route('leaves.index')->with('success', 'Leave created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating leave: ' . $e->getMessage());
            return redirect()->route('leaves.index')->withInput()->withErrors($request->validator);
        }
    }



    public function edit(Leave $leave)
    {
       $employees = $this->leaveService->getAllEmployee();
        return view('leaves.edit', compact('leave','employees'));
    }

    public function update(LeaveStoreRequest $request, Leave $leave)
    {
        try {
            $validatedData = $request->validated();
            $updatedLeave  = $this->leaveService->updateLeave($leave->id, $validatedData);

            if (! $updatedLeave) {
                return redirect()->back()->withInput()->withErrors(['error' => 'Leave not found.']);
            }

            return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating leave: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update leave.']);
        }
    }

    public function destroy(Leave $leave)
    {
        try {
            $deleted = $this->leaveService->deleteLeave($leave->id);

            if (! $deleted) {
                return redirect()->back()->withErrors(['error' => 'Leave not found.']);
            }

            return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting leave: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to delete leave.']);
        }
    }

    // public function restore(Leave $leave)
    // {
    //     try {
    //         $restored = $this->leaveService->restoreLeave($leave->id);

    //         if (! $restored) {
    //             return redirect()->back()->withErrors(['error' => 'Leave not found or already active.']); // Redirect back with errors
    //         }

    //         return redirect()->route('leaves.index')->with('success', 'Leave restored successfully.'); // Redirect back to index with success message
    //     } catch (\Exception $e) {
    //         Log::error('Error restoring leave: ' . $e->getMessage());
    //         return redirect()->back()->withErrors(['error' => 'Failed to restore leave.']); // Redirect back with errors
    //     }
    // }

    // public function forceDestroy(Leave $leave)
    // {
    //     try {
    //         $forceDeleted = $this->leaveService->forceDeleteLeave($leave->id);

    //         if (! $forceDeleted) {
    //             return redirect()->back()->withErrors(['error' => 'Leave not found.']); // Redirect back with errors
    //         }

    //         return redirect()->route('leaves.index')->with('success', 'Leave permanently deleted.'); // Redirect back to index with success message
    //     } catch (\Exception $e) {
    //         Log::error('Error force deleting leave: ' . $e->getMessage());
    //         return redirect()->back()->withErrors(['error' => 'Failed to permanently delete leave.']); // Redirect back with errors
    //     }
    // }
}
