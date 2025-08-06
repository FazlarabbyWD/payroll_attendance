@extends('app')
@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Designation Table</h5>
        </div>
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
            <div class="dt-buttons btn-group flex-wrap">
                <button class="btn create-new btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"
                    type="button">
                    <span class="d-flex align-items-center">
                        <i class="icon-base ri ri-add-line icon-18px me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New Designation</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <hr class="my-0">

    <div class="row m-3 align-items-center">
        <div class="col-md-2 mb-2">
            <input type="text" id="nameSearchInput" class="form-control" placeholder="Designation Name">
        </div>


        <div class="col-md-2 mb-2">
            <button type="button" class="btn btn-secondary waves-effect waves-light" id="resetButton">RESET</button>
        </div>
    </div>


    @include('designations.partials.designations_table', ['designations' => $designations])

</div>

@include('designations.partials.designation_add_modal')



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

    // Get all the table rows
    const tableRows = Array.from(document.querySelectorAll('table tbody tr'));

    // Function to filter the table rows
    function filterTable() {
        const nameSearchTerm = nameSearchInput.value.toLowerCase();

        tableRows.forEach(row => {
            // Get the data from the row
            const name = row.cells[1].textContent.toLowerCase();

            // Check if the row matches the search term
            const isDepartmentNameMatch = name.includes(nameSearchTerm);  // Corrected this line

            // Show or hide the row based on whether it matches the search term
            if (isDepartmentNameMatch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Function to reset the filter
    function resetFilter() {
        nameSearchInput.value = "";
        filterTable();
    }

    // Attach event listeners to the input and select elements
    nameSearchInput.addEventListener('input', filterTable);

    // Get reference to the reset button and attach event listener
    const resetButton = document.getElementById('resetButton');
    resetButton.addEventListener('click', resetFilter);
</script>

@endpush
