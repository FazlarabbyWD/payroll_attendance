<!-- Large Modal -->
<div class="modal fade" id="addLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Add New Leave</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
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
                            <label for="employee_id" class="form-label">Employee ID <span
                                    class="text-danger">**</span></label>
                            <input type="number" id="employee_id" name="employee_id" class="form-control"
                                placeholder="Enter Employee ID" required>
                        </div>

                        <!-- Employee Name Display -->
                        <div class="col-md-6 mb-3">
                            <label for="employee_name" class="form-label">Employee Name<span
                                    class="text-danger">**</span></label>
                            <input type="text" id="employee_name" class="form-control" readonly
                                placeholder="Employee Name">
                        </div>
                    </div>

                    <div class="row">
                        <!-- Start Date -->
                        <div class="col mb-3">
                            <label for="start_date" class="form-label">Start Date<span
                                    class="text-danger">**</span></label>
                            <input type="date" id="start_date" name="start_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- End Date -->
                        <div class="col mb-3">
                            <label for="end_date" class="form-label">End Date<span class="text-danger">**</span></label>
                            <input type="date" id="end_date" name="end_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Leave Type -->
                        <div class="col mb-3">
                            <label for="leave_type" class="form-label">Leave Type<span
                                    class="text-danger">**</span></label>
                            <select id="leave_type" name="leave_type" class="form-select" required>
                                <option value="">Select Leave Type</option>
                                <option value="Sick">Sick</option>
                                <option value="Casual">Casual</option>
                                <option value="Annual">Annual</option>
                                <option value="Unpaid">Unpaid</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Reason -->
                        <div class="col mb-3">
                            <label for="reason" class="form-label">Reason</label>
                            <textarea id="reason" name="reason" class="form-control" rows="3"></textarea>
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

        </div>
    </div>
</div>
<!--/ Large Modal -->