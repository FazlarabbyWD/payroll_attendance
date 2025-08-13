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
                                <a class="nav-link  waves-effect waves-light" href="javascript:void(0);">
                                    <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>Personal Info
                                </a>
                            </li>


                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light"
                                    href="{{ route('employees.address',$employee) }}">
                                    <i class="icon-base ri ri-bookmark-line icon-sm me-1_5"></i>Address
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light"
                                    href="{{ route('employees.education',$employee) }}">
                                    <i class="menu-icon icon-base ri ri-bill-line"></i>Education
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link active waves-effect waves-light" href="javascript::void()">
                                    <i class="icon-base ri ri-money-dollar-circle-line icon-24px"></i>Bank Info
                                </a>
                            </li>
                        </ul>
                        <div class="dt-buttons btn-group">
                            <a href="{{ route('employees.index') }}" class="btn add-new btn-primary">
                                <i class="icon-base ri ri-list-line icon-sm me-0 me-sm-2 d-sm-none d-inline-block"></i>
                                <span class="d-none d-sm-inline-block">Employee List</span>
                            </a>
                        </div>

                    </div>
                    <div class="card mb-6">
                        <div class="card-body pt-0">
                            <form id="formBankInfo" method="POST"
                                action="{{ route('employees.bankinfo.store',$employee) }}">
                                @csrf

                                <div class="row mt-1 g-5">

                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <select id="bank_id" name="bank_id"
                                                class="select2 form-select @error('bank_id') is-invalid @enderror">
                                                <option value="">Select Bank</option>
                                                @foreach($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ old('bank_id', optional($employee->
                                                    bankDetails?->branch?->bank)->id) == $bank->id ? 'selected' : '' }}>
                                                    {{ $bank->name }}
                                                </option>

                                                @endforeach
                                            </select>
                                            <label for="bank_id">Bank <span>*</span></label>
                                            @error('bank_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <select id="bank_branch_id" name="bank_branch_id"
                                                class="select2 form-select @error('bank_branch_id') is-invalid @enderror">
                                                <option value="">Select Branch</option>
                                                @foreach($banks as $bank)
                                                @foreach($bank->branches as $branch)
                                                <option value="{{ $branch->id }}" {{ old('bank_branch_id',
                                                    optional($employee->bankDetails)->bank_branch_id) == $branch->id ?
                                                    'selected' : '' }}>
                                                    {{ $branch->branch_name }}
                                                </option>

                                                @endforeach
                                                @endforeach
                                            </select>

                                            <label for="branch_id">Branch <span>*</span></label>
                                            @error('branch_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="account_holder_name" name="account_holder_name"
                                                class="form-control @error('account_holder_name') is-invalid @enderror"
                                                placeholder="Enter account holder name"
                                                value="{{ old('account_holder_name', optional($employee->bankDetails)->account_holder_name) }}">
                                            <label for="account_holder_name">A/C Name <span>*</span></label>
                                            @error('account_holder_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 form-control-validation">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" id="account_number" name="account_number"
                                                class="form-control @error('account_number') is-invalid @enderror"
                                                placeholder="Enter account number"
                                                value="{{ old('account_number', optional($employee->bankDetails)->account_number) }}">
                                            <label for="account_number">A/C Number <span>*</span></label>
                                            @error('account_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>



                                    @if(!is_null($employee->bankDetails?->bank_branch_id))
                                    <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">
                                        Update Info
                                    </button>
                                    @else
                                    <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">
                                        Save Info
                                    </button>
                                    @endif


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
