<!-- Add Modal -->
<div class="modal fade" id="largeModal" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('holidays.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror" id="titleLarge"
                                    placeholder="Enter Holiday Title" value="{{ old('title') }}" required>
                                <label for="titleLarge">Title<span class="text-danger">**</span></label>
                                @error('title')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- Is Recurring -->
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <!-- Hidden input ensures a value is always submitted -->
                                <input type="hidden" name="is_recurring" value="0">

                                <input class="form-check-input" type="checkbox" id="isRecurring" name="is_recurring"
                                    value="1" {{ old('is_recurring') ? 'checked' : '' }}>
                                <label class="form-check-label" for="isRecurring">Recurring</label>

                                @error('is_recurring')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <!-- Start Date -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date') }}" required>
                                <label for="start_date">Start Date<span class="text-danger">**</span></label>
                                @error('start_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="date" name="end_date"
                                    class="form-control @error('end_date') is-invalid @enderror"
                                    value="{{ old('end_date') }}" required>
                                <label for="end_date">End Date<span class="text-danger">**</span></label>
                                @error('end_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-floating form-floating-outline">
                                <textarea name="description"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Enter Description"
                                    style="height: 100px">{{ old('description') }}</textarea>
                                <label for="description">Description</label>
                                @error('description')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Holiday</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Modal -->