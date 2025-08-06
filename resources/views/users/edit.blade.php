@extends('app')

@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="card-header">
        <h5 class="card-title">Edit User</h5>
    </div>
    <div class="card-body">

        <form id="editUserForm" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="username"
                               class="form-control @error('username') is-invalid @enderror" id="editName"
                               placeholder="Enter Name" value="{{ old('username', $user->username) }}" required>
                        <label for="editName">Username</label>
                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror" id="editEmail"
                               placeholder="example@gmail.com" value="{{ old('email', $user->email) }}" required>
                        <label for="editEmail">Email</label>
                        @error('email')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="phone"
                               class="form-control @error('phone') is-invalid @enderror" id="editPhone"
                               placeholder="01*********" value="{{ old('phone', $user->phone) }}" required>
                        <label for="editPhone">Phone No.</label>
                        @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror" id="editPassword"
                               placeholder="Password">
                        <label for="editPassword">Password (leave blank to keep current)</label>
                        <small class="text-muted">Leave blank to keep current password.</small>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <img src="{{ $user->profile_photo ? asset('public/storage/' . $user->profile_photo) : asset('/resources/assets/img/avatars/1.png') }}" alt="user-avatar"
                         class="d-block w-px-100 h-px-100 rounded" id="editUploadedAvatar" />
                    <div class="button-wrapper">
                        <label for="editUpload" class="btn btn-sm btn-primary me-3 mb-4" tabindex="0">
                            <span class="d-none d-sm-block">Upload new photo</span>
                            <i class="icon-base ri ri-upload-2-line d-block d-sm-none"></i>
                            <input type="file" id="editUpload" name="profile_photo"
                                   class="account-file-input @error('profile_photo') is-invalid @enderror" hidden
                                   accept="image/png, image/jpeg, image/gif" />
                        </label>
                        @error('profile_photo')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                        @enderror

                        <button type="button" class="btn btn-sm btn-outline-danger account-image-reset mb-4"
                                id="editResetBtn">
                            <i class="icon-base ri ri-refresh-line d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Reset</span>
                        </button>
                        <div>Allowed JPG, GIF or PNG. Max size of 1MB</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update changes</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>  <!-- Back to index -->
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
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
