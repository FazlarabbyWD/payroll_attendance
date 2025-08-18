@extends('app')

@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Employee Leave Table</h5>
        </div>
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
            <div class="dt-buttons btn-group flex-wrap">
                <a href="{{ route('leaves.create') }}" class="btn create-new btn-primary">
                    <span class="d-flex align-items-center">
                        <i class="icon-base ri ri-add-line icon-18px me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New Employee Leave</span>
                    </span>
                </a>
            </div>

        </div>
    </div>
    <hr class="my-0">

    <div class="row m-3 align-items-center">
        <!-- Month Select -->
        <div class="col-md-2 mb-2">
            <select id="monthSelect" class="form-control">
                <option value="">Select Month</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
        </div>

        <!-- Year Select -->
        <div class="col-md-2 mb-2">
            <select id="yearSelect" class="form-control">
                <option value="">Select Year</option>
                <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                <option value="{{ date('Y', strtotime('+1 year')) }}">{{ date('Y', strtotime('+1 year')) }}</option>
            </select>
        </div>

        <!-- Reset Button -->
        <div class="col-md-2 mb-2">
            <button type="button" class="btn btn-secondary waves-effect waves-light" id="resetButton">RESET</button>
        </div>
    </div>


    @include('leaves.partials.leave_table', ['leaves' => $leaves])

</div>




@endsection

@push('scripts')
@if ($errors->any())
<script>
    window.addEventListener('DOMContentLoaded', function() {
                var myModal = new bootstrap.Modal(document.getElementById('addLeaveModal'));
                myModal.show();
            });
</script>
@endif

@if (session('success'))
<script>
    Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
</script>
@endif

<script>
    // Get references to the dropdowns
        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');
        const resetButton = document.getElementById('resetButton');

        // Get all the table rows
        const tableRows = Array.from(document.querySelectorAll('table tbody tr'));

        // Function to filter the table rows
        function filterTable() {
            const selectedMonth = monthSelect.value;
            const selectedYear = yearSelect.value;

            tableRows.forEach(row => {
                // Grab Start Date column (3rd col → index 2)
                const startDateText = row.cells[2].textContent.trim(); // "05 Jan 2025"
                const endDateText = row.cells[3].textContent.trim(); // "06 Jan 2025"

                // Parse into JS Date objects
                const startDate = new Date(startDateText);
                const endDate = new Date(endDateText);

                // Default show
                let showRow = true;

                if (selectedMonth) {
                    // month in JS Date is 0-based → add 1
                    const startMonth = startDate.getMonth() + 1;
                    const endMonth = endDate.getMonth() + 1;
                    if (startMonth !== parseInt(selectedMonth) && endMonth !== parseInt(selectedMonth)) {
                        showRow = false;
                    }
                }

                if (selectedYear) {
                    const startYear = startDate.getFullYear();
                    const endYear = endDate.getFullYear();
                    if (startYear !== parseInt(selectedYear) && endYear !== parseInt(selectedYear)) {
                        showRow = false;
                    }
                }

                row.style.display = showRow ? "" : "none";
            });
        }

        // Reset filters
        function resetFilter() {
            monthSelect.value = "";
            yearSelect.value = "";
            filterTable();
        }

        // Event listeners
        monthSelect.addEventListener('change', filterTable);
        yearSelect.addEventListener('change', filterTable);
        resetButton.addEventListener('click', resetFilter);



</script>

@endpush
