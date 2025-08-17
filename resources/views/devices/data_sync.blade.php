@extends('app')
@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Devices Data Sync Status</h5>
        </div>

    </div>
    <hr class="my-0">

    <div class="row m-3 align-items-center">
        <div class="col-md-3 mb-3">
            <input type="text" id="nameSearchInput" class="form-control" placeholder="Device Name">
        </div>
        <div class="col-md-3 mb-3">
            <input type="text" id="locationSearchInput" class="form-control" placeholder="Location">
        </div>
        <div class="col-md-3 mb-3">
            <input type="text" id="ipSearchInput" class="form-control" placeholder="IP Address">
        </div>

        <div class="col-md-3 mb-3">
            <button type="button" class="btn btn-secondary waves-effect waves-light" id="resetButton">RESET</button>
        </div>
    </div>

    <div class="card-datatable">
        <div id="DataTables_Table_3_wrapper" class="dt-container dt-bootstrap5">
            <div class="justify-content-between dt-layout-table">
                <div class="d-md-flex justify-content-between align-items-center dt-layout-full table-responsive">

                    <table class="dt-responsive table table-bordered dataTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th>Device IP</th>
                                <th>Location</th>
                                <th>Last Sync</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($devices as $key => $device)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $device->ip_address }}</td>
                                <td>{{ $device->location }}</td>
                                <td>
                                    {{
                                    optional($device->syncLogs->sortByDesc('last_sync')->first())->last_sync?->format('d-m-Y
                                    H:i:s') }}
                                </td>

                                <td>
                                    {{ optional($device->syncLogs->sortByDesc('last_sync')->first())->type }}
                                </td>
                                <td class="d-flex align-items-center gap-2">
                                    <form action="{{ route('devices.sync', [$device, 'type' => 'employee']) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">EMP SYNC</button>
                                    </form>

                                    {{-- <form action="{{ route('devices.sync', [$device, 'type' => 'attendance']) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">ATT SYNC</button>
                                    </form> --}}
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





@endsection