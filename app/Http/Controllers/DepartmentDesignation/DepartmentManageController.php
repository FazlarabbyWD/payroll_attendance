<?php
namespace App\Http\Controllers\DepartmentDesignation;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\DepartmentServiceInterface;
use App\Exceptions\DepartmentUpdateException;
use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;
use App\Exceptions\DepartmentDeletionException;
use App\Exceptions\DepartmentNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DepartmentManageController extends Controller
{
    protected $departmentsService;
    protected $departmentCrudLog;

    public function __construct(DepartmentServiceInterface $departmentsService)
    {
        $this->departmentsService = $departmentsService;
        $this->departmentCrudLog  = Log::channel('departmentStoreLog');
    }
    public function index()
    {
        $departments = $this->departmentsService->getAllDepartments();

        return view('departments.index', compact('departments'));

    }
    public function store(DepartmentStoreRequest $request): RedirectResponse
    {
        $departmentName = $request->input('name');

        $this->departmentCrudLog->info('Received Department creation request', ['department_name' => $departmentName]);

        try {
            $department = $this->departmentsService->createDepartment($request);

            $this->departmentCrudLog->info('Department created successfully', [
                'department_name' => $department->department_name,
                'department_id'   => $department->id,
            ]);

            return redirect()->route('departments.index')->with('success', 'Department created successfully!');
        } catch (\Exception $e) {
            $this->departmentCrudLog->error('Failed to create department', [
                'department_name' => $departmentName,
                'error_message'   => $e->getMessage(),
                'trace'           => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->withErrors([
                'error' => 'Failed to create department: ' . $e->getMessage(),
            ]);
        }
    }

    public function edit(string $id)
    {
        try {
            $department = $this->departmentsService->findDepartment($id);

            return view('departments.edit', compact('department'));

        } catch (DepartmentNotFoundException $e) {
            return redirect()->route('departments.index')->withErrors([
                'error' => $e->getMessage(),
            ]);

        } catch (\Exception $e) {
            $this->departmentCrudLog->error("Failed to retrieve Department for editing", [
                'department_id'       => $id,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('departments.index')->withErrors([
                'error' => 'Failed to retrieve department for editing: ' . $e->getMessage(),
            ]);
        }
    }




  public function update(DepartmentUpdateRequest $request, string $id): RedirectResponse
    {
        try {
            $department = $this->departmentsService->updateDepartment($request, $id);  // Use the service method

            $this->departmentCrudLog->info('Department updated successfully', [
                'department_name' => $department->name,
                'department_id'   => $department->id,
            ]);

            return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
        } catch (DepartmentNotFoundException $e) {
            return redirect()->route('departments.index')->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (DepartmentUpdateException $e) {
            return redirect()->back()->withInput()->withErrors([
                'error' => 'Failed to update department: ' . $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            $this->departmentCrudLog->error("Failed to update department", [
                'department_id'   => $id,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            return redirect()->back()->withInput()->withErrors([
                'error' => 'Unexpected error occurred while updating department.',
            ]);
        }
    }

  public function destroy(string $id): RedirectResponse
    {
        try {
            $this->departmentsService->deleteDepartment($id); // Use the service method

            $this->departmentCrudLog->info('Department deleted successfully', [
                'department_id' => $id,
            ]);

            return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
        } catch (DepartmentNotFoundException $e) {
            return redirect()->route('departments.index')->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (DepartmentDeletionException $e) {
            return redirect()->route('departments.index')->withErrors([
                'error' => 'Failed to delete department: ' . $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            $this->departmentCrudLog->error("Failed to delete department", [
                'department_id'   => $id,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            return redirect()->route('departments.index')->withErrors([
                'error' => 'Unexpected error occurred while deleting department.',
            ]);
        }
    }
}
