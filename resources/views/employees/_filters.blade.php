
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

