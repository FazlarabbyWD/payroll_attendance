<div class="row g-6 mb-6">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">Total Employee</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $stats['employees'] }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded">
                            <div class="icon-base ri ri-group-line icon-26px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">Department</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $stats['departments'] }}</h4>

                        </div>

                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-danger rounded">
                            <div class="icon-base ri ri-user-add-line icon-26px scaleX-n1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">Verified Employee</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $stats['verifiedemployee'] }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-success rounded">
                            <div class="icon-base ri ri-user-follow-line icon-26px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="me-1">
                        <p class="text-heading mb-1">Pending Verification</p>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-1 me-2">{{ $stats['pendingVerification'] }}</h4>
                        </div>
                    </div>
                    <div class="avatar">
                        <div class="avatar-initial bg-label-warning rounded">
                            <div class="icon-base ri ri-user-search-line icon-26px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
