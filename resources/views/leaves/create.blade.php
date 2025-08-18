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

                        </li>
                    </ul>

                    <!-- Right: Button -->
                    <div class="dt-buttons btn-group">
                        <a href="{{ route('leaves.index') }}" class="btn add-new btn-primary">
                            <i class="icon-base ri ri-calendar-event-line icon-sm me-1_5"></i>
                            <span class="d-none d-sm-inline-block">Employee Leave List</span>
                        </a>
                    </div>
                </div>

                <div class="card mb-6">
                    <div class="card-body pt-0">
                        <form method="POST" action="{{ route('leaves.store') }}">
                            @csrf

                            <div class="row mt-3 g-5">
                                <!-- Employee ID -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" id="employee_id" name="employee_id"
                                            class="form-control @error('employee_id') is-invalid @enderror"
                                            placeholder="Enter Employee ID" value="{{ old('employee_id') }}" required>
                                        <label for="employee_id">Employee ID <span>*</span></label>
                                        @error('employee_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Employee Name Display -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="employee_name" class="form-control" readonly
                                            placeholder="Employee Name">
                                        <label for="employee_name">Employee Name</label>
                                    </div>
                                </div>


                                <!-- Start Date -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="start_date" name="start_date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            value="{{ old('start_date') }}" required>
                                        <label for="start_date">Start Date <span>*</span></label>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- End Date -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" id="end_date" name="end_date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            value="{{ old('end_date') }}" required>
                                        <label for="end_date">End Date <span>*</span></label>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Leave Type -->
                                <div class="col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="leave_type" name="leave_type"
                                            class="select2 form-select @error('leave_type') is-invalid @enderror" required>
                                            <option value="">Select Leave Type</option>
                                            @foreach (['Sick', 'Casual', 'Annual', 'Unpaid', 'Other'] as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('leave_type') == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="leave_type">Leave Type <span>*</span></label>
                                        @error('leave_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea id="reason" name="reason"
                                            class="form-control @error('reason') is-invalid @enderror"
                                            style="height: 100px;">{{ old('reason') }}</textarea>
                                        <label for="reason">Reason</label>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary me-3 waves-effect waves-light">Save</button>
                                <button type="reset" class="btn btn-outline-secondary waves-effect">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

{{-- @push('scripts')
    <script>
        const employees = @json($employees);

        document.getElementById('employee_id').addEventListener('input', function() {
            const employeeId = this.value;
            const employeeNameInput = document.getElementById('employee_name');

            if (employeeId) {
                const employee = employees.find(emp => emp.employee_id == employeeId);

                if (employee) {
                    employeeNameInput.value = employee.first_name + ' ' + employee.last_name;
                } else {
                    employeeNameInput.value = '';
                }
            } else {
                employeeNameInput.value = '';
            }
        });
    </script>
@endpush --}}

@push('scripts')
    <script>
        const employees = @json($employees);

        document.getElementById('employee_id').addEventListener('input', function() {
            const employeeId = this.value;
            const employeeNameInput = document.getElementById('employee_name');

            if (employeeId) {
                const employee = employees.find(emp => emp.employee_id == employeeId);

                if (employee) {
                    employeeNameInput.value = employee.last_name
                        ? employee.first_name + ' ' + employee.last_name
                        : employee.first_name;
                } else {
                    employeeNameInput.value = '';
                }
            } else {
                employeeNameInput.value = '';
            }
        });
    </script>
@endpush

