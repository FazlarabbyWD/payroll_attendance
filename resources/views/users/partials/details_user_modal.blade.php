<!-- Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ID:</label>
                        <p id="detailId"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username:</label>
                        <p id="detailUsername"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email:</label>
                        <p id="detailEmail"></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone:</label>
                        <p id="detailPhone"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Profile Photo:</label>
                        <img src="" alt="Profile Photo" id="detailProfilePhoto" width="100">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status:</label>
                        <p id="detailStatus"></p>
                    </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Created At:</label>
                      <p id="detailCreatedAt"></p>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Updated At:</label>
                      <p id="detailUpdatedAt"></p>
                  </div>
                </div>

                  <div class="row">
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Created By:</label>
                      <p id="detailCreatedBy"></p>
                  </div>
                  <div class="col-md-6 mb-3">
                      <label class="form-label">Updated By:</label>
                      <p id="detailUpdatedBy"></p>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
