@extends('app')
@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Devices Table</h5>
        </div>
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
            <div class="dt-buttons btn-group flex-wrap">
                <button class="btn create-new btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"
                    type="button">
                    <span class="d-flex align-items-center">
                        <i class="icon-base ri ri-add-line icon-18px me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New Device</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <hr class="my-0">

    <div class="row m-3 align-items-center">
        <div class="col-md-2 mb-2">
            <input type="text" id="nameSearchInput" class="form-control" placeholder="Device Name">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" id="locationSearchInput" class="form-control" placeholder="Location">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" id="ipSearchInput" class="form-control" placeholder="IP Address">
        </div>
        <div class="col-md-2 mb-2">
            <select id="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <button type="button" class="btn btn-secondary waves-effect waves-light" id="resetButton">RESET</button>
        </div>
    </div>


    @include('devices.partials.devices_table', ['devices' => $devices])

</div>

@include('devices.partials.add_devices_modal')



@endsection

@push('scripts')
@if ($errors->any())
<script>
    window.addEventListener('DOMContentLoaded', function () {
                var myModal = new bootstrap.Modal(document.getElementById('largeModal'));
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
    // Get references to the input and select elements
        const nameSearchInput = document.getElementById('nameSearchInput');
        const locationSearchInput = document.getElementById('locationSearchInput');
        const ipSearchInput = document.getElementById('ipSearchInput');
        const statusFilter = document.getElementById('statusFilter');

        // Get all the table rows
        const tableRows = Array.from(document.querySelectorAll('table tbody tr'));

        // Function to filter the table rows
        function filterTable() {
            const nameSearchTerm = nameSearchInput.value.toLowerCase();
            const locationSearchTerm = locationSearchInput.value.toLowerCase();
            const ipSearchTerm = ipSearchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            tableRows.forEach(row => {
                // Get the data from the row
                const device_name = row.cells[1].textContent.toLowerCase();
                const ip_address = row.cells[2].textContent.toLowerCase();
                const location = row.cells[4].textContent.toLowerCase();

                // Get status value 1 or 0
                const status = (row.cells[5].textContent.toLowerCase() === 'active') ? '1' : '0';

                // Check if the row matches the search term and status filter
                const isDeviceNameMatch = device_name.includes(nameSearchTerm);
                const isLocationMatch = location.includes(locationSearchTerm);
                const isIpMatch = ip_address.includes(ipSearchTerm);
                const isStatusMatch = statusValue === "" || status === statusValue;

                // Show or hide the row based on whether it matches the search term and status filter
                if (isDeviceNameMatch && isLocationMatch && isIpMatch && isStatusMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Function to reset the filter
        function resetFilter() {
            nameSearchInput.value = "";
            locationSearchInput.value = "";
            ipSearchInput.value = "";
            statusFilter.value = "";
            filterTable();
        }


        // Attach event listeners to the input and select elements
         nameSearchInput.addEventListener('input', filterTable);
        locationSearchInput.addEventListener('input', filterTable);
        ipSearchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);

           // Get reference to the reset button and attach event listener
        const resetButton = document.getElementById('resetButton');
        resetButton.addEventListener('click', resetFilter);

</script>

@endpush