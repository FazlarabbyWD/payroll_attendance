@extends('app')

@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Attendance Overview Widgets -->
    <div class="card mb-6">
        <div class="card-widget-separator-wrapper">
            <div class="card-body card-widget-separator">
                <div class="row gy-4 gy-sm-1">

                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                            <div>
                                <h4 class="mb-0">{{ $totalEmployee }}</h4>
                                <p class="mb-0">Employees Total</p>
                            </div>
                            <div class="avatar me-sm-6">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="icon-base ri ri-user-line text-heading icon-26px"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6">
                    </div>

                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
                            <div>
                                <h4 class="mb-0">{{ $attendances->count() }}</h4>
                                <p class="mb-0">Employees Present</p>
                            </div>
                            <div class="avatar me-sm-6">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="icon-base ri ri-user-line text-heading icon-26px"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none me-6">
                    </div>


                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                            <div>
                                <h4 class="mb-0">{{ $totalWorkingHours }}</h4>
                                <p class="mb-0">Total Working Hours</p>
                            </div>
                            <div class="avatar me-lg-6">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="icon-base ri ri-clock-line text-heading icon-26px"></i>
                                </span>
                            </div>
                        </div>
                        <hr class="d-none d-sm-block d-lg-none">
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div
                            class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                            <div>
                                <h4 class="mb-0">{{ $averageWorkingHours }}</h4>
                                <p class="mb-0">Average Working Hours</p>
                            </div>
                            <div class="avatar me-sm-6">
                                <span class="avatar-initial rounded bg-label-secondary">
                                    <i class="icon-base ri ri-calculator-line text-heading icon-26px"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Attendance List Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                Daily Attendance Report -
                <span style="color:#8C57FF">{{ $date->format('d M Y') }}</span>
            </h5>


            <!-- Search Filters -->
            <div class="d-flex">
                <div class="input-group input-group-merge me-2">
                    <span class="input-group-text"><i class="ri ri-search-line"></i></span>
                    <input type="search" class="form-control form-control-sm" id="employeeIdSearchInput"
                        placeholder="Search by ID">
                </div>
                <div class="input-group input-group-merge me-2">
                    <span class="input-group-text"><i class="ri ri-search-line"></i></span>
                    <input type="text" class="form-control form-control-sm" id="nameSearchInput"
                        placeholder="Search by Name">
                </div>

                <div class="input-group input-group-merge me-2">
                    <span class="input-group-text"><i class="icon-base ri ri-delete-bin-7-line icon-md"></i></span>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="resetButton">Reset</button>
                </div>


            </div>

        </div>

        <div class="card-datatable table-responsive">
            <div id="DataTables_Table_0_wrapper" class="dt-container dt-bootstrap5">
                <div class="justify-content-between dt-layout-table">
                    <div class="d-md-flex justify-content-between align-items-center dt-layout-full table-responsive">
                        <table class="invoice-list-table table dataTable dtr-column" id="DataTables_Table_0"
                            aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Working Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr class="attendance-row">

                                    <td>
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded-circle bg-label-primary">{{
                                                    $attendance->employee->employee_id }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <img src="{{ asset('resources/assets/img/avatars/2.png') }}"
                                                    alt="Avatar" class="rounded-circle">
                                            </div>
                                            <span class="fw-medium">
                                                {{ $attendance->employee->first_name }} {{
                                                $attendance->employee->last_name }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="text-truncate d-flex align-items-center text-heading">
                                            <span
                                                class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-info me-4">
                                                <i class="icon-base ri ri-home-6-line icon-18px"></i>
                                            </span>
                                            {{ $attendance->check_in ?
                                            \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-flex align-items-center text-heading">
                                            <span
                                                class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-success me-4">
                                                <i class="icon-base ri ri-footprint-line icon-18px"></i>
                                            </span>
                                            {{ $attendance->check_out ?
                                            \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : 'N/A' }}
                                        </span>
                                    </td>



                                    <td class=""><span class="badge rounded-pill bg-label-warning px-2 py-1_5"><i
                                                class="icon-base ri ri-line-chart-line icon-16px my-50"></i>
                                            {{ $attendance->total_minutes ? number_format($attendance->total_minutes /
                                            60,
                                            2) . ' hrs' : '0 hrs' }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No attendance records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const employeeIdSearchInput = document.getElementById('employeeIdSearchInput');
        const nameSearchInput = document.getElementById('nameSearchInput');
        const tableRows = Array.from(document.querySelectorAll('#DataTables_Table_0 tbody tr'));

        function filterTable() {
            const employeeIdSearchTerm = employeeIdSearchInput.value.toLowerCase();
            const nameSearchTerm = nameSearchInput.value.toLowerCase();

            tableRows.forEach(row => {
                const employeeId = row.cells[0].textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();

                const isEmployeeIdMatch = employeeId.includes(employeeIdSearchTerm);
                const isNameMatch = name.includes(nameSearchTerm);

                row.style.display = (isEmployeeIdMatch && isNameMatch) ? "" : "none";
            });
        }

        function resetFilter() {
            employeeIdSearchInput.value = "";
            nameSearchInput.value = "";
            filterTable();
        }

        employeeIdSearchInput.addEventListener('input', filterTable);
        nameSearchInput.addEventListener('input', filterTable);
        document.getElementById('resetButton').addEventListener('click', resetFilter);
    });
</script>
@endpush
