<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\DesgnByDept;
use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use App\Services\DepartmentServiceInterface;
use App\Services\DesignationServiceInterface;
use App\Services\EmployeeServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EmployeeManageController extends Controller
{
    protected $designationService;
    protected $departmentsService;
    protected $employeeService;
    protected $employeeStoreLog;

    public function __construct(DesignationServiceInterface $designationService, DepartmentServiceInterface $departmentsService, EmployeeServiceInterface $employeeService)
    {
        $this->designationService = $designationService;
        $this->departmentsService = $departmentsService;
        $this->employeeService    = $employeeService;
        $this->employeeStoreLog   = Log::channel('employeeStoreLog');

    }
    public function index()
    {
        return view('employees.index');
    }

    public function create()
    {
        $designations  = $this->designationService->getAllDesignations();
        $departments   = $this->departmentsService->getAllDepartments();
        $employeeTypes = $this->employeeService->getAllEmploymentTypes();

        return view('employees.create', compact('departments', 'designations', 'employeeTypes'));
    }

    
    public function store(EmployeeBasicInfoStoreRequet $request)
    {
        $employeeName = $request->input('first_name') . ' ' . $request->input('last_name');
        $this->employeeStoreLog->info('Received Employee creation request', ['employee_name' => $employeeName]);

        try {
            $employee = $this->employeeService->createAndAddToDevice($request);

            $this->employeeStoreLog->info('Employee created and added to device successfully', [
                'employee_id'   => $employee->id,
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
            ]);

            return redirect()->route('employees.personal-info', ['employee_id' => $employee->id])
                ->with('success', 'Employee saved and synced to device successfully!');

        } catch (ValidationException $e) {
            $this->employeeStoreLog->error('Validation error during Employee creation', [
                'employee_name' => $employeeName,
                'errors'        => $e->errors(),
            ]);
            return redirect()->route('employees.create')->withInput()->withErrors($e->errors());

        } catch (Exception $e) {
            $this->employeeStoreLog->error('Failed to create Employee and add to device', [
                'employee_name' => $employeeName,
                'error_message' => $e->getMessage(),
            ]);
            return redirect()->route('employees.create')->withInput()->with('error', 'Employee creation or device sync failed. Please try again.');
        }
    }



    public function showPersonalInfoForm()
    {

        $genders         = $this->departmentsService->getGender();
        $religions       = $this->departmentsService->getReligion();
        $maritalStatuses = $this->departmentsService->getMaritalStatus();
        $bloodGroups     = $this->departmentsService->getBloodGroup();

        return view('employees.personal_info', compact('genders', 'religions', 'maritalStatuses', 'bloodGroups'));
    }

    public function getDesignationsByDepartment(DesgnByDept $request)
    {
        $departmentId = $request->input('department_id');

        $designations = $this->designationService->getDesignationByDept($departmentId);

        return response()->json($designations);
    }
}
