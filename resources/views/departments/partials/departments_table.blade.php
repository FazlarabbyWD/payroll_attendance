<div class="card-datatable">
    <div id="DataTables_Table_3_wrapper" class="dt-container dt-bootstrap5">
        <div class="justify-content-between dt-layout-table">
            <div class="d-md-flex justify-content-between align-items-center dt-layout-full table-responsive">

                <table class="dt-responsive table table-bordered dataTable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $key => $department)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->description }}</td>


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
                                            <form action="{{ route('departments.destroy', $department->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{ route('department.edit', $department->id) }}"
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
