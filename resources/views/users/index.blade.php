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

    @include('users.partials.user_table', ['users' => $users])

</div>

@include('users.partials.add_user_modal')
@include('users.partials.edit_user_modal')

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


        const editModal = document.getElementById('editModal');
        if (editModal) {
           editModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;

    const userId = button.getAttribute('data-id');
    const username = button.getAttribute('data-username');
    const email = button.getAttribute('data-email');
    const phone = button.getAttribute('data-phone');
    const profilePhoto = button.getAttribute('data-profile_photo');

    const modalBodyInputUsername = editModal.querySelector('#editName');
    const modalBodyInputEmail = editModal.querySelector('#editEmail');
    const modalBodyInputPhone = editModal.querySelector('#editPhone');
    const editUploadedAvatar = editModal.querySelector('#editUploadedAvatar');
    const editUserForm = editModal.querySelector('#editUserForm');
    const editUserId = editModal.querySelector('#editUserId');

    modalBodyInputUsername.value = username;
    modalBodyInputEmail.value = email;
    modalBodyInputPhone.value = phone;
    editUserId.value = userId;

    editUserForm.action = updateUserRouteTemplate.replace('__ID__', userId);

    if (profilePhoto) {
        editUploadedAvatar.src = '{{ asset('public/storage/') }}/' + profilePhoto;
    } else {
        editUploadedAvatar.src = '{{ asset('/resources/assets/img/avatars/1.png') }}';
    }
});

        }


        // Edit modal Image reset and upload functionality
        const editUploadedAvatar = document.getElementById('editUploadedAvatar');
        const editFileInput = document.getElementById('editUpload');
        const editResetBtn = document.getElementById('editResetBtn');
        const defaultEditSrc = '{{ asset('/resources/assets/img/avatars/1.png') }}';

        editFileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    editUploadedAvatar.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        editResetBtn.addEventListener('click', function () {
            editUploadedAvatar.src = defaultEditSrc;
            editFileInput.value = '';
        });
</script>
@endpush
