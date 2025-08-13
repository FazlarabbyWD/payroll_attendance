<tr>
    <td class="control dtr-hidden" tabindex="0" style="display: none;"></td>
    <td>{{ $employee->employee_id }}</td>
    <td>
        <div class="d-flex justify-content-start align-items-center user-name">
            <div class="avatar-wrapper">
                <div class="avatar avatar-sm me-4">
                    <img src="{{ asset('/resources/assets/img/avatars/2.png') }}"
                        alt="Avatar" class="rounded-circle">
                </div>
            </div>
            <div class="d-flex flex-column">
                <a href="app-user-view-account.html" class="text-heading text-truncate">
                    <span class="fw-medium">{{ $employee->first_name }} {{
                        $employee->last_name }}</span>
                </a>
            </div>
        </div>
    </td>
    <td>{{ $employee->department->name ?? 'N/A' }}</td>

    <td>{{ $employee->designation->name ?? 'N/A' }}</td>
    {{-- <td>{{ $employee->employmentType->name ?? 'N/A' }}</td> --}}

    <td>{{ $employee->phone_no ?? 'N/A' }}</td>
    <td>{{ $employee->bloodGroup->name ?? 'N/A' }}</td>

    <td>
        <span class="badge rounded-pill bg-label-info">
            {{ $employee->employmentStatus->name }}
        </span>
    </td>

    <td>
        <div class="d-flex align-items-center">
            <a href="javascript:;"
                class="btn btn-icon btn-text-secondary rounded-pill delete-record">
                <i class="icon-base ri ri-delete-bin-7-line icon-md"></i>
            </a>
            <a href="app-user-view-account.html"
                class="btn btn-icon btn-text-secondary rounded-pill">
                <i class="icon-base ri ri-eye-line icon-md"></i>
            </a>
            <a href="javascript:;"
                class="btn btn-icon btn-text-secondary rounded-pill dropdown-toggle hide-arrow"
                data-bs-toggle="dropdown">
                <i class="icon-base ri ri-more-2-line icon-md"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end m-0">
              <a href="{{ route('employees.personal-info',$employee) }}" class="dropdown-item">Update Info</a>

                <a href="{{ route('employees.edit', $employee) }}" class="dropdown-item">Edit</a>
            </div>
        </div>
    </td>
</tr>
