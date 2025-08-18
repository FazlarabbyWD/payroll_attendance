<?php
namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\EmployeeServiceInterface;
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

        $date      = $request->report_date;
        $companyId = $request->company_id;

        $attendances = Attendance::with('employee')
            ->whereDate('date', $date)
            ->get();

        dd($attendances);

        // dd($date, $companyId);

        // if ($companyId === 0) {

        //     $attendances = Attendance::with('employee')
        //         ->whereDate('date', $date)
        //         ->get();
        // } else {

        //     $employeeIds = Company::with('employees:id')
        //         ->findOrFail($companyId)
        //         ->employees
        //         ->pluck('id');

        //     $attendances = Attendance::with('employee')
        //         ->whereIn('employee_id', $employeeIds)
        //         ->whereDate('date', $date)
        //         ->get();
        // }

    }
}
