@extends('app')
@section('main-content')
@include('components.alert', ['type' => 'success', 'message' => session('success')])
@include('components.alert', ['type' => 'danger', 'message' => session('error')])
<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Attendnace Data Sync Status</h5>
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
                                <th>Device IP</th>
                                <th>Location</th>
                                <th>Attendance Sync</th>
                                <th>Data Process</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($lastAttSync as $key => $sync)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $sync->device->ip_address }}</td>
                                <td>{{$sync->device->location }}</td>
                                <td>
                                    {{
                                    $sync->last_sync->format('d-m-Y
                                    H:i:s') }}
                                </td>
                                <td>
                                    @if($lastAttPros)
                                    {{ $lastAttPros->last_processed_at->format('d-m-Y H:i:s') }}
                                    @else
                                    Not Processed
                                    @endif
                                </td>



                                <td class="d-flex align-items-center gap-2">
                                    <form action="{{ route('attendance.sync') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">ATT SYNC</button>
                                    </form>

                                    <form action="{{ route('attendance.process') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">DATA PROS</button>
                                    </form>
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
