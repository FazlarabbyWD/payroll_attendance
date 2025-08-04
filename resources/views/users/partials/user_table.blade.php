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
                        <th>Phone No.</th>
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
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if ($user->profile_photo)
                                    <img src="{{ asset('public/storage/' . $user->profile_photo) }}" alt="Profile Photo"
                                         width="40" height="40">
                                @else
                                    <img src="{{ asset('/resources/assets/img/avatars/1.png') }}"
                                         alt="Default Profile Photo" width="50" height="50">
                                @endif
                            </td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge bg-label-success">Active</span>
                                @else
                                    <span class="badge bg-label-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="d-flex align-items-center">
                                <div class="d-inline-block">
                                    <a href="javascript:;"
                                       class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                       data-bs-toggle="dropdown">
                                        <i class="icon-base ri ri-more-2-line icon-22px"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end m-0">
                                        <li><a href="javascript:;" class="dropdown-item">Details</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                <a href="javascript:void(0);" {{-- Change href to javascript:void(0); --}}
                                   class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit ms-2"
                                   data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $user->id }}"
                                   data-username="{{ $user->username }}" data-email="{{ $user->email }}"
                                   data-phone="{{ $user->phone }}"
                                   data-profile_photo="{{ $user->profile_photo }}">
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
