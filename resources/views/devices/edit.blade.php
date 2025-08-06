@extends('app')

@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="card-header">
        <h5 class="card-title">Edit Device</h5>
    </div>
    <div class="card-body">

        <form id="editDeviceForm" action="{{ route('devices.update', $device->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="device_id" value="{{ $device->id }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="device_name"
                            class="form-control @error('device_name') is-invalid @enderror" id="editName"
                            placeholder="Enter Device Name" value="{{ old('device_name', $device->device_name) }}"
                            required>
                        <label for="editName">Device Name<span class="text-danger">**</span></label>
                        @error('device_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                            id="editLocation" placeholder="Door-1" value="{{ old('location', $device->location) }}"
                            required>
                        <label for="editLocation">Location<span class="text-danger">**</span></label>
                        @error('location')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="ip_address"
                            class="form-control @error('ip_address') is-invalid @enderror" id="editIpAddress"
                            placeholder="10.0.1.14" value="{{ old('ip_address', $device->ip_address) }}" required>
                        <label for="editIpAddress">IP Address<span class="text-danger">**</span></label>
                        @error('ip_address')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="number" name="port" class="form-control @error('port') is-invalid @enderror"
                            id="editPort" placeholder="8001" value="{{ old('port', $device->port) }}" required>
                        <label for="editPort">Port<span class="text-danger">**</span></label>
                        @error('port')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="editStatusActive" value="1" {{
                        old('status', $device->status) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="editStatusActive">
                        Active
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="editStatusInactive" value="0" {{
                        old('status', $device->status) == 0 ? 'checked' : '' }}>
                    <label class="form-check-label" for="editStatusInactive">
                        Inactive
                    </label>
                </div>
            </div>


            <button type="submit" class="btn btn-primary">Update changes</button>
            <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
