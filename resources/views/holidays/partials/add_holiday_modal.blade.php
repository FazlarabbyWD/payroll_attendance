<div class="modal-body">
    <!-- Include alert components -->
    @include('components.alert', ['type' => 'success', 'message' => session('success')])
    @include('components.alert', ['type' => 'danger', 'message' => session('error')])

    <!-- Add Leave Form -->
    <form method="POST" action="{{ route('leaves.store') }}">
        @csrf

        <div class="row">
            <!-- Employee ID -->
            <div class="col-md-6 mb-3">
                <label for="employee_id" class="form-label">Employee ID <span class="text-danger">**</span></label>
                <input type="number" id="employee_id" name="employee_id"
                    class="form-control @error('employee_id') is-invalid @enderror" placeholder="Enter Employee ID"
                    value="{{ old('employee_id') }}" required>
                @error('employee_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Employee Name Display -->
            <div class="col-md-6 mb-3">
                <label for="employee_name" class="form-label">Employee Name <span class="text-danger">**</span></label>
                <input type="text" id="employee_name" class="form-control" value="{{ old('employee_name') }}" readonly
                    placeholder="Employee Name">
            </div>
        </div>

        <div class="row">
            <!-- Start Date -->
            <div class="col mb-3">
                <label for="start_date" class="form-label">Start Date <span class="text-danger">**</span></label>
                <input type="date" id="start_date" name="start_date"
                    class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}"
                    required>
                @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- End Date -->
            <div class="col mb-3">
                <label for="end_date" class="form-label">End Date <span class="text-danger">**</span></label>
                <input type="date" id="end_date" name="end_date"
                    class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" required>
                @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Leave Type -->
            <div class="col mb-3">
                <label for="leave_type" class="form-label">Leave Type <span class="text-danger">**</span></label>
                <select id="leave_type" name="leave_type" class="form-select @error('leave_type') is-invalid @enderror"
                    required>
                    <option value="">Select Leave Type</option>
                    <option value="Sick" {{ old('leave_type')=='Sick' ? 'selected' : '' }}>Sick</option>
                    <option value="Casual" {{ old('leave_type')=='Casual' ? 'selected' : '' }}>Casual</option>
                    <option value="Annual" {{ old('leave_type')=='Annual' ? 'selected' : '' }}>Annual</option>
                    <option value="Unpaid" {{ old('leave_type')=='Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Other" {{ old('leave_type')=='Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('leave_type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <!-- Reason -->
            <div class="col mb-3">
                <label for="reason" class="form-label">Reason</label>
                <textarea id="reason" name="reason" class="form-control @error('reason') is-invalid @enderror"
                    rows="3">{{ old('reason') }}</textarea>
                @error('reason')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Close
            </button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
</div>
