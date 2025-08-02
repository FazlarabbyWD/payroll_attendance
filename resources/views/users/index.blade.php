@extends('app')
@section('main-content')

<div class="card mt-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Users Table</h5>
        </div>
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
            <div class="dt-buttons btn-group flex-wrap">
                <button class="btn create-new btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal" type="button">
                    <span class="d-flex align-items-center">
                        <i class="icon-base ri ri-add-line icon-18px me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New Record</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <hr class="my-0">

    <div class="card-datatable">
        <div id="DataTables_Table_3_wrapper" class="dt-container dt-bootstrap5">
            <div class="justify-content-between dt-layout-table">
                <div class="d-md-flex justify-content-between align-items-center dt-layout-full table-responsive">
                    <table class="dt-responsive table table-bordered dataTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Photo</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->profile_photo }}</td>
                                <td>
                                    @if($user->status == 'active')
                                        <span class="badge bg-label-success">Active</span>
                                    @else
                                        <span class="badge bg-label-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="d-flex align-items-center">
                                    <div class="d-inline-block">
                                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="icon-base ri ri-more-2-line icon-22px"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end m-0">
                                            <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                            <div class="dropdown-divider"></div>
                                            <li>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit ms-2">
                                        <i class="icon-base ri ri-edit-box-line icon-22px"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                    id="nameLarge" placeholder="Enter Name" value="{{ old('username') }}" required>
                                <label for="nameLarge">Username</label>
                                @error('username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    id="emailLarge" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                                <label for="emailLarge">Email</label>
                                @error('email')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    placeholder="01*********" value="{{ old('phone') }}" required>
                                <label for="phone">Phone No.</label>
                                @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Password" required>
                                <label for="password">Password</label>
                                @error('password')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-0">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ asset('/resources/assets/img/avatars/1.png') }}" alt="user-avatar"
                                class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-sm btn-primary me-3 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="icon-base ri ri-upload-2-line d-block d-sm-none"></i>
                                    <input type="file" id="upload" name="profile_photo"
                                        class="account-file-input @error('profile_photo') is-invalid @enderror"
                                        hidden accept="image/png, image/jpeg, image/gif" />
                                </label>
                                @error('profile_photo')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror

                                <button type="button" class="btn btn-sm btn-outline-danger account-image-reset mb-4">
                                    <i class="icon-base ri ri-refresh-line d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>
                                <div>Allowed JPG, GIF or PNG. Max size of 1MB</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
</script>
@endpush

