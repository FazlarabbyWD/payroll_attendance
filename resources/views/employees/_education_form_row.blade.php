<div class="card mb-4 education-item" data-index="{{ $index }}">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Education Information</h5>
        {{-- Only show remove button if there's more than one item initially, or if it's a new added item --}}
        <button type="button" class="btn btn-danger btn-sm remove-education-item {{ ($employee->education->count() <= 1 && !$is_new && !old('education')) ? 'd-none' : '' }}">Remove</button>
    </div>
    <div class="card-body">
        <div class="row g-3">
            {{-- Degree Name --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control @error('education.' . $index . '.degree_name') is-invalid @enderror" type="text"
                        name="education[{{ $index }}][degree_name]"
                        value="{{ old('education.' . $index . '.degree_name', $education->degree_name ?? '') }}"
                        placeholder="e.g., SSC, HSC, BSc" required>
                    <label>Degree Name <span class="text-danger">*</span></label>
                    @error('education.' . $index . '.degree_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Field of Study --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control @error('education.' . $index . '.field_of_study') is-invalid @enderror" type="text"
                        name="education[{{ $index }}][field_of_study]"
                        value="{{ old('education.' . $index . '.field_of_study', $education->field_of_study ?? '') }}"
                        placeholder="e.g., Science, Computer Science">
                    <label>Field of Study</label>
                    @error('education.' . $index . '.field_of_study')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Institute Name --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control @error('education.' . $index . '.institute_name') is-invalid @enderror" type="text"
                        name="education[{{ $index }}][institute_name]"
                        value="{{ old('education.' . $index . '.institute_name', $education->institute_name ?? '') }}"
                        placeholder="e.g., Dhaka University" required>
                    <label>Institute Name <span class="text-danger">*</span></label>
                    @error('education.' . $index . '.institute_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Board --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control @error('education.' . $index . '.board') is-invalid @enderror" type="text"
                        name="education[{{ $index }}][board]"
                        value="{{ old('education.' . $index . '.board', $education->board ?? '') }}"
                        placeholder="e.g., Dhaka Board">
                    <label>Board</label>
                    @error('education.' . $index . '.board')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Passing Year --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control @error('education.' . $index . '.passing_year') is-invalid @enderror" type="number" min="1900" max="{{ date('Y') }}"
                        name="education[{{ $index }}][passing_year]"
                        value="{{ old('education.' . $index . '.passing_year', $education->passing_year ?? '') }}"
                        placeholder="e.g., 2015" required>
                    <label>Passing Year <span class="text-danger">*</span></label>
                    @error('education.' . $index . '.passing_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- GPA/Division --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control @error('education.' . $index . '.gpa') is-invalid @enderror" type="text"
                        name="education[{{ $index }}][gpa]"
                        value="{{ old('education.' . $index . '.gpa', $education->gpa ?? '') }}"
                        placeholder="e.g., 4.00, First Class">
                    <label>GPA/Division</label>
                    @error('education.' . $index . '.gpa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Certificate File --}}
            <div class="col-md-6 form-control-validation">
                <div class="form-floating form-floating-outline">
                    <input class="form-control @error('education.' . $index . '.certificate_file') is-invalid @enderror" type="file"
                        name="education[{{ $index }}][certificate_file]">
                    <label>Certificate File</label>
                    @error('education.' . $index . '.certificate_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    {{-- CHANGE THIS LINE --}}
                    @if ($education && isset($education->certificate_file) && !empty($education->certificate_file))
                        <small class="text-muted">Current: <a href="{{ Storage::url($education->certificate_file) }}" target="_blank">View File</a></small>
                    @endif
                </div>
            </div>

            {{-- This hidden input is for handling updates to existing records later if you implement it properly --}}
            @if ($education && isset($education->id))
                <input type="hidden" name="education[{{ $index }}][id]" value="{{ $education->id }}">
            @endif

        </div>
    </div>
</div>


