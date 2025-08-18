<div class="card-datatable">
    <div id="DataTables_Table_3_wrapper" class="dt-container dt-bootstrap5">
        <div class="justify-content-between dt-layout-table">
            <div class="d-md-flex justify-content-between align-items-center dt-layout-full table-responsive">
                <table class="dt-responsive table table-bordered dataTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Employee</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Days</th>
                            <th>Leave Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaves as $key => $leave)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if ($leave->employee)
                                        {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                                    @else
                                        (Employee Not Found)
                                    @endif
                                </td>
                                <td>{{ $leave->start_date->format('d M Y') }}</td>
                                <td>{{ $leave->end_date->format('d M Y') }}</td>
                                @php
                                    $days = $leave->start_date->diffInDays($leave->end_date) + 1;
                                @endphp

                                <td>{{ $days }} {{ Str::plural('day', $days) }}</td>

                                <td>{{ $leave->leave_type }}</td>

                                <td class="d-flex align-items-center">
                                    <div class="d-inline-block">
                                        <a href="javascript:;"
                                            class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base ri ri-more-2-line icon-22px"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end m-0">

                                            <div class="dropdown-divider"></div>
                                            <li>
                                                <a href="{{ route('leaves.edit', $leave) }}" class="dropdown-item">
                                                    <i class="icon-base ri ri-edit-box-line"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('leaves.destroy', $leave) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="#"
                                        class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit ms-2">
                                        <i class="icon-base ri ri-eye-line icon-22px"></i>
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
