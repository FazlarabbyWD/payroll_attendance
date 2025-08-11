@extends('app')
@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            @include('components.alert', ['type' => 'success', 'message' => session('success')])
            @include('components.alert', ['type' => 'danger', 'message' => session('error')])

            {{-- Main Content Area --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body pt-12">
                            <div class="user-avatar-section">
                                <div class=" d-flex align-items-center flex-column">
                                    <img src="{{ asset('/resources/assets/img/avatars/3.png') }}" alt="Employee Photo"
                                        class="d-block rounded-circle img-fluid" height="100" width="100"
                                        id="uploadedAvatar" style="object-fit: cover;">
                                    <div class="user-info text-center">
                                        <h5>{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                                        <span class="badge bg-label-danger rounded-pill"> {{
                                            $employee->employmentType->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around flex-wrap my-6 gap-0 gap-md-3 gap-lg-4">
                                <div class="d-flex align-items-center me-5 gap-4">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-primary rounded">
                                            <i class="icon-base ri ri-check-line icon-24px"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $employee->designation->name }}</h5>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-4">
                                    <div class="avatar">
                                        <div class="avatar-initial bg-label-primary rounded">
                                            <i class="icon-base ri ri-briefcase-line icon-24px"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $employee->department->name }}</h5>
                                    </div>
                                </div>
                            </div>
                            <h5 class="pb-4 border-bottom mb-4">Details</h5>
                            <div class="info-container">
                                <ul class="list-unstyled mb-6">
                                    <li class="mb-2">
                                        <span class="h6">Joining Date:</span>
                                        <span>{{ $employee->date_of_joining->format('d-m-Y') }}</span>
                                    </li>

                                    @php
                                    $btnClass = match($employee->employmentStatus->code) {
                                    'active' => 'badge bg-label-success rounded-pill',
                                    'inactive' => 'badge bg-label-danger rounded-pill',
                                    'terminated' => 'badge bg-label-warning rounded-pill',
                                    'resigned' => 'badge bg-label-danger rounded-pill',
                                    default => 'badge bg-label-dark rounded-pill',
                                    };
                                    @endphp
                                    <li class="mb-2">
                                        <span class="h6">Status:</span>

                                        <span class="{{$btnClass}}"> {{
                                            $employee->employmentStatus->name }}</span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-center">
                                    <a href="javascript:;" class="btn btn-primary me-4 waves-effect waves-light"
                                        data-bs-target="#editUser" data-bs-toggle="modal">Update</a>
                                    <a href="javascript:;"
                                        class="btn btn-outline-danger suspend-user waves-effect">Suspend</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <!-- Left: Navigation Pills -->
                        <ul class="nav nav-pills flex-row">

                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light" href="javascript:void(0);">
                                    <i class="icon-base ri ri-link-m icon-sm me-1_5"></i>Personal Info
                                </a>
                            </li>


                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light"
                                    href="{{ route('employees.address',$employee) }}">
                                    <i class="icon-base ri ri-bookmark-line icon-sm me-1_5"></i>Address
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active waves-effect waves-light"
                                    href="javascript:void()">
                                    <i class="menu-icon icon-base ri ri-bill-line"></i>Education
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link waves-effect waves-light"
                                    href="pages-account-settings-notifications.html">
                                    <i class="icon-base ri ri-money-dollar-circle-line icon-24px"></i>Bank Info
                                </a>
                            </li>
                        </ul>
                        <div class="dt-buttons btn-group">
                            <a href="{{ route('employees.index') }}" class="btn add-new btn-primary">
                                <i class="icon-base ri ri-list-line icon-sm me-0 me-sm-2 d-sm-none d-inline-block"></i>
                                <span class="d-none d-sm-inline-block">Employee List</span>
                            </a>
                        </div>

                    </div>
                    <div class="card mb-6">
                        <div class="card-body pt-0">
                            {{-- EDUCATION FORM STARTS HERE --}}
                            <form id="educationForm" method="POST"
                                action="{{ route('employees.education.store', $employee->id) }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div id="education-container" class="mt-1 g-5">
                                    {{-- Loop through existing education records --}}
                                    @if ($employee->education->isNotEmpty())
                                    @foreach ($employee->education as $index => $edu)
                                    {{-- If existing data, is_new is false --}}
                                    @include('employees._education_form_row', ['education' => $edu, 'index' =>
                                    $index, 'is_new' => false])
                                    @endforeach
                                    {{-- OR if there was a validation error (old input exists) --}}
                                    @elseif (old('education'))
                                    @foreach (old('education') as $index => $edu)
                                    {{-- If old input, is_new is true --}}
                                    @include('employees._education_form_row', ['education' => (object)$edu,
                                    'index' => $index, 'is_new' => true])
                                    @endforeach
                                    {{-- Else (no existing data, no old input), render one empty row --}}
                                    @else
                                    @include('employees._education_form_row', ['education' => null, 'index' =>
                                    0, 'is_new' => true])
                                    @endif
                                </div>

                                <button type="button" id="add-education-item" class="btn btn-info mt-3">
                                    <span><i class="icon-base ri ri-add-line me-0 me-sm-1 icon-16px"></i><span class="d-none d-sm-inline-block">Add More</span></span>
                                    </button>
                                <button type="submit" class="btn btn-primary mt-3 ms-2">Save Education</button>
                            </form>
                            {{-- EDUCATION FORM ENDS HERE --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const educationContainer = document.getElementById('education-container');
        const addEducationButton = document.getElementById('add-education-item');

        const educationTemplate = `
            <div class="card mb-4 education-item">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Education Information</h5>
                    <button type="button" class="btn btn-danger btn-sm remove-education-item">Remove</button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text"
                                    name="education[INDEX_PLACEHOLDER][degree_name]"
                                    placeholder="e.g., SSC, HSC, BSc" required>
                                <label>Degree Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text"
                                    name="education[INDEX_PLACEHOLDER][field_of_study]"
                                    placeholder="e.g., Science, Computer Science">
                                <label>Field of Study</label>
                            </div>
                        </div>
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text"
                                    name="education[INDEX_PLACEHOLDER][institute_name]"
                                    placeholder="e.g., Dhaka University" required>
                                <label>Institute Name <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text"
                                    name="education[INDEX_PLACEHOLDER][board]"
                                    placeholder="e.g., Dhaka Board">
                                <label>Board</label>
                            </div>
                        </div>
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="number" min="1900" max="{{ date('Y') }}"
                                    name="education[INDEX_PLACEHOLDER][passing_year]"
                                    placeholder="e.g., 2015" required>
                                <label>Passing Year <span class="text-danger">*</span></label>
                            </div>
                        </div>
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="text"
                                    name="education[INDEX_PLACEHOLDER][gpa]"
                                    placeholder="e.g., 4.00, First Class">
                                <label>GPA/Division</label>
                            </div>
                        </div>
                        <div class="col-md-6 form-control-validation">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control" type="file"
                                    name="education[INDEX_PLACEHOLDER][certificate_file]">
                                <label>Certificate File</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        let educationIndex = educationContainer.querySelectorAll('.education-item').length;
        console.log('Initial educationIndex (based on existing DOM elements):', educationIndex);


        addEducationButton.addEventListener('click', function () {
            console.log('Add More button clicked. Current educationIndex before add:', educationIndex);
            const newEducationItem = document.createElement('div');
            const htmlToInsert = educationTemplate.replace(/INDEX_PLACEHOLDER/g, educationIndex);
            newEducationItem.innerHTML = htmlToInsert;
            const actualEducationItem = newEducationItem.firstElementChild;
            educationContainer.appendChild(actualEducationItem);
            educationIndex++;
            console.log('educationIndex after adding new item:', educationIndex);
            updateRemoveButtonVisibility();
        });

        // Event delegation for removing items
        educationContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-education-item')) {
                e.target.closest('.education-item').remove();
                updateRemoveButtonVisibility();
            }
        });

        function updateRemoveButtonVisibility() {
            const items = educationContainer.querySelectorAll('.education-item');
            if (items.length <= 1) {
                items.forEach(item => {
                    const removeBtn = item.querySelector('.remove-education-item');
                    if (removeBtn) {
                        removeBtn.classList.add('d-none');
                    }
                });
            } else {
                items.forEach(item => {
                    const removeBtn = item.querySelector('.remove-education-item');
                    if (removeBtn) {
                        removeBtn.classList.remove('d-none');
                    }
                });
            }
        }

        // Initial call to set correct visibility on load
        updateRemoveButtonVisibility();

    });
</script>
@endpush
