<!-- Add Modal -->
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
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror" id="nameLarge"
                                    placeholder="Enter Name" value="{{ old('username') }}" required>
                                <label for="nameLarge">Username <span class="text-danger">**</span></label>
                                @error('username')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id="emailLarge"
                                    placeholder="example@gmail.com" value="{{ old('email') }}" required>
                                <label for="emailLarge">Email<span class="text-danger">**</span></label>
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
                                    class="form-control @error('phone') is-invalid @enderror" placeholder="01*********"
                                    value="{{ old('phone') }}" required>
                                <label for="phone">Phone No.<span class="text-danger">**</span></label>
                                @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                    required>
                                <label for="password">Password<span class="text-danger">**</span></label>
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
                                        class="account-file-input @error('profile_photo') is-invalid @enderror" hidden
                                        accept="image/png, image/jpeg, image/gif" />
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
<!-- Add Modal -->
