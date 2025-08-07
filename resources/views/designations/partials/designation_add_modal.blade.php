<!-- Add Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Designation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('designations.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    id="nameLarge" placeholder="Enter Designation Name" value="{{ old('name') }}"
                                    required>
                                <label for="nameLarge">Designation Name<span class="text-danger">**</span></label>
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    id="descriptionLarge" placeholder="Door-1" value="{{ old('description') }}"
                                    >
                                <label for="descriptionLarge">Description</label>
                                @error('description')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label d-block">Departments <span class="text-danger">**</span></label>

                            <div class="form-control" style="max-height: 200px; overflow-y: auto;">
                                @foreach($departments as $department)
                                <div class="form-check">
                                    <input class="form-check-input @error('department_ids') is-invalid @enderror"
                                        type="checkbox" name="department_ids[]" id="department_{{ $department->id }}"
                                        value="{{ $department->id }}" {{ is_array(old('department_ids')) &&
                                        in_array($department->id, old('department_ids')) ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label" for="department_{{ $department->id }}">
                                        {{ $department->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('department_ids')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    


                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Modal -->
