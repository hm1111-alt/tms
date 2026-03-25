<!-- Profile Picture Upload Modal -->
<div class="modal fade" id="pictureUploadModal" tabindex="-1" aria-labelledby="pictureUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pictureUploadModalLabel">
                    <i class="fas fa-user-circle me-2"></i>Update Profile Picture
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= site_url('profilepicture') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file_upload" class="form-label">
                            <i class="fas fa-upload me-1"></i>Choose Picture
                        </label>
                        <input class="form-control" type="file" id="file_upload" name="file_upload" accept="image/jpeg,image/png" required>
                        <small class="text-muted">Accepted formats: JPG, PNG. Max size: 3MB</small>
                    </div>
                    <input type="hidden" name="page_to" value="<?= $class_name ?? 'myprofile' ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to open picture upload modal
    function openPictureUploadModal() {
        var myModal = new bootstrap.Modal(document.getElementById('pictureUploadModal'));
        myModal.show();
    }
    
    // Auto-open if triggered from flashdata
    <?php if(session()->getFlashdata('error')): ?>
        <?php if(strpos(session()->getFlashdata('error'), 'File') !== false): ?>
            document.addEventListener('DOMContentLoaded', function() {
                openPictureUploadModal();
            });
        <?php endif; ?>
    <?php endif; ?>
</script>
