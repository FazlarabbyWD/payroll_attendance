<!-- Add Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Device</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('devices.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="device_name"
                                    class="form-control @error('device_name') is-invalid @enderror" id="nameLarge"
                                    placeholder="Enter Device Name" value="{{ old('device_name') }}" required>
                                <label for="nameLarge">Device Name<span class="text-danger">**</span></label>
                                @error('device_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="location"
                                    class="form-control @error('location') is-invalid @enderror" id="locationLarge"
                                    placeholder="Door-1" value="{{ old('location') }}" required>
                                <label for="locationLarge">Location<span class="text-danger">**</span></label>
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
                                    class="form-control @error('ip_address') is-invalid @enderror" placeholder="10.0.1.14"
                                    value="{{ old('ip_address') }}" required>
                                <label for="ip_address">Ip Address<span class="text-danger">**</span></label>
                                @error('ip_address')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="number" name="port"
                                    class="form-control @error('port') is-invalid @enderror" placeholder="8001"
                                    required>
                                <label for="port">Port<span class="text-danger">**</span></label>
                                @error('port')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
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
