@extends('app')
@section('main-content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">

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

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <!-- Left: Navigation Pills -->
                <ul class="nav nav-pills flex-row">
                    <li class="nav-item">
                        <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                            <i class="icon-base ri ri-group-line icon-sm me-1_5"></i>Add Employee
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                        <a class="nav-link waves-effect waves-light" href="javascript:void(0);"
                            onclick="alert('Please save employee first.'); return false;">
                            <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>Personal Info
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light">
                            <i class="menu-icon icon-base ri ri-bill-line"></i>Payroll
                        </a>
                    </li> --}}
                </ul>

                <!-- Right: Button -->
                <div class="dt-buttons btn-group">
                    <a href="{{ route('employees.index') }}" class="btn add-new btn-primary">
                        <i class="icon-base ri ri-list-line icon-sm me-0 me-sm-2 d-sm-none d-inline-block"></i>
                        <span class="d-none d-sm-inline-block">Employee List</span>
                    </a>
                </div>
            </div>


            <div class="card mb-6">
                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-6">
                        <img src="{{ asset('/resources/assets/img/avatars/1.png') }}" alt="user-avatar"
                            class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar">
                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-sm btn-primary me-3 mb-4 waves-effect waves-light"
                                tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="icon-base ri ri-upload-2-line d-block d-sm-none"></i>
                                <input type="file" id="upload" class="account-file-input" hidden=""
                                    accept="image/png, image/jpeg">
                            </label>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger account-image-reset mb-4 waves-effect">
                                <i class="icon-base ri ri-refresh-line d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form id="formAccountSettings" method="POST" action="{{ route('employees.store') }}">
                        @csrf



                        <div class="row mt-1 g-5">
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('first_name') is-invalid @enderror" type="text"
                                        id="firstName" name="first_name" autofocus="" required
                                        value="{{ old('first_name') }}">
                                    <label for="firstName">First Name <span>*</span></label>
                                    @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control @error('last_name') is-invalid @enderror" type="text"
                                        name="last_name" id="lastName" value="{{ old('last_name') }}">
                                    <label for="lastName">Last Name</label>
                                    @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" id="date_of_joining" name="date_of_joining"
                                        class="form-control @error('date_of_joining') is-invalid @enderror"
                                        value="{{ old('date_of_joining') }}">
                                    <label for="date_of_joining">Date of Joining <span>*</span></label>
                                    @error('date_of_joining')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select id="employment_type_id" name="employment_type_id"
                                        class="select2 form-select @error('employment_type_id') is-invalid @enderror">
                                        <option value="">Select Employment Type</option>
                                        @foreach($employeeTypes as $employeeType)
                                        <option value="{{ $employeeType->id }}" {{
                                            old('employment_type_id')==$employeeType->id ? 'selected' : ''
                                            }}>
                                            {{ $employeeType->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="employment_type_id">Employment Type <span>*</span></label>
                                    @error('employment_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select id="department_id" name="department_id"
                                        class="select2 form-select @error('department_id') is-invalid @enderror">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id')==$department->id ?
                                            'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="department_id">Department <span>*</span></label>
                                    @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select id="designation_id" name="designation_id"
                                        class="select2 form-select @error('designation_id') is-invalid @enderror">
                                        <option value="">Select Designation</option>
                                    </select>

                                    <label for="designation_id">Designation <span>*</span></label>
                                    @error('designation_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">Save
                                & Next</button>
                            <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var old = @json(session()->getOldInput());

$(document).ready(function () {
    $('#department_id').on('change', function () {
        let departmentId = $(this).val();

        if (departmentId) {
            $.ajax({
                url: "{{ route('get.designations.by.department') }}",
                type: "GET",
                data: { department_id: departmentId },
                success: function (data) {
                    let options = '<option value="">Select Designation</option>';
                    data.forEach(function (designation) {
                        options += `<option value="${designation.id}" ${old && old['designation_id'] == designation.id ? 'selected' : ''}>
                                        ${designation.name}
                                    </option>`;
                    });
                    $('#designation_id').html(options).trigger('change');
                },
                error: function () {
                    alert('Failed to fetch designations.');
                }
            });
        } else {
            $('#designation_id').html('<option value="">Select Designation</option>').trigger('change');
        }
    });
});
</script>
@endpush
