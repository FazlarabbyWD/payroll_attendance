@extends('app')

@section('main-content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">

            @include('components.alert', ['type' => 'success', 'message' => session('success')])
            @include('components.alert', ['type' => 'danger', 'message' => session('error')])

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <!-- Left: Navigation Pills -->
                <ul class="nav nav-pills flex-row">
                    <li class="nav-item">
                        <a class="nav-link active waves-effect waves-light" href="javascript:void(0);">
                            <i class="menu-icon icon-base ri ri-bill-line"></i>Daily Attendance Report
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card mb-6">
                <!-- Account -->
                <div class="card-body pt-0">
                    <form id="formAccountSettings" method="get" action="{{ route('reports.daily-attendance.show') }}">
                        <div class="row mt-1 g-5">
                            <!-- Date -->
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="date" id="report_date" name="report_date"
                                        class="form-control @error('report_date') is-invalid @enderror"
                                        value="{{ old('report_date') }}">
                                    <label for="report_date">Date of Joining <span>*</span></label>
                                    @error('report_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Company -->
                            <div class="col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <select name="company_id" id="company_id"
                                        class="form-select @error('company_id') is-invalid @enderror">
                                        <option value="">-- Select Concern --</option>
                                        <option value="0" {{ old('company_id')=='0' ? 'selected' : '' }}>All Concern</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id')==$company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="company_id">Concern <span>*</span></label>
                                    @error('company_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">
                                Request Report
                            </button>
                            <button type="reset" class="btn btn-outline-secondary waves-effect">
                                Reset
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
</div>
@endsection
