<?php
namespace App\Http\Controllers\Employee;

use Exception;
use App\Models\Employee;
use App\Http\Requests\DesgnByDept;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\EmployeeServiceInterface;
use App\Services\DepartmentServiceInterface;
use App\Exceptions\EmployeeNotFoundException;
use App\Services\DesignationServiceInterface;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use App\Http\Requests\EmployeePersonalInfoStoreRequest;

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

        return view('employees.index', compact('stats', 'departments', 'bloodGroups', 'employees', 'employmentTypes'));
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

    public function edit(Employee $employee)
    {
        try {
            $designations  = $this->designationService->getAllDesignations();
            $departments   = $this->departmentsService->getAllDepartments();
            $employeeTypes = $this->employeeService->getAllEmploymentTypes();

            return view('employees.edit', compact('employee','designations','departments','employeeTypes'));

        } catch (EmployeeNotFoundException $e) {
            return redirect()->route('employees.index')->withErrors([
                'error' => $e->getMessage(),
            ]);

        } catch (Exception $e) {
            $this->employeeStoreLog->error("Failed to retrieve Employee for Update Info", [
                'employee_id'   => $employee->id,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('employees.index')->withErrors([
                'error' => 'Failed to retrieve Employee for Update Info: ' . $e->getMessage(),
            ]);
        }
    }

    public function showPersonalInfoForm(Employee $employee)
    {
        $genders         = $this->departmentsService->getGender();
        $religions       = $this->departmentsService->getReligion();
        $maritalStatuses = $this->departmentsService->getMaritalStatus();
        $bloodGroups     = $this->departmentsService->getBloodGroup();

        return view('employees.personal_info', compact('employee', 'genders', 'religions', 'maritalStatuses', 'bloodGroups'));
    }

    public function getDesignationsByDepartment(DesgnByDept $request)
    {
        $departmentId = $request->input('department_id');

        $designations = $this->designationService->getDesignationByDept($departmentId);

        return response()->json($designations);
    }

    public function storePersonalAddressInfo(EmployeePersonalInfoStoreRequest $request,Employee $employee)
    {
        try {
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

            $this->employeeService->savePersonalAndAddress($employee, $personalData, $addressData);

            return redirect()->back()->with('success', 'Info & Address saved successfully!');
        } catch (Exception $e) {

            $this->employeeStoreLog->error('Error saving personal/address information', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to save personal/address information. Please try again.');
        }
    }

}
