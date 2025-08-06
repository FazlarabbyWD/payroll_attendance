@extends('app')

@section('main-content')

<div class="card mt-4 ml-4 mr-4">
    <div class="card-header">
        <h5 class="card-title">Edit Department</h5>
    </div>
    <div class="card-body">

        <form id="editDepartmentForm" action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="department_id" value="{{ $department->id }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-floating form-floating-outline">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="editName" placeholder="Enter Department Name"
                            value="{{ old('name', $department->name) }}" required>
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
                            placeholder="EUTO IT" value="{{ old('description', $department->description) }}" required>
                        <label for="editDescription">Description<span class="text-danger">**</span></label>
                        @error('description')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>



            <button type="submit" class="btn btn-primary">Update changes</button>
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection
