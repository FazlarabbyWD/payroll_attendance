@extends('app')

@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="card-header">
        <h5 class="card-title">Edit Designation</h5>
    </div>
    <div class="card-body">

        <form id="editDesignationForm" action="{{ route('designations.update', $designation->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="designation_id" value="{{ $designation->id }}">


            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="editName" placeholder="Enter Designation Name"
                            value="{{ old('name', $designation->name) }}" required>
                        <label for="editName">Name<span class="text-danger">**</span></label>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="description"
                            class="form-control @error('description') is-invalid @enderror" id="editDescription"
                            placeholder="EUTO IT" value="{{ old('description', $designation->description) }}">
                        <label for="editDescription">Description</label>
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
                            <input class="form-check-input" type="checkbox" name="department_ids[]"
                                id="department_{{ $department->id }}" value="{{ $department->id }}" {{
                                $designation->departments->contains($department->id) ? 'checked' : '' }}>
                            <label class="form-check-label" for="department_{{ $department->id }}">
                                {{ $department->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>





            <button type="submit" class="btn btn-primary">Update changes</button>
            <a href="{{ route('designations.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
