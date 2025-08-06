@extends('app')
@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Users Table</h5>
        </div>
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
            <div class="dt-buttons btn-group flex-wrap">
                <button class="btn create-new btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"
                    type="button">
                    <span class="d-flex align-items-center">
                        <i class="icon-base ri ri-add-line icon-18px me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New Record</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <hr class="my-0">

     <div class="row m-3 align-items-center">
        <div class="col-md-2 mb-2">
            <input type="text" id="nameSearchInput" class="form-control" placeholder="Username">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" id="emailSearchInput" class="form-control" placeholder="Email">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" id="phoneSearchInput" class="form-control" placeholder="Phone">
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


    @include('users.partials.user_table', ['users' => $users])

</div>

@include('users.partials.add_user_modal')
@include('users.partials.details_user_modal')

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
    const uploadedAvatar = document.getElementById('uploadedAvatar');
        const fileInput = document.getElementById('upload');
        const resetBtn = document.querySelector('.account-image-reset');
        const defaultSrc = '{{ asset('/resources/assets/img/avatars/1.png') }}';

        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    uploadedAvatar.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        resetBtn.addEventListener('click', function () {
            uploadedAvatar.src = defaultSrc;
            fileInput.value = '';
        });

          // User Details Modal
        const userDetailsModal = document.getElementById('userDetailsModal');
        if (userDetailsModal) {
            userDetailsModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const username = button.getAttribute('data-username');
                const email = button.getAttribute('data-email');
                const phone = button.getAttribute('data-phone');
                const profilePhoto = button.getAttribute('data-profile_photo');
                const status = button.getAttribute('data-status');
                const email_verified_at = button.getAttribute('data-email_verified_at');
                const force_password_change = button.getAttribute('data-force_password_change');
                const created_at = button.getAttribute('data-created_at');
                const updated_at = button.getAttribute('data-updated_at');
                const created_by = button.getAttribute('data-created_by');
                const updated_by = button.getAttribute('data-updated_by');

                userDetailsModal.querySelector('#detailId').textContent = id;
                userDetailsModal.querySelector('#detailUsername').textContent = username;
                userDetailsModal.querySelector('#detailEmail').textContent = email;
                userDetailsModal.querySelector('#detailPhone').textContent = phone;
                userDetailsModal.querySelector('#detailProfilePhoto').src = profilePhoto;
                userDetailsModal.querySelector('#detailStatus').textContent = status;
                userDetailsModal.querySelector('#detailEmailVerifiedAt').textContent = email_verified_at;
                userDetailsModal.querySelector('#detailForcePasswordChange').textContent = force_password_change;
                userDetailsModal.querySelector('#detailCreatedAt').textContent = created_at;
                userDetailsModal.querySelector('#detailUpdatedAt').textContent = updated_at;
                userDetailsModal.querySelector('#detailCreatedBy').textContent = created_by;
                userDetailsModal.querySelector('#detailUpdatedBy').textContent = updated_by;

            });
        }


        
         // Get references to the input and select elements
        const nameSearchInput = document.getElementById('nameSearchInput');
        const emailSearchInput = document.getElementById('emailSearchInput');
        const phoneSearchInput = document.getElementById('phoneSearchInput');
        const statusFilter = document.getElementById('statusFilter');

        // Get all the table rows
        const tableRows = Array.from(document.querySelectorAll('table tbody tr'));

        // Function to filter the table rows
        function filterTable() {
            const nameSearchTerm = nameSearchInput.value.toLowerCase();
            const emailSearchTerm = emailSearchInput.value.toLowerCase();
            const phoneSearchTerm = phoneSearchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            tableRows.forEach(row => {
                // Get the data from the row
                const username = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const phone = row.cells[3].textContent.toLowerCase();

                // Get status value 1 or 0
                const status = (row.cells[5].textContent === 'active') ? '0' : '1';

                // Check if the row matches the search term and status filter
                const isNameMatch = username.includes(nameSearchTerm);
                const isEmailMatch = email.includes(emailSearchTerm);
                const isPhoneMatch = phone.includes(phoneSearchTerm);
                const isStatusMatch = statusValue === "" || status === statusValue;

                // Show or hide the row based on whether it matches the search term and status filter
                if (isNameMatch && isEmailMatch && isPhoneMatch && isStatusMatch) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Function to reset the filter
        function resetFilter() {
            nameSearchInput.value = "";
            emailSearchInput.value = "";
            phoneSearchInput.value = "";
            statusFilter.value = "";
            filterTable();
        }


        // Attach event listeners to the input and select elements
        nameSearchInput.addEventListener('input', filterTable);
        emailSearchInput.addEventListener('input', filterTable);
        phoneSearchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);

           // Get reference to the reset button and attach event listener
        const resetButton = document.getElementById('resetButton');
        resetButton.addEventListener('click', resetFilter);


</script>
@endpush
