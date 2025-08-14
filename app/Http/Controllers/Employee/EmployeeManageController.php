<?php
namespace App\Http\Controllers\Employee;

use App\Exceptions\EmployeeNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\DesgnByDept;
use App\Http\Requests\EmployeeAddressStoreRequest;
use App\Http\Requests\EmployeeBankStoreRequest;
use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use App\Http\Requests\EmployeeEducationStoreRequest;
use App\Http\Requests\EmployeePersonalInfoStoreRequest;
use App\Models\Employee;
use App\Services\BankServiceInterface;
use App\Services\DepartmentServiceInterface;
use App\Services\DesignationServiceInterface;
use App\Services\EmployeeBankService;
use App\Services\EmployeeEducationService;
use App\Services\EmployeeServiceInterface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EmployeeManageController extends Controller
{
    protected $designationService;
    protected $departmentsService;
    protected $employeeService;
    protected $employeeStoreLog;
    protected $educationService;
    protected $bankService;
    protected $employeeBankService;

    public function __construct(DesignationServiceInterface $designationService, DepartmentServiceInterface $departmentsService, EmployeeServiceInterface $employeeService, EmployeeEducationService $educationService, BankServiceInterface $bankService, EmployeeBankService $employeeBankService)
    {
        $this->designationService  = $designationService;
        $this->departmentsService  = $departmentsService;
        $this->employeeService     = $employeeService;
        $this->educationService    = $educationService;
        $this->bankService         = $bankService;
        $this->employeeBankService = $employeeBankService;
        $this->employeeStoreLog    = Log::channel('employeeStoreLog');

    }
    public function index()
    {
        $stats       = $this->employeeService->getEmployeeStats();
        $departments = $this->departmentsService->getAllDepartments();
        $bloodGroups = $this->departmentsService->getBloodGroup();
        $employees   = $this->employeeService->getAllEmployees();

        $employmentTypes = $this->employeeService->getAllEmploymentTypes();

        return view('employees.index', compact('stats', 'departments', 'bloodGroups', 'employees', 'employmentTypes'));
    }

    public function create()
    {
        $companies= $this->employeeService->getCompanies();
        $designations  = $this->designationService->getAllDesignations();
        $departments   = $this->departmentsService->getAllDepartments();
        $employeeTypes = $this->employeeService->getAllEmploymentTypes();


        return view('employees.create', compact('companies','departments', 'designations', 'employeeTypes'));
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
              $companies= $this->employeeService->getCompanies();
            $designations  = $this->designationService->getAllDesignations();
            $departments   = $this->departmentsService->getAllDepartments();
            $employeeTypes = $this->employeeService->getAllEmploymentTypes();

            return view('employees.edit', compact('companies','employee', 'designations', 'departments', 'employeeTypes'));

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

    public function update(EmployeeBasicInfoStoreRequet $request, Employee $employee)
    {
        $employeeName = $request->input('first_name') . ' ' . $request->input('last_name');
        $this->employeeStoreLog->info('Received Employee update request', [
            'employee_id'   => $employee->id,
            'employee_name' => $employeeName,
        ]);

        try {
            // ✅ Corrected argument order
            $updatedEmployee = $this->employeeService->updateEmployee($request, $employee);

            $this->employeeStoreLog->info('Employee updated successfully', [
                'employee_id'   => $updatedEmployee->id,
                'employee_name' => $updatedEmployee->first_name . ' ' . $updatedEmployee->last_name,
            ]);

            return redirect()->route('employees.index')
                ->with('success', 'Employee updated successfully!');

        } catch (ValidationException $e) {
            $this->employeeStoreLog->error('Validation error during Employee update', [
                'employee_id'   => $employee->id,
                'employee_name' => $employeeName,
                'errors'        => $e->errors(),
            ]);

            return redirect()->route('employees.edit', $employee->id)
                ->withInput()
                ->withErrors($e->errors());

        } catch (Exception $e) {
            $this->employeeStoreLog->error('Failed to update Employee', [
                'employee_id'   => $employee->id,
                'employee_name' => $employeeName,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('employees.edit', $employee->id)
                ->withInput()
                ->with('error', 'Employee update failed. Please try again.');
        }
    }

    public function destroy(Employee $employee)
    {
        $employeeName = $employee->first_name . ' ' . $employee->last_name;
        $this->employeeStoreLog->info('Received Employee deletion request', [
            'employee_id'   => $employee->id,
            'employee_name' => $employeeName,
        ]);

        try {

            $this->employeeService->deleteEmployee($employee);

            $this->employeeStoreLog->info('Employee deleted successfully', [
                'employee_id'   => $employee->id,
                'employee_name' => $employeeName,
            ]);

            return redirect()->route('employees.index')
                ->with('success', 'Employee deleted and removed from devices successfully!');

        } catch (Exception $e) {
            $this->employeeStoreLog->error('Failed to delete Employee', [
                'employee_id'   => $employee->id,
                'employee_name' => $employeeName,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('employees.index')
                ->with('error', 'Employee deletion failed. Please try again.');
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

    public function storePersonalAddressInfo(EmployeePersonalInfoStoreRequest $request, Employee $employee)
    {
        try {

            $personalData = $request->all();
            $this->employeeService->addEmployeePersonalInfo($employee, $personalData);

            return redirect()->back()->with('success', 'Info saved successfully!');
        } catch (Exception $e) {

            $this->employeeStoreLog->error('Error saving personal information', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to save personal information. Please try again.');
        }
    }

    public function getAddress(Employee $employee)
    {
        $addresses = $employee->addresses->keyBy('type');

        $permanent = $addresses->get('permanent');
        $current   = $addresses->get('current');

        return view('employees.address_info', compact('employee', 'addresses', 'permanent', 'current'));
    }

    public function storeAddress(EmployeeAddressStoreRequest $request, Employee $employee)
    {

        try {

            $addressData = $request->all();
            $this->employeeService->addEmployeeAddress($employee, $addressData);

            return redirect()->back()->with('success', 'Address saved successfully!');
        } catch (Exception $e) {

            $this->employeeStoreLog->error('Error saving address information', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to save address information. Please try again.');
        }
    }

    public function getEducation(Employee $employee)
    {

        return view('employees.education_info', compact('employee'));
    }

    public function storeEducation(EmployeeEducationStoreRequest $request, Employee $employee)
    {
        try {
            $validatedEducationData = $request->validated('education');

            $this->educationService->syncEducation(
                $employee,
                $validatedEducationData,
                Auth::id() // Pass the authenticated user's ID
            );

            return redirect()->back()->with('success', 'Employee education updated successfully!');

        } catch (Exception $e) {

            \Log::error('Error updating employee education: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Failed to update employee education. Please try again.')->withInput();
        }
    }

    public function getBank(Employee $employee)
    {
        $banks = $this->bankService->getBanks()->load('branches');
        $employee->load(['bankDetails.branch.bank']);
        return view('employees.bank_info', compact('employee', 'banks'));
    }

    public function storeBank(EmployeeBankStoreRequest $request, Employee $employee)
    {
        try {
            $empBankData                = $request->validated();
            $empBankData['employee_id'] = $employee->id;

            $this->employeeBankService->storeEmployeeBank($empBankData);

            return redirect()->back()->with('success', 'Employee bank details saved successfully.');

        } catch (Exception $e) {

            $this->employeeStoreLog->error('Error saving address information', [
                'employee_id' => $employee->id,
                'error'       => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to save address information. Please try again.');
        }

    }

}
