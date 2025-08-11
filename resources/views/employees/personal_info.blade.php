{{-- @dd($employee); --}}
@extends('app')
@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            @include('components.alert', ['type' => 'success', 'message' => session('success')])
            @include('components.alert', ['type' => 'danger', 'message' => session('error')])

            {{-- Main Content Area --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body pt-12">
                            <div class="user-avatar-section">
                                <div class=" d-flex align-items-center flex-column">
                                    <img src="{{ asset('/resources/assets/img/avatars/3.png') }}" alt="Employee Photo"
                                        class="d-block rounded-circle img-fluid" height="100" width="100"
                                        id="uploadedAvatar" style="object-fit: cover;">
                                    <div class="user-info text-center">
                                        <h5>{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                                        <span class="badge bg-label-danger rounded-pill"> {{
                                            $employee->employmentType->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4">
                                <div class="d-flex align-items-center me-5 gap-4">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-primary rounded">
                                            <i class="icon-base ri ri-check-line icon-24px"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $employee->designation->name }}</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-primary rounded">
                                            <i class="icon-base ri ri-briefcase-line icon-24px"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $employee->department->name }}</h5>
                                    </div>
                                </div>
                            </div>
                            <h5 class="pb-4 border-bottom mb-4">Details</h5>
                            <div class="info-container">
                                <ul class="list-unstyled mb-6">
                                    <li class="mb-2">
                                        <span class="h6">Joining Date:</span>
                                        <span>{{ $employee->date_of_joining->format('d-m-Y') }}</span>
                                    </li>

                                    @php
                                    $btnClass = match($employee->employmentStatus->code) {
                                    'active' => 'badge bg-label-success rounded-pill',
                                    'inactive' => 'badge bg-label-danger rounded-pill',
                                    'terminated' => 'badge bg-label-warning rounded-pill',
                                    'resigned' => 'badge bg-label-danger rounded-pill',
                                    default => 'badge bg-label-dark rounded-pill',
                                    };
                                    @endphp
                                    <li class="mb-2">
                                        <span class="h6">Status:</span>

                                        <span class="{{$btnClass}}"> {{
                                            $employee->employmentStatus->name }}</span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-center">
                                    <a href="javascript:;" class="btn btn-primary me-4 waves-effect waves-light"
                                        data-bs-target="#editUser" data-bs-toggle="modal">Update</a>
                                    <a href="javascript:;"
                                        class="btn btn-outline-danger suspend-user waves-effect">Suspend</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-8">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <!-- Left: Navigation Pills -->
                        <ul class="nav nav-pills flex-row">

                            <li class="nav-item">
                                <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                                    <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>Personal Info
                                </a>
                            </li>


                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light"
                                    href="pages-account-settings-notifications.html">
                                    <i class="icon-base ri ri-bookmark-line icon-sm me-1_5"></i>Address
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light"
                                    href="pages-account-settings-notifications.html">
                                    <i class="menu-icon icon-base ri ri-bill-line"></i>Education
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light"
                                    href="pages-account-settings-notifications.html">
                                    <i class="icon-base ri ri-money-dollar-circle-line icon-24px"></i>Payroll
                                </a>
                            </li>
                        </ul>

                    </div>
                    <div class="card mb-6">
                        <div class="card-body pt-0">
                            <form id="formPersonalInfo" method="POST"
                                action="{{ route('employees.personal-info.store',$employee) }}">
                                @csrf
                                <div class="row mt-1 g-5">
                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control @error('phone_no') is-invalid @enderror"
                                                type="text" id="phone_no" name="phone_no"
                                                value="{{ old('phone_no') ?? $employee->phone_no ?? '' }}">
                                            <label for="phone_no">Phone Number <span>*</span></label>
                                            @error('phone_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control @error('email') is-invalid @enderror"
                                                type="email" name="email" id="email"
                                                value="{{ old('email') ?? $employee->email ?? '' }}">
                                            <label for="email">Email </label>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control @error('date_of_birth') is-invalid @enderror"
                                                type="date" name="date_of_birth" id="date_of_birth"
                                                value="{{ old('date_of_birth')  ?? ($employee->date_of_birth ?? '')->format('Y-m-d') }}">
                                            <label for="date_of_birth">Date of Birth <span>*</span></label>
                                            @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <select id="gender_id" name="gender_id"
                                                class="select2 form-select @error('gender_id') is-invalid @enderror">
                                                <option value="">Select Gender</option>
                                                @foreach($genders as $gender)
                                                <option value="{{ $gender->id }}" {{ old('gender_id', $employee->
                                                    gender_id ?? '')==$gender->id ? 'selected'
                                                    : '' }}>{{ $gender->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="gender_id">Gender <span>*</span></label>
                                            @error('gender_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <select id="religion_id" name="religion_id"
                                                class="select2 form-select @error('religion_id') is-invalid @enderror">
                                                <option value="">Select Religion</option>
                                                @foreach($religions as $religion)
                                                <option value="{{ $religion->id }}" {{ old('religion_id', $employee->
                                                    religion_id ?? '')==$religion->id ?
                                                    'selected' : '' }}>{{ $religion->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="religion_id">Religion <span>*</span></label>
                                            @error('religion_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <select id="marital_status_id" name="marital_status_id"
                                                class="select2 form-select @error('marital_status_id') is-invalid @enderror">
                                                <option value="">Select Marital Status</option>
                                                @foreach($maritalStatuses as $maritalStatus)
                                                <option value="{{ $maritalStatus->id }}" {{ old('marital_status_id',
                                                    $employee->marital_status_id ??
                                                    '')==$maritalStatus->id ? 'selected' : '' }}>{{
                                                    $maritalStatus->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="marital_status_id">Marital Status <span>*</span></label>
                                            @error('marital_status_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <select id="blood_group_id" name="blood_group_id"
                                                class="select2 form-select @error('blood_group_id') is-invalid @enderror">
                                                <option value="">Select Blood Group</option>
                                                @foreach($bloodGroups as $bloodGroup)
                                                <option value="{{ $bloodGroup->id }}" {{ old('blood_group_id',
                                                    $employee->blood_group_id ?? '')==$bloodGroup->id
                                                    ? 'selected' : '' }}>{{ $bloodGroup->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="blood_group_id">Blood Group <span>*</span></label>
                                            @error('blood_group_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <input class="form-control @error('national_id') is-invalid @enderror"
                                                type="text" id="national_id" name="national_id"
                                                value="{{ old('national_id') ?? $employee->national_id ?? '' }}">
                                            <label for="national_id">National ID <span>*</span></label>
                                            @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        @if(!is_null($employee->phone_no))
                                        <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">
                                            Update
                                        </button>
                                        @else
                                        <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">
                                            Save 
                                        </button>
                                        @endif
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
