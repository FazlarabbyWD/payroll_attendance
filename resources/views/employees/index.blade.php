@extends('app')
@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif


    @if(session('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-6 mb-6">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Total Employee</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-2">{{ $stats['employees'] }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-primary rounded">
                                <div class="icon-base ri ri-group-line icon-26px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Department</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-2">{{ $stats['departments'] }}</h4>

                            </div>

                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-danger rounded">
                                <div class="icon-base ri ri-user-add-line icon-26px scaleX-n1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Verified Employee</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-2">{{ $stats['verifiedemployee'] }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-success rounded">
                                <div class="icon-base ri ri-user-follow-line icon-26px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="me-1">
                            <p class="text-heading mb-1">Pending Verification</p>
                            <div class="d-flex align-items-center">
                                <h4 class="mb-1 me-2">{{ $stats['pendingVerification'] }}</h4>
                            </div>
                        </div>
                        <div class="avatar">
                            <div class="avatar-initial bg-label-warning rounded">
                                <div class="icon-base ri ri-user-search-line icon-26px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Users List Table -->
    <div class="card">
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-custom-icon">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);">Employee</a>
                            <i class="breadcrumb-icon icon-base ri ri-arrow-right-circle-line align-middle"></i>
                        </li>
                        <li class="breadcrumb-item active">List</li>
                    </ol>
                </nav>
                <div class="dt-buttons btn-group flex-wrap">
                    <a href="{{ route('employees.create') }}" class="btn add-new btn-primary" tabindex="0">
                        <span>
                            <i class="icon-base ri ri-add-line icon-sm me-0 me-sm-2 d-sm-none d-inline-block"></i>
                            <span class="d-none d-sm-inline-block">Add New Employee</span>
                        </span>
                    </a>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center row pt-4 pb-2 gap-4 gap-md-0 gx-5">
                <div class="col-md-4 employee_dept">
                    <select id="EmployeeDept" class="form-select text-capitalize" name="department_id">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ ucfirst($department->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 employment_type">
                    <select id="EMploymentType" class="form-select text-capitalize" name="employment_type_id">
                        <option value="">Employment Type</option>
                        @foreach ($employmentTypes as $employmentType)
                        <option value="{{ $employmentType->id }}">{{ ucfirst($employmentType->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 employee_blood_group">
                    <select id="EmployeeBlood" class="form-select text-capitalize" name="blood_group_id">
                        <option value="">Select Blood Group</option>
                        @foreach ($bloodGroups as $bloodGroup )
                        <option value="{{ $bloodGroup->id }}">{{ ucfirst($bloodGroup->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="card-datatable">
            <div id="DataTables_Table_0_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">

                <div class="row m-2 my-0 mt-2 mb-2 justify-content-between align-items-center">
                    <div class="col d-flex flex-wrap align-items-center gap-2">
                        <div>
                            <input type="search" class="form-control form-control-sm" id="employeeIdSearchInput"
                                placeholder="Employee ID">
                        </div>

                        <div>
                            <input type="text" class="form-control form-control-sm" id="nameSearchInput"
                                placeholder="Name">
                        </div>

                        <div>
                            <input type="search" class="form-control form-control-sm" id="phoneSearchInput"
                                placeholder="Phone">
                        </div>

                        <div>
                            <input type="search" class="form-control form-control-sm" id="emailSearchInput"
                                placeholder="Email">
                        </div>

                        <div>
                            <select id="statusFilter" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div>
                            <button type="button" class="btn btn-secondary btn-sm" id="resetButton">RESET</button>
                        </div>

                    </div>
                </div>


                <div class="justify-content-between dt-layout-table">
                    <div class="d-md-flex justify-content-between align-items-center dt-layout-full">
                        <table class="datatables-users table dataTable dtr-column table-responsive"
                            id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                            <colgroup>
                                <col style="width: 80px;">
                                <!-- EMP ID -->
                                <col style="width: 150px;">
                                <!-- NAME -->
                                <col style="width: 120px;">
                                <!-- DEPARTMENT -->
                                <col style="width: 150px;">
                                <!-- DESIGNATION -->
                                <col style="width: 100px;">
                                <!-- PHONE -->
                                <col style="width: 80px;">
                                <!-- BLOOD GROUP -->
                                <col style="width: 80px;">
                                <!-- STATUS -->
                                <col style="width: 120px;">
                                <!-- ACTION -->
                            </colgroup>
                            <thead>
                                <tr>
                                    <th data-dt-column="0" class="control dt-orderable-none dtr-hidden" rowspan="1"
                                        colspan="1" aria-label="" style="display: none;"><span
                                            class="dt-column-title"></span><span class="dt-column-order"></span></th>

                                    <th data-dt-column="1" class="dt-orderable-asc" tabindex="0">
                                        <span class="dt-column-title" role="button">EMP ID</span>
                                    </th>
                                    <th data-dt-column="2" class="dt-orderable-asc" tabindex="0">
                                        <span class="dt-column-title" role="button">Name</span>
                                    </th>
                                    <th data-dt-column="3" class="dt-orderable-asc" tabindex="0">
                                        <span class="dt-column-title" role="button">Department</span>
                                    </th>
                                    <th data-dt-column="4" class="dt-orderable-asc" tabindex="0">
                                        <span class="dt-column-title" role="button">Designation</span>
                                    </th>
                                    <th data-dt-column="5" class="dt-orderable-asc" tabindex="0">
                                        <span class="dt-column-title" role="button">Phone</span>
                                    </th>
                                    <th data-dt-column="6" class="dt-orderable-asc" tabindex="0">
                                        <span class="dt-column-title" role="button">Blood</span>
                                    </th>
                                    <th data-dt-column="7" class="dt-orderable-asc" tabindex="0">
                                        <span class="dt-column-title" role="button">Status</span>
                                    </th>
                                    <th data-dt-column="8" class="dt-orderable-none" aria-label="Actions">
                                        <span class="dt-column-title">Actions</span>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($employees as $employee)
                                <tr>
                                    <td class="control dtr-hidden" tabindex="0" style="display: none;"></td>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar avatar-sm me-4">
                                                    <img src="{{ asset('/resources/assets/img/avatars/2.png') }}"
                                                        alt="Avatar" class="rounded-circle">
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="app-user-view-account.html" class="text-heading text-truncate">
                                                    <span class="fw-medium">{{ $employee->first_name }} {{
                                                        $employee->last_name }}</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                    <!-- Assuming you have a Department relationship -->
                                    <td>{{ $employee->designation->name ?? 'N/A' }}</td>
                                    <!-- Assuming you have a Designation relationship -->
                                    <td>{{ $employee->phone_no ?? 'N/A' }}</td>
                                    <td>{{ $employee->bloodGroup->name ?? 'N/A' }}</td>
                                    <!-- Assuming you have a BloodGroup relationship -->
                                    <td>
                                        <span class="badge rounded-pill bg-label-info">
                                            {{ $employee->employmentStatus->name }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:;"
                                                class="btn btn-icon btn-text-secondary rounded-pill delete-record">
                                                <i class="icon-base ri ri-delete-bin-7-line icon-md"></i>
                                            </a>
                                            <a href="app-user-view-account.html"
                                                class="btn btn-icon btn-text-secondary rounded-pill">
                                                <i class="icon-base ri ri-eye-line icon-md"></i>
                                            </a>
                                            <a href="javascript:;"
                                                class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="icon-base ri ri-more-2-line icon-md"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end m-0">
                                                <a href="javascript:;" class="dropdown-item">Edit</a>
                                                <a href="javascript:;" class="dropdown-item">Suspend</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>


                <div class="row mx-3 mt-3 justify-content-between">
                    <div
                        class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-md-0 mt-5">
                        <div class="dt-info" aria-live="polite" id="DataTables_Table_0_info" role="status">Showing
                            {{ $employees->firstItem() }} to
                            {{ $employees->lastItem() }} of
                            {{ $employees->total() }} entries
                        </div>
                    </div>
                    <div
                        class="d-md-flex align-items-center dt-layout-end col-md-auto ms-auto d-flex gap-md-4 justify-content-md-between justify-content-center gap-md-2 flex-wrap mt-0">
                        <div class="dt-paging">
                            <nav aria-label="pagination">
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($employees->onFirstPage())
                                    <li class="dt-paging-button page-item disabled">
                                        <button class="page-link first" role="link" type="button" disabled
                                            aria-disabled="true" aria-label="First" data-dt-idx="first" tabindex="-1">
                                            <i class="icon-base ri ri-skip-back-mini-line scaleX-n1-rtl icon-22px"></i>
                                        </button>
                                    </li>
                                    <li class="dt-paging-button page-item disabled">
                                        <button class="page-link previous" role="link" type="button" disabled
                                            aria-disabled="true" aria-label="Previous" data-dt-idx="previous"
                                            tabindex="-1">
                                            <i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i>
                                        </button>
                                    </li>
                                    @else
                                    <li class="dt-paging-button page-item">
                                        <a class="page-link first" href="{{ $employees->url(1) }}" rel="prev"
                                            aria-label="First">
                                            <i class="icon-base ri ri-skip-back-mini-line scaleX-n1-rtl icon-22px"></i>
                                        </a>
                                    </li>
                                    <li class="dt-paging-button page-item">
                                        <a class="page-link previous" href="{{ $employees->previousPageUrl() }}"
                                            rel="prev" aria-label="Previous">
                                            <i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i>
                                        </a>
                                    </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($employees->getUrlRange(max(1, $employees->currentPage() - 2),
                                    min($employees->lastPage(), $employees->currentPage() + 2)) as $page => $url)
                                    @if ($page == $employees->currentPage())
                                    <li class="dt-paging-button page-item active">
                                        <button class="page-link" role="link" type="button" aria-current="page"
                                            data-dt-idx="{{ $page - 1 }}">{{ $page }}</button>
                                    </li>
                                    @else
                                    <li class="dt-paging-button page-item">
                                        <a class="page-link" href="{{ $url }}" data-dt-idx="{{ $page - 1 }}">{{ $page
                                            }}</a>
                                    </li>
                                    @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($employees->hasMorePages())
                                    <li class="dt-paging-button page-item">
                                        <a class="page-link next" href="{{ $employees->nextPageUrl() }}" rel="next"
                                            aria-label="Next">
                                            <i class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i>
                                        </a>
                                    </li>
                                    <li class="dt-paging-button page-item">
                                        <a class="page-link last" href="{{ $employees->url($employees->lastPage()) }}"
                                            rel="next" aria-label="Last">
                                            <i
                                                class="icon-base ri ri-skip-forward-mini-line scaleX-n1-rtl icon-22px"></i>
                                        </a>
                                    </li>
                                    @else
                                    <li class="dt-paging-button page-item disabled">
                                        <button class="page-link next" role="link" type="button" disabled
                                            aria-disabled="true" aria-label="Next" data-dt-idx="next">
                                            <i class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i>
                                        </button>
                                    </li>
                                    <li class="dt-paging-button page-item disabled">
                                        <button class="page-link last" role="link" type="button" disabled
                                            aria-disabled="true" aria-label="Last" data-dt-idx="last">
                                            <i
                                                class="icon-base ri ri-skip-forward-mini-line scaleX-n1-rtl icon-22px"></i>
                                        </button>
                                    </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
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
        // Get references to the input and select elements
        const employeeIdSearchInput = document.getElementById('employeeIdSearchInput');
        const nameSearchInput = document.getElementById('nameSearchInput');
        const phoneSearchInput = document.getElementById('phoneSearchInput');
        const emailSearchInput = document.getElementById('emailSearchInput');
        const statusFilter = document.getElementById('statusFilter');


        // Get all the table rows
        const tableRows = Array.from(document.querySelectorAll('table tbody tr'));

        // Function to filter the table rows
        function filterTable() {
            const employeeIdSearchTerm = employeeIdSearchInput.value.toLowerCase();
            const nameSearchTerm = nameSearchInput.value.toLowerCase();
            const phoneSearchTerm = phoneSearchInput.value.toLowerCase();
            const emailSearchTerm = emailSearchInput.value.toLowerCase();
            const statusValue = statusFilter.value;



            tableRows.forEach(row => {
                // Get the data from the row. Adjust indices based on actual table structure!
                const employeeId = row.cells[1].textContent.toLowerCase(); // EMP ID
                const name = row.cells[2].querySelector('.fw-medium').textContent.toLowerCase(); // Name
                const phone = row.cells[5].textContent.toLowerCase(); // Phone
                const email = row.cells[2].querySelector('a').textContent.toLowerCase(); // Email, get from <a> tag
                const statusText = row.cells[7].textContent.toLowerCase(); // Status text (Active/Inactive)


                // Determine status value (1 for Active, 0 for Inactive)
                let status = (statusText === 'active') ? '1' : '0';

                // Check if the row matches the search term and status filter
                const isEmployeeIdMatch = employeeId.includes(employeeIdSearchTerm);
                const isNameMatch = name.includes(nameSearchTerm);
                const isPhoneMatch = phone.includes(phoneSearchTerm);
                const isEmailMatch = emailSearchTerm.includes(emailSearchTerm);
                const isStatusMatch = statusValue === "" || status === statusValue;

                // Show or hide the row based on whether it matches the search term and status filter
                if (isEmployeeIdMatch && isNameMatch && isPhoneMatch && isEmailMatch && isStatusMatch ) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Function to reset the filter
        function resetFilter() {
            employeeIdSearchInput.value = "";
            nameSearchInput.value = "";
            phoneSearchInput.value = "";
            emailSearchInput.value = "";
            statusFilter.value = "";

            filterTable();
        }


        // Attach event listeners to the input and select elements
        employeeIdSearchInput.addEventListener('input', filterTable);
        nameSearchInput.addEventListener('input', filterTable);
        phoneSearchInput.addEventListener('input', filterTable);
        emailSearchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);


        // Get reference to the reset button and attach event listener
        const resetButton = document.getElementById('resetButton');
        resetButton.addEventListener('click', resetFilter);
    });
</script>
@endpush
