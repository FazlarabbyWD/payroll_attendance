<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee;
use App\Services\EmployeeServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyAttendanceReportController extends Controller
{
    protected $employeeService;
    public function __construct(EmployeeServiceInterface $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function requestReport()
    {
        $companies = $this->employeeService->getCompanies();

        return view('reports.daily-report.request', compact('companies'));
    }

    public function showReport(Request $request)
    {
        $request->validate([
            'report_date' => 'required|date',
            'company_id'  => 'required|integer',
        ]);

        if ($request->company_id != 0 && ! \DB::table('companies')->where('id', $request->company_id)->exists()) {
            return back()->withErrors(['company_id' => 'The selected concern is invalid.']);
        }

        $date      = Carbon::parse($request->report_date);
        $companyId = $request->company_id;

        if ($companyId == 0) {
            // All companies → all employees
            $attendances = Attendance::with('employee')
                ->whereDate('date', $date)
                ->orderBy('employee_id', 'asc')
                ->get();

            $totalEmployee = Employee::count();
        } else {
            $employeeIds = Company::with('employees:id')
                ->findOrFail($companyId)
                ->employees
                ->pluck('id');

            $actualIds = Employee::findMany($employeeIds)->pluck('employee_id');

            $attendances = Attendance::with('employee')
                ->whereIn('employee_id', $actualIds)
                ->whereDate('date', $date)
                ->get();

            $totalEmployee = $actualIds->count();
        }

        $totalMinutes      = $attendances->sum('total_minutes');
        $totalWorkingHours = number_format($totalMinutes / 60, 2);

        $employeeCount       = $attendances->count();
        $averageWorkingHours = $employeeCount > 0
        ? number_format($totalWorkingHours / $employeeCount, 2)
        : 0;

        return view('reports.daily-report.show', compact(
            'attendances',
            'date',
            'totalWorkingHours',
            'averageWorkingHours',
            'totalEmployee'
        ));
    }

}
