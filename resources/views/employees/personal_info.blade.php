@extends('app')
@section('main-content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <!-- Left: Navigation Pills -->
                <ul class="nav nav-pills flex-row">
                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light" href="{{ route('employees.create') }}">
                            <i class="icon-base ri ri-group-line icon-sm me-1_5"></i>Employee
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                            <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>Personal Info
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link waves-effect waves-light"
                            href="pages-account-settings-notifications.html">
                            <i class="menu-icon icon-base ri ri-bill-line"></i>Payroll
                        </a>
                    </li>
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

                <div class="card-body pt-0">
                    <form id="formPersonalInfo" method="POST"
                        action="#">
                        @csrf
                        <div class="row mt-1 g-5">
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="phone_no" name="phone_no">
                                    <label for="phone_no">Phone Number <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="email" name="email" id="email">
                                    <label for="email">Email </label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="date" name="date_of_birth" id="date_of_birth">
                                    <label for="date_of_birth">Date of Birth <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <select id="gender_id" name="gender_id" class="select2 form-select">
                                        <option value="">Select Gender</option>
                                        @foreach($genders as $gender)
                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="gender_id">Gender <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <select id="religion_id" name="religion_id" class="select2 form-select">
                                        <option value="">Select Religion</option>
                                        @foreach($religions as $religion)
                                        <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="religion_id">Religion <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <select id="marital_status_id" name="marital_status_id"
                                        class="select2 form-select">
                                        <option value="">Select Marital Status</option>
                                        @foreach($maritalStatuses as $maritalStatus)
                                        <option value="{{ $maritalStatus->id }}">{{ $maritalStatus->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="marital_status_id">Marital Status <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <select id="blood_group_id" name="blood_group_id" class="select2 form-select">
                                        <option value="">Select Blood Group</option>
                                        @foreach($bloodGroups as $bloodGroup)
                                        <option value="{{ $bloodGroup->id }}">{{ $bloodGroup->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="blood_group_id">Blood Group <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="national_id" name="national_id">
                                    <label for="national_id">National ID <span>*</span></label>
                                </div>
                            </div>


                            {{-- Address Section Start --}}
                            <div class="col-12">
                                <h5>Address Information</h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select class="form-select" id="address_type" name="address_type">
                                        <option value="current">Current Address</option>
                                        <option value="permanent">Permanent Address</option>
                                    </select>
                                    <label for="address_type">Address Type <span>*</span></label>
                                </div>
                            </div>

                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="country" id="country">
                                    <label for="country">Country <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="state" id="state">
                                    <label for="state">State <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="city" id="city">
                                    <label for="city">City <span>*</span></label>
                                </div>
                            </div>

                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="postal_code" id="postal_code">
                                    <label for="postal_code">Postal Code <span>*</span></label>
                                </div>
                            </div>

                            <div class="col-md-12 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control" name="address" id="address" rows="3"></textarea>
                                    <label for="address">Details Address <span>*</span></label>
                                </div>
                            </div>
                        </div>
                        {{-- Address Section End --}}

                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">Save
                                & Next</button>
                            <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
