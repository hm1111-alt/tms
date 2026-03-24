<h4 class="h2-class mt-2 mb-4"><?= $page[0]->page_name2; ?></h4>

<div class="px-4">
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-success" onclick="showAddTrainingModal();">
            <i class="fas fa-plus-circle"></i> Add New Training
        </button>
    </div>
    
    <ul class="nav nav-tabs mb-3" id="trainingTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">Pending Trainings</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">Approved Trainings</button>
        </li>
    </ul>
    
    <div class="tab-content" id="trainingTabContent">
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div class="datatable-dropdown">
                                <label>
                                    <select id="limit_pending" class="datatable-selector">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="all">All</option>
                                    </select> entries per page
                                </label>
                            </div>
                        </div>

                        <div class="datatable-search ms-auto">
                            <input name="search_pending" id="search_pending" class="datatable-input search" 
                                value=""
                                placeholder="Search pending trainings..." type="search">
                        </div>

                        <input type="hidden" id="order_by_pending" value="addeddate">
                        <input type="hidden" id="sort_by_pending" value="desc">
                    </div>
                    
                    <div id="pending_trainings_content">
                        <p class="text-muted text-center">Loading pending trainings...</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="approved" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div class="datatable-dropdown">
                                <label>
                                    <select id="limit_approved" class="datatable-selector">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="all">All</option>
                                    </select> entries per page
                                </label>
                            </div>
                        </div>

                        <div class="datatable-search ms-auto">
                            <input name="search_approved" id="search_approved" class="datatable-input search" 
                                value=""
                                placeholder="Search approved trainings..." type="search">
                        </div>

                        <input type="hidden" id="order_by_approved" value="addeddate">
                        <input type="hidden" id="sort_by_approved" value="desc">
                    </div>
                    
                    <div id="approved_trainings_content">
                        <p class="text-muted text-center">Loading approved trainings...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<div class="modal fade" id="addTrainingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addTrainingForm" method="post">
                    <input type="hidden" id="add_training_emp_idno" value="<?= session()->get('emp_idno') ?>">
                    
                    <div class="mb-4">
                        <label for="add_training_name" class="form-label fw-semibold">Training Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="add_training_name" name="training_name" required placeholder="Enter training/seminar/workshop name">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="add_training_datefrom" class="form-label fw-semibold">Date From <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="add_training_datefrom" name="training_datefrom" required>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="add_training_dateto" class="form-label fw-semibold">Date To <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="add_training_dateto" name="training_dateto" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="add_training_hours" class="form-label fw-semibold">Number of Hours <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="add_training_hours" name="training_hours" required placeholder="e.g., 8, 40" min="1">
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="add_training_category" class="form-label fw-semibold">Training Category</label>
                            <select class="form-select" id="add_training_category" name="training_category_id">
                                <option value="">Select Category</option>
                                <?php
                                try {
                                    $db = \Config\Database::connect('training');
                                    $categories = $db->table('lib_training_category')
                                        ->select('id_training_category, training_category_name')
                                        ->where('is_deleted', 0)
                                        ->where('is_visible', 1)
                                        ->orderBy('training_category_name', 'ASC')
                                        ->get()
                                        ->getResultArray();
                                    foreach ($categories as $cat) {
                                        echo '<option value="' . $cat['id_training_category'] . '">' . esc($cat['training_category_name']) . '</option>';
                                    }
                                } catch (\Exception $e) {
                                    echo '<!-- Training categories failed to load -->';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="add_training_venue" class="form-label fw-semibold">Venue <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_training_venue" name="training_venue" required placeholder="Location where training was held">
                    </div>
                    
                    <div class="mb-4">
                        <label for="add_training_facilitator" class="form-label fw-semibold">Facilitator/Conducted By <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="add_training_facilitator" name="training_facilitator" required placeholder="Organization or person who conducted the training">
                    </div>
                    
                    <div class="mb-4">
                        <label for="add_training_certificate" class="form-label fw-semibold">Certificate File</label>
                        <input type="file" class="form-control" id="add_training_certificate" name="training_certificate" accept=".pdf,.png,.jpg,.jpeg" onchange="validateCertificateFile(this)">
                        <small class="form-text text-muted mt-1 d-block">Upload certificate (PDF, PNG, JPG, JPEG). Max size: 5MB.</small>
                        <div id="certificate_error" class="text-danger small mt-1"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitAddTraining();"><i class="fas fa-save"></i> Save Training</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="trainingDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Training Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="trainingDetailContent">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTrainingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editTrainingForm" method="post">
                    <input type="hidden" id="edit_training_id" value="">
                    <input type="hidden" id="edit_training_emp_idno" value="<?= session()->get('emp_idno') ?>">
                    
                    <div class="mb-4">
                        <label for="edit_training_name" class="form-label fw-semibold">Training Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="edit_training_name" name="training_name" required placeholder="Enter training/seminar/workshop name">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="edit_training_datefrom" class="form-label fw-semibold">Date From <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_training_datefrom" name="training_datefrom" required>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="edit_training_dateto" class="form-label fw-semibold">Date To <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_training_dateto" name="training_dateto" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="edit_training_hours" class="form-label fw-semibold">Number of Hours <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_training_hours" name="training_hours" required placeholder="e.g., 8, 40" min="1">
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="edit_training_category" class="form-label fw-semibold">Training Category</label>
                            <select class="form-select" id="edit_training_category" name="training_category_id">
                                <option value="">Select Category</option>
                                <?php
                                try {
                                    $db = \Config\Database::connect('training');
                                    $categories = $db->table('lib_training_category')
                                        ->select('id_training_category, training_category_name')
                                        ->where('is_deleted', 0)
                                        ->where('is_visible', 1)
                                        ->orderBy('training_category_name', 'ASC')
                                        ->get()
                                        ->getResultArray();
                                    foreach ($categories as $cat) {
                                        echo '<option value="' . $cat['id_training_category'] . '">' . esc($cat['training_category_name']) . '</option>';
                                    }
                                } catch (\Exception $e) {
                                    echo '<!-- Training categories failed to load -->';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_training_venue" class="form-label fw-semibold">Venue <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_training_venue" name="training_venue" required placeholder="Location where training was held">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_training_facilitator" class="form-label fw-semibold">Facilitator/Conducted By <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_training_facilitator" name="training_facilitator" required placeholder="Organization or person who conducted the training">
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_training_certificate" class="form-label fw-semibold">Certificate File</label>
                        <input type="file" class="form-control" id="edit_training_certificate" name="training_certificate" accept=".pdf,.png,.jpg,.jpeg" onchange="validateCertificateFile(this)">
                        <small class="form-text text-muted mt-1 d-block">Upload certificate (PDF, PNG, JPG, JPEG). Max size: 5MB.</small>
                        <div id="edit_certificate_error" class="text-danger small mt-1"></div>
                        <div id="current_certificate_container" class="mt-2" style="display:none;">
                            <small class="text-muted">Current certificate: <strong id="current_certificate_name"></strong></small><br>
                            <a href="#" id="current_certificate_link" target="_blank" class="btn btn-sm btn-success mt-1"><i class="fas fa-download"></i> View Current Certificate</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitEditTraining();"><i class="fas fa-save"></i> Update Training</button>
            </div>
        </div>
    </div>
</div>

<script>
function validateCertificateFile(input) {
    const file = input.files[0];
    const errorDiv = document.getElementById('certificate_error');
    const editErrorDiv = document.getElementById('edit_certificate_error');
    const activeErrorDiv = input.id === 'edit_training_certificate' ? editErrorDiv : errorDiv;
    
    if (file) {
        const fileSize = file.size / 1024 / 1024; 
        const fileName = file.name.toLowerCase();
        const validExtensions = ['.pdf', '.png', '.jpg', '.jpeg'];
        const hasValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
        
        if (!hasValidExtension) {
            activeErrorDiv.textContent = 'Invalid file type. Only PDF, PNG, JPG, and JPEG files are allowed.';
            input.value = ''; 
            return false;
        }
        
        if (fileSize > 5) {
            activeErrorDiv.textContent = 'File size exceeds 5MB. Please upload a smaller file.';
            input.value = ''; 
            return false;
        }
        
        activeErrorDiv.textContent = '';
        return true;
    }
    return true;
}

function editPendingTraining(trainingId) {
    $.ajax({
        url: '<?= site_url("trainings/get_pending_details/") ?>' + trainingId,
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const training = response.data;
                
                $('#edit_training_id').val(training.id_pending_training || training.id);
                $('#edit_training_name').val(training.training_name || '');
                $('#edit_training_datefrom').val(training.training_datefrom || '');
                $('#edit_training_dateto').val(training.training_dateto || '');
                $('#edit_training_hours').val(training.training_hours || '');
                $('#edit_training_category').val(training.training_category_id || '');
                $('#edit_training_venue').val(training.training_venue || '');
                $('#edit_training_facilitator').val(training.training_facilitator || '');
                $('#edit_training_sponsor').val(training.training_sponsor || '');
                
                if (training.training_certificate_file && training.training_certificate_file !== '') {
                    $('#current_certificate_name').text(training.training_certificate_file);
                    $('#current_certificate_link').attr('href', '<?= base_url("uploads/trainings/certificates/") ?>' + training.training_certificate_file);
                    $('#current_certificate_container').show();
                } else {
                    $('#current_certificate_container').hide();
                }
                
                $('#editTrainingModal').modal('show');
            } else {
                alert('Error loading training details: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Error loading training details: ' + error);
        }
    });
}

function submitEditTraining() {
    const form = document.getElementById('editTrainingForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const certificateInput = document.getElementById('edit_training_certificate');
    if (certificateInput.files.length > 0 && !validateCertificateFile(certificateInput)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('training_id', $('#edit_training_id').val());
    formData.append('emp_idno', $('#edit_training_emp_idno').val());
    formData.append('training_name', $('#edit_training_name').val());
    formData.append('training_datefrom', $('#edit_training_datefrom').val());
    formData.append('training_dateto', $('#edit_training_dateto').val());
    formData.append('training_hours', $('#edit_training_hours').val());
    formData.append('training_category_id', $('#edit_training_category').val());
    formData.append('training_venue', $('#edit_training_venue').val());
    formData.append('training_facilitator', $('#edit_training_facilitator').val());
    
    if (certificateInput.files.length > 0) {
        formData.append('training_certificate', certificateInput.files[0]);
    }
    
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    saveBtn.disabled = true;
    
    $.ajax({
        url: '<?= site_url("trainings/update_pending") ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#editTrainingModal').modal('hide');
                
                alert('Training updated successfully!');
                loadPendingTrainings();
                form.reset();
            } else {
                alert('Error updating training: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Error updating training: ' + (xhr.responseJSON?.message || error));
        },
        complete: function() {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        }
    });
}

function showAddTrainingModal() {
    $('#addTrainingModal').modal('show');
}

function submitAddTraining() {
    const form = document.getElementById('addTrainingForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const certificateInput = document.getElementById('add_training_certificate');
    if (certificateInput.files.length > 0 && !validateCertificateFile(certificateInput)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('emp_idno', $('#add_training_emp_idno').val());
    formData.append('training_name', $('#add_training_name').val());
    formData.append('training_datefrom', $('#add_training_datefrom').val());
    formData.append('training_dateto', $('#add_training_dateto').val());
    formData.append('training_hours', $('#add_training_hours').val());
    formData.append('training_category_id', $('#add_training_category').val());
    formData.append('training_venue', $('#add_training_venue').val());
    formData.append('training_facilitator', $('#add_training_facilitator').val());
    
    if (certificateInput.files.length > 0) {
        formData.append('training_certificate', certificateInput.files[0]);
    }
    
    const saveBtn = event.target;
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    saveBtn.disabled = true;
    
    $.ajax({
        url: '<?= site_url("trainings/save_pending") ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            $('#addTrainingModal').modal('hide');
        
            Swal.fire({
                icon: 'success',
                title: 'Training Added Successfully!',
                text: 'Your training request has been submitted for approval.',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: '#d4edda',
                color: '#155724',
                customClass: {
                    popup: 'swal2-toast-success'
                }
            });

            if (typeof window.loadPendingTrainingsGlobal === 'function') {
                window.loadPendingTrainingsGlobal();
            } else {
                console.error('loadPendingTrainingsGlobal not available');
            }
            
            form.reset();
        },
        error: function(xhr, status, error) {
            alert('Error adding training: ' + (xhr.responseJSON?.message || error));
        },
        complete: function() {
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        }
    });
}

$(document).ready(function(){
    console.log('Training tabs initialized');
    
    window.loadPendingTrainingsGlobal = loadPendingTrainings;
    
    $(document).on('change', '#limit_pending', function(){
        console.log('Pending limit changed to:', $(this).val());
        loadPendingTrainings();
    });
    
    $(document).on('keyup', '#search_pending', function(){
        console.log('Pending search updated:', $(this).val());
        loadPendingTrainings();
    });
    
    $(document).on('change', '#limit_approved', function(){
        console.log('Approved limit changed to:', $(this).val());
        loadApprovedTrainings();
    });
    
    $(document).on('keyup', '#search_approved', function(){
        console.log('Approved search updated:', $(this).val());
        loadApprovedTrainings();
    });
    
    loadPendingTrainings();
    
    function loadPendingTrainings() {
        var limit = $('#limit_pending').val() || 10;
        var search = $('#search_pending').val() || '';
        var page = 1;
        
        console.log('Loading pending trainings - Limit:', limit, 'Search:', search);
        
        $.ajax({
            url: '<?= site_url("trainings/load_pending_trainings") ?>',
            method: 'POST',
            data: {
                limit: limit,
                search: search,
                page: page
            },
            success: function(response) {
                console.log('=== PENDING RESPONSE ===');
                console.log('First 200 chars:', response ? response.substring(0, 200) : 'EMPTY RESPONSE');
                console.log('Response type:', typeof response);
                
                if (!response || response.trim().startsWith('<!DOCTYPE') || response.trim().startsWith('<html') || response.trim().startsWith('<?xml')) {
                    console.error('ERROR: Server returned HTML page instead of table content');
                    console.error('Response preview:', response ? response.substring(0, 500) : 'EMPTY');
                    $('#pending_trainings_content').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error loading data. Please refresh the page or contact administrator.</div>');
                    return;
                }
                
                if (response.includes('Fatal error') || response.includes('Warning:') || response.includes('Notice:')) {
                    console.error('ERROR: Response contains PHP errors');
                    console.error('Response preview:', response.substring(0, 500));
                    $('#pending_trainings_content').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Server error occurred. Check error logs.</div>');
                    return;
                }
                
                try {
                    $('#pending_trainings_content').html(response);
                    console.log('Successfully loaded pending trainings');
                } catch (e) {
                    console.error('Error appending response:', e);
                    $('#pending_trainings_content').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error displaying data. Please refresh.</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('XHR Status:', status);
                console.error('Error object:', error);
                console.error('Response Text:', xhr.responseText ? xhr.responseText.substring(0, 500) : 'No response text');
                console.error('Status Code:', xhr.status);
                $('#pending_trainings_content').html('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error loading pending trainings. Status: ' + xhr.status + '</div>');
            }
        });
    }
    
    $('#approved-tab').on('shown.bs.tab', function(e) {
        loadApprovedTrainings();
    });
    
    function loadApprovedTrainings() {
        var limit = $('#limit_approved').val() || 10;
        var search = $('#search_approved').val() || '';
        var page = 1;
        
        console.log('Loading approved trainings - Limit:', limit, 'Search:', search);
        
        $.ajax({
            url: '<?= site_url("trainings/load_approved_trainings") ?>',
            method: 'POST',
            data: {
                limit: limit,
                search: search,
                page: page
            },
            success: function(response) {
                console.log('=== APPROVED RESPONSE ===');
                console.log('First 200 chars:', response ? response.substring(0, 200) : 'EMPTY RESPONSE');
                
                if (!response || response.trim().startsWith('<!DOCTYPE') || response.trim().startsWith('<html')) {
                    console.error('ERROR: Server returned HTML page instead of table content');
                    $('#approved_trainings_content').html('<tr><td colspan="11" class="text-center text-danger">Error loading data. Please refresh the page.</td></tr>');
                    return;
                }
                
                $('#approved_trainings_content').html(response);
            },
            error: function(xhr, status, error) {
                $('#approved_trainings_content').html('<p class="text-danger text-center">Error loading approved trainings.</p>');
                console.error('Error loading approved trainings:', error);
            }
        });
    }
});
