<div class="card-datatable">
    <div id="DataTables_Table_0_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">

        <div class="row m-2 my-0 mt-2 mb-2 justify-content-between align-items-center">
            <div class="col d-flex flex-wrap align-items-center gap-2">
                <div>
                    <input type="search" class="form-control form-control-sm" id="employeeIdSearchInput"
                        placeholder="Employee ID">
                </div>

                <div>
                    <input type="text" class="form-control form-control-sm" id="nameSearchInput" placeholder="Name">
                </div>

                <div>
                    <input type="search" class="form-control form-control-sm" id="phoneSearchInput" placeholder="Phone">
                </div>

                <div>
                    <input type="search" class="form-control form-control-sm" id="emailSearchInput" placeholder="Email">
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
                <table class="datatables-users table dataTable dtr-column table-responsive" id="DataTables_Table_0"
                    aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                    <colgroup>
                        <col style="width: 10px;">
                        <!-- EMP ID -->
                        <col style="width: 100px;">
                        <!-- NAME -->
                        <col style="width: 80px;">
                        <!-- DEPARTMENT -->
                        <col style="width: 120px;">
                        <!-- DESIGNATION -->
                        {{-- <col style="width: 800px;"> --}}
                        <!-- Employment -->
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
                            <th data-dt-column="0" class="control dt-orderable-none dtr-hidden" rowspan="1" colspan="1"
                                aria-label="" style="display: none;"><span class="dt-column-title"></span><span
                                    class="dt-column-order"></span></th>

                            <th data-dt-column="1" class="dt-orderable-asc" tabindex="0">
                                <span class="dt-column-title" role="button">ID</span>
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
                            {{-- <th data-dt-column="5" class="dt-orderable-asc" tabindex="0">
                                <span class="dt-column-title" role="button">Employment</span>
                            </th> --}}
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
                        @include('employees._table_row', ['employee' => $employee])
                        @endforeach
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
        @include('employees._pagination', ['employees' => $employees])
    </div>
</div>
