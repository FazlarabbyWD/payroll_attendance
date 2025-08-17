@extends('app')
@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="row card-header mx-0 px-2">
        <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto">
            <h5 class="card-title mb-0">Edit Holiday</h5>
        </div>
        <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto">
            <div class="dt-buttons btn-group flex-wrap">
                <a href="{{ route('holidays.index') }}" class="btn btn-primary">
                    <i class="ri ri-list-check icon-18px me-sm-1"></i>
                    <span class="d-none d-sm-inline-block">List Holidays</span>
                </a>
            </div>
        </div>
    </div>
    <hr class="my-0">

    <div class="card-body">
        <form action="{{ route('holidays.update', $holiday) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Title -->
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            id="titleLarge"
                            value="{{ old('title', $holiday->title) }}" required>
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
                            value="1" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}>
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
                            value="{{ old('start_date', $holiday->start_date->format('Y-m-d')) }}" required>
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
                            value="{{ old('end_date', $holiday->end_date->format('Y-m-d')) }}" required>
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
                            style="height: 100px">{{ old('description', $holiday->description) }}</textarea>
                        <label for="description">Description</label>
                        @error('description')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('holidays.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Holiday</button>
            </div>
        </form>
    </div>
</div>

@endsection
