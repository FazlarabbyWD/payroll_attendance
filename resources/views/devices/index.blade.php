@extends('app')
@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Devices Table</h5>
        </div>
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
            <div class="dt-buttons btn-group flex-wrap">
                <button class="btn create-new btn-primary" data-bs-toggle="modal" data-bs-target="#largeModal"
                    type="button">
                    <span class="d-flex align-items-center">
                        <i class="icon-base ri ri-add-line icon-18px me-sm-1"></i>
                        <span class="d-none d-sm-inline-block">Add New Device</span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <hr class="my-0">

    <div class="row m-3 align-items-center">
        <div class="col-md-2 mb-2">
            <input type="text" id="nameSearchInput" class="form-control" placeholder="Device Name">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" id="locationSearchInput" class="form-control" placeholder="Location">
        </div>
        <div class="col-md-3 mb-2">
            <input type="text" id="ipSearchInput" class="form-control" placeholder="IP Address">
        </div>
        <div class="col-md-2 mb-2">
            <select id="statusFilter" class="form-select">
                <option value="">All Statuses</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <button type="button" class="btn btn-secondary waves-effect waves-light" id="resetButton">RESET</button>
        </div>
    </div>


    @include('devices.partials.devices_table', ['devices' => $devices])

</div>

@include('devices.partials.add_devices_modal')



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


@endpush
