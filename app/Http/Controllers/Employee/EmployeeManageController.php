<?php
namespace App\Http\Controllers\Employee;

use App\Http\Requests\EmployeeBasicInfoStoreRequet;
use App\Models\Gender;
use App\Models\Religion;
use App\Models\BloodGroup;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\MaritalStatus;

use App\Http\Controllers\Controller;
use App\Services\EmployeeServiceInterface;
use App\Services\DepartmentServiceInterface;
use App\Services\DesignationServiceInterface;

class EmployeeManageController extends Controller
{
    protected $designationService;
    protected $departmentsService;
    protected $employeeService;

    public function __construct(DesignationServiceInterface $designationService, DepartmentServiceInterface $departmentsService, EmployeeServiceInterface $employeeService)
    {
        $this->designationService = $designationService;
        $this->departmentsService = $departmentsService;
        $this->employeeService = $employeeService;

    }
    public function index()
    {
        return view('employees.index');
    }

    public function create()
    {
        $designations  = $this->designationService->getAllDesignations();
        $departments   = $this->departmentsService->getAllDepartments();
        $employeeTypes =$this->employeeService->getAllEmploymentTypes();

        return view('employees.create', compact('departments', 'designations', 'employeeTypes'));
    }

    public function store(EmployeeBasicInfoStoreRequet $request)
    {
        dd($request->all());

        return redirect()->route('employees.personal-info');
    }

    public function showPersonalInfoForm()
    {
        // dd('hello');

        $genders         = Gender::all();
        $religions       = Religion::all();
        $maritalStatuses = MaritalStatus::all();
        $bloodGroups     = BloodGroup::all();

        return view('employees.personal_info', compact('genders', 'religions', 'maritalStatuses', 'bloodGroups'));
    }

    public function getDesignationsByDepartment(Request $request)
    {
        $departmentId = $request->input('department_id');

        $department = Department::with('designations')->find($departmentId);

        if (! $department) {
            return response()->json([]);
        }

        return response()->json($department->designations);
    }
}
