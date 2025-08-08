@extends('app')
@section('main-content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top">
                <div class="d-flex mb-3 justify-content-between align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-custom-icon">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0);">Employee</a>
                                <i class="breadcrumb-icon icon-base ri ri-arrow-right-circle-line align-middle"></i>
                            </li>
                            <li class="breadcrumb-item active">Add</li>
                        </ol>
                    </nav>
                    <div class="dt-buttons btn-group flex-wrap">
                        <a href="{{ route('employees.index') }}" class="btn add-new btn-primary" tabindex="0">
                            <span>
                                <i class="icon-base ri ri-list-line icon-sm me-0 me-sm-2 d-sm-none d-inline-block"></i>
                                <span class="d-none d-sm-inline-block">Employee List</span>
                            </span>
                        </a>
                    </div>

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
                                    <input class="form-control" type="text" id="firstName" name="first_name"
                                        autofocus="" required>
                                    <label for="firstName">First Name <span>*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 form-control-validation">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" name="last_name" id="lastName">
                                    <label for="lastName">Last Name<span>*</span></label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="phone_no" name="phone_no" class="form-control"
                                            placeholder="01700000000">
                                        <label for="phone_no">Phone Number <span>*</span></label>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" id="date_of_joining" name="date_of_joining" class="form-control">
                                    <label for="date_of_joining">Date of Joining <span>*</span></label>
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select id="department_id" name="department_id" class="select2 form-select">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="department_id">Department</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select id="designation_id" name="designation_id" class="select2 form-select">
                                        <option value="">Select Designation</option>
                                        @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="designation_id">Designation</label>
                                </div>
                            </div>

                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">Save
                                changes</button>
                            <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
            {{-- <div class="card">
                <h5 class="card-header">Delete Account</h5>
                <div class="card-body">
                    <form id="formAccountDeactivation" onsubmit="return false">
                        <div class="form-check mb-6 ms-3">
                            <input class="form-check-input" type="checkbox" name="accountActivation"
                                id="accountActivation">
                            <label class="form-check-label" for="accountActivation">I confirm my account
                                deactivation</label>
                        </div>
                        <button type="submit" class="btn btn-danger deactivate-account waves-effect waves-light"
                            disabled="disabled">
                            Deactivate Account
                        </button>
                    </form>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
