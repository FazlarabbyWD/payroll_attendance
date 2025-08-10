<!-- Add Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('departments.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id="nameLarge"
                                    placeholder="Enter Department Name" value="{{ old('name') }}" required>
                                <label for="nameLarge">Department Name<span class="text-danger">**</span></label>
                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="description"
                                    class="form-control @error('description') is-invalid @enderror" id="descriptionLarge"
                                    placeholder="Description" value="{{ old('description') }}" required>
                                <label for="descriptionLarge">Description<span class="text-danger">**</span></label>
                                @error('description')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
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
