<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\DesgnByDept;
use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use App\Http\Requests\EmployeePersonalInfoStoreRequest;
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
        $stats           = $this->employeeService->getEmployeeStats();
        $departments     = $this->departmentsService->getAllDepartments();
        $bloodGroups     = $this->departmentsService->getBloodGroup();
        $employees       = $this->employeeService->getAllEmployees();
        $employmentTypes = $this->employeeService->getAllEmploymentTypes();

        return view('employees.index', compact('stats', 'departments', 'bloodGroups', 'employees','employmentTypes'));
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

            return redirect()->route('employees.index')->with('success', 'Employee saved and synced to device successfully!');

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
        $employeeId = session('employee_id');
        if (! $employeeId) {
            return redirect()->route('employees.create')->with('error', 'Please create employee first.');
        }

        $genders         = $this->departmentsService->getGender();
        $religions       = $this->departmentsService->getReligion();
        $maritalStatuses = $this->departmentsService->getMaritalStatus();
        $bloodGroups     = $this->departmentsService->getBloodGroup();

        return view('employees.personal_info', compact('employeeId', 'genders', 'religions', 'maritalStatuses', 'bloodGroups'));
    }

    public function getDesignationsByDepartment(DesgnByDept $request)
    {
        $departmentId = $request->input('department_id');

        $designations = $this->designationService->getDesignationByDept($departmentId);

        return response()->json($designations);
    }

    public function storePersonalAddressInfo(EmployeePersonalInfoStoreRequest $request)
    {
        $employeeId = session('employee_id');
        if (! $employeeId) {
            return redirect()
                ->route('employees.create')
                ->with('error', 'Please create employee first.');
        }

        $employee = $this->employeeService->findEmployeeById($employeeId);
        if (! $employee) {
            return redirect()
                ->route('employees.create')
                ->with('error', 'Employee not found.');
        }

        try {
            // Separate data for personal info and address
            $personalData = $request->only([
                'phone_no',
                'email',
                'date_of_birth',
                'gender_id',
                'religion_id',
                'marital_status_id',
                'blood_group_id',
                'national_id',
            ]);

            $addressData = $request->only([
                'type',
                'country',
                'state',
                'city',
                'postal_code',
                'address',
            ]);

            // Call service method (handles transaction)
            $this->employeeService->savePersonalAndAddress($employee, $personalData, $addressData);

            return redirect()->back()->with('success', 'Info & Address saved successfully!');
        } catch (Exception $e) {

            $this->employeeStoreLog->error('Error saving personal/address information', [
                'employee_id' => $employeeId,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to save personal/address information. Please try again.');
        }
    }

}
