@extends('app')
@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="alert alert-secondary alert-dismissible" role="alert">
        This is a secondary dismissible alert — check it out!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
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
                                <h4 class="mb-1 me-2">21,459</h4>
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
                                <h4 class="mb-1 me-2">4,567</h4>

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
                                <h4 class="mb-1 me-2">19,860</h4>
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
                                <h4 class="mb-1 me-2">237</h4>
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
                    <select id="EMployeeDept" class="form-select text-capitalize">
                        <option value="">Select Department</option>
                        <option value="hr">HR</option>
                        <option value="it">IT</option>
                        <option value="finance">FINANCE</option>
                    </select>
                </div>

                <div class="col-md-4 employee_desgn">
                    <select id="EmployeeDesgn" class="form-select text-capitalize">
                        <option value="">Select Designation</option>
                        <option value="Software_engineer">Software Engineer</option>
                        <option value="cfo">CFO</option>
                        <option value="cto">CTO</option>
                    </select>
                </div>


                <div class="col-md-4 employee_blood_group">
                    <select id="EmployeeBlood" class="form-select text-capitalize">
                        <option value="">Select Blood Group</option>
                        <option value="a+" class="text-capitalize">A+</option>
                        <option value="b+" class="text-capitalize">B+</option>
                        <option value="o+" class="text-capitalize">O+</option>
                    </select>
                </div>
            </div>
        </div>




        <div class="card-datatable">
            <div id="DataTables_Table_0_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">

                <div class="row m-2 my-0 mt-2 mb-2 justify-content-between align-items-center">
                    <div class="col d-flex flex-wrap align-items-center gap-2">
                        <div>
                            <input type="search" class="form-control form-control-sm" id="dt-search-0"
                                placeholder="Employee ID">
                        </div>

                        <div>
                            <input type="search" class="form-control form-control-sm" id="dt-search-0"
                                placeholder="Name">
                        </div>

                        <div>
                            <input type="search" class="form-control form-control-sm" id="dt-search-1"
                                placeholder="Phone">
                        </div>

                        <div>
                            <input type="search" class="form-control form-control-sm" id="dt-search-2"
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
                                <col style="width: 80px;"> <!-- EMP ID -->
                                <col style="width: 150px;"> <!-- NAME -->
                                <col style="width: 120px;"> <!-- DEPARTMENT -->
                                <col style="width: 150px;"> <!-- DESIGNATION -->
                                <col style="width: 100px;"> <!-- PHONE -->
                                <col style="width: 80px;"> <!-- BLOOD GROUP -->
                                <col style="width: 80px;"> <!-- STATUS -->
                                <col style="width: 120px;"> <!-- ACTION -->
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

                                <tr>
                                    <td class="control dtr-hidden" tabindex="0" style="display: none;"></td>
                                    <td>EMP-123</td> <!-- Example Employee ID -->
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar avatar-sm me-4"><img
                                                        src="{{ asset('/resources/assets/img/avatars/2.png') }}"
                                                        alt="Avatar" class="rounded-circle"></div>
                                            </div>
                                            <div class="d-flex flex-column"><a href="app-user-view-account.html"
                                                    class="text-heading text-truncate"><span class="fw-medium">Zsazsa
                                                    </span></a></div>
                                        </div>
                                    </td>
                                    <td>IT</td> <!-- Example Department -->
                                    <td>Software Engineer</td> <!-- Example Designation -->
                                    <td>01682282043</td> <!-- Example Phone -->
                                    <td>O+</td> <!-- Example Blood Group -->
                                    <td><span class="badge rounded-pill bg-label-success"
                                            text-capitalized="">Active</span></td>
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

                                <!-- More rows would go here -->

                            </tbody>
                            <tfoot></tfoot>
                        </table>
                    </div>
                </div>


                <div class="row mx-3 mt-3 justify-content-between">
                    <div
                        class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-md-0 mt-5">
                        <div class="dt-info" aria-live="polite" id="DataTables_Table_0_info" role="status">Showing
                            1 to
                            10 of 50 entries</div>
                    </div>
                    <div
                        class="d-md-flex align-items-center dt-layout-end col-md-auto ms-auto d-flex gap-md-4 justify-content-md-between justify-content-center gap-md-2 flex-wrap mt-0">
                        <div class="dt-paging">
                            <nav aria-label="pagination">
                                <ul class="pagination">
                                    <li class="dt-paging-button page-item disabled"><button class="page-link first"
                                            role="link" type="button" aria-controls="DataTables_Table_0"
                                            aria-disabled="true" aria-label="First" data-dt-idx="first" tabindex="-1"><i
                                                class="icon-base ri ri-skip-back-mini-line scaleX-n1-rtl icon-22px"></i></button>
                                    </li>
                                    <li class="dt-paging-button page-item disabled"><button class="page-link previous"
                                            role="link" type="button" aria-controls="DataTables_Table_0"
                                            aria-disabled="true" aria-label="Previous" data-dt-idx="previous"
                                            tabindex="-1"><i
                                                class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i></button>
                                    </li>
                                    <li class="dt-paging-button page-item active"><button class="page-link" role="link"
                                            type="button" aria-controls="DataTables_Table_0" aria-current="page"
                                            data-dt-idx="0">1</button></li>
                                    <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                            type="button" aria-controls="DataTables_Table_0" data-dt-idx="1">2</button>
                                    </li>
                                    <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                            type="button" aria-controls="DataTables_Table_0" data-dt-idx="2">3</button>
                                    </li>
                                    <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                            type="button" aria-controls="DataTables_Table_0" data-dt-idx="3">4</button>
                                    </li>
                                    <li class="dt-paging-button page-item"><button class="page-link" role="link"
                                            type="button" aria-controls="DataTables_Table_0" data-dt-idx="4">5</button>
                                    </li>
                                    <li class="dt-paging-button page-item"><button class="page-link next" role="link"
                                            type="button" aria-controls="DataTables_Table_0" aria-label="Next"
                                            data-dt-idx="next"><i
                                                class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i></button>
                                    </li>
                                    <li class="dt-paging-button page-item"><button class="page-link last" role="link"
                                            type="button" aria-controls="DataTables_Table_0" aria-label="Last"
                                            data-dt-idx="last"><i
                                                class="icon-base ri ri-skip-forward-mini-line scaleX-n1-rtl icon-22px"></i></button>
                                    </li>
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
