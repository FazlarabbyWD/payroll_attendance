<div class="card-datatable">
    <div id="DataTables_Table_3_wrapper" class="dt-container dt-bootstrap5">
        <div class="justify-content-between dt-layout-table">
            <div class="d-md-flex justify-content-between align-items-center dt-layout-full table-responsive">
                <table class="dt-responsive table table-bordered dataTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Days</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $key => $holiday)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $holiday->title }}</td>
                            <td>{{ $holiday->start_date->format('d M Y') }}</td>
                            <td>{{ $holiday->end_date->format('d M Y') }}</td>
                            @php
                            $days = $holiday->start_date->diffInDays($holiday->end_date) + 1;
                            @endphp

                            <td>{{ $days }} {{ Str::plural('day', $days) }}</td>

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
                                            <form action="{{ route('holidays.destroy', $holiday) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{ route('holidays.edit', $holiday) }}"
                                    class="btn btn-sm btn-text-secondary rounded-pill btn-icon item-edit ms-2">
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
