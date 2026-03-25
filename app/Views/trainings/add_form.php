<?php $request = \Config\Services::request(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <title><?= MY_APP_NAME; ?> - Create Training</title>
    
    <!-- Bootstrap 5 CSS -->
    <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
    <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
    <link href="<?= css('styles.css') ?>" rel="stylesheet" />
    <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .form-section {
            background-color: #f8f9fc;
            border-radius: 0.375rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4e73df;
            margin-bottom: 1rem;
            border-bottom: 2px solid #e3e6f0;
            padding-bottom: 0.5rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .checkbox-custom {
            padding: 0.5rem 0;
        }
        
        .help-block {
            color: #e74a3b;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    
    <?= $this->include('layout/navbar_admin') ?>
    
    <div id="layoutSidenav">
        
        <?= $this->include('layout/sidebar') ?>
        
        <div id="layoutSidenav_content">
            
            <div class="container-fluid px-4">
                
                <div class="row mt-4 mb-4" style="background-color: white; border-radius: 0.375rem; margin:1rem 0 1rem 0; padding: 1.5rem;">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="mt-2 mb-3">
                                    <i class="fas fa-plus-circle"></i> Create Training
                                </h1>
                                <ol class="breadcrumb mb-3">
                                    <li class="breadcrumb-item"><a href="<?= site_url('trainings') ?>">All Trainings</a></li>
                                    <li class="breadcrumb-item active">Create New Training</li>
                                </ol>
                            </div>
                            <div>
                                <ul class="page_title_button" style="list-style: none; margin: 0;">
                                    <li style="">
                                        <a href="<?= site_url('trainings') ?>" class="btn btn-light">
                                            <i class="fas fa-arrow-circle-left"></i>
                                            <div class="text-muted">Back</div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?= $this->include('layout/messages') ?>
                
                <!-- Training Form -->
                <form action="<?= site_url('trainings/add') ?>" method="post" id="createTrainingForm">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <!-- Left Column - Basic Information and Options -->
                        <div class="col-lg-6">
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-info-circle"></i> Basic Information
                                </div>
                                
                                <!-- Row 1: Training Title and Category -->
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="training_name" class="form-label fw-bold">Training Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('training_name') ? 'is-invalid' : '' ?>" 
                                               id="training_name" name="training_name" 
                                               value="<?= old('training_name') ?>" 
                                               placeholder="Enter training title" required>
                                        <?php if(isset($validation) && $validation->hasError('training_name')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_name') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="training_category_id" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                        <select class="form-select <?= isset($validation) && $validation->hasError('training_category_id') ? 'is-invalid' : '' ?>" 
                                                id="training_category_id" name="training_category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>" <?= old('training_category_id') == $cat['id'] ? 'selected' : '' ?>>
                                                    <?= esc($cat['category_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if(isset($validation) && $validation->hasError('training_category_id')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_category_id') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Row 2: Date From, Date To, Enrollment Deadline -->
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="training_datefrom" class="form-label fw-bold">Date From <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control <?= isset($validation) && $validation->hasError('training_datefrom') ? 'is-invalid' : '' ?>" 
                                               id="training_datefrom" name="training_datefrom" 
                                               value="<?= old('training_datefrom') ?>" required>
                                        <?php if(isset($validation) && $validation->hasError('training_datefrom')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_datefrom') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="training_dateto" class="form-label fw-bold">Date To <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control <?= isset($validation) && $validation->hasError('training_dateto') ? 'is-invalid' : '' ?>" 
                                               id="training_dateto" name="training_dateto" 
                                               value="<?= old('training_dateto') ?>" required>
                                        <?php if(isset($validation) && $validation->hasError('training_dateto')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_dateto') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="training_deadline" class="form-label fw-bold">Enrollment Deadline</label>
                                        <input type="date" class="form-control <?= isset($validation) && $validation->hasError('training_deadline') ? 'is-invalid' : '' ?>" 
                                               id="training_deadline" name="training_deadline" 
                                               value="<?= old('training_deadline') ?>">
                                        <?php if(isset($validation) && $validation->hasError('training_deadline')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_deadline') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Row 3: Facilitator and Venue -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="training_facilitator" class="form-label fw-bold">Facilitator <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('training_facilitator') ? 'is-invalid' : '' ?>" 
                                               id="training_facilitator" name="training_facilitator" 
                                               value="<?= old('training_facilitator') ?>" 
                                               placeholder="Enter facilitator name" required>
                                        <?php if(isset($validation) && $validation->hasError('training_facilitator')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_facilitator') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="training_venue" class="form-label fw-bold">Venue <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('training_venue') ? 'is-invalid' : '' ?>" 
                                               id="training_venue" name="training_venue" 
                                               value="<?= old('training_venue') ?>" 
                                               placeholder="Enter training venue" required>
                                        <?php if(isset($validation) && $validation->hasError('training_venue')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_venue') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Row 4: Training Hours and Attendees -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="training_hours" class="form-label fw-bold">Training Hours <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('training_hours') ? 'is-invalid' : '' ?>" 
                                               id="training_hours" name="training_hours" 
                                               value="<?= old('training_hours') ?>" 
                                               placeholder="e.g., 8" min="1" required>
                                        <?php if(isset($validation) && $validation->hasError('training_hours')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_hours') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="training_attendees" class="form-label fw-bold">Limit/Number of Attendees</label>
                                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('training_attendees') ? 'is-invalid' : '' ?>" 
                                               id="training_attendees" name="training_attendees" 
                                               value="<?= old('training_attendees') ?>" 
                                               placeholder="e.g., 30" min="1">
                                        <?php if(isset($validation) && $validation->hasError('training_attendees')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('training_attendees') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-cog"></i> Options
                                </div>
                                
                                <div class="checkbox-custom">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="training_is_local" name="training_is_local" value="1" 
                                               <?= old('training_is_local') ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold" for="training_is_local">
                                            Is Local Training
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="checkbox-custom">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="training_require_upload" name="training_require_upload" value="1" 
                                               <?= old('training_require_upload') ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold" for="training_require_upload">
                                            Require Upload
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="checkbox-custom">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="training_require_feedback" name="training_require_feedback" value="1" 
                                               <?= old('training_require_feedback') ? 'checked' : '' ?>>
                                        <label class="form-check-label fw-bold" for="training_require_feedback">
                                            Require Feedback
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Details (Descriptions and Learnings) -->
                        <div class="col-lg-6">
                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-book-open"></i> Details
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Training Description</label>
                                    <div id="descriptionsContainer">
                                        <div class="description-item mb-2">
                                            <textarea class="form-control <?= isset($validation) && $validation->hasError('training_description[]') ? 'is-invalid' : '' ?>" 
                                                      name="training_description[]" 
                                                      rows="3" 
                                                      placeholder="Enter training description"><?= old('training_description.0') ?></textarea>
                                            <small class="text-muted">Maximum 2000 characters</small>
                                            <?php if(isset($validation) && $validation->hasError('training_description[]')): ?>
                                                <div class="invalid-feedback d-block"><?= $validation->getError('training_description[]') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addDescription()">
                                        <i class="fas fa-plus"></i> Add Another Description
                                    </button>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Training Learnings</label>
                                    <div id="learningsContainer">
                                        <div class="learning-item mb-2">
                                            <textarea class="form-control <?= isset($validation) && $validation->hasError('training_learnings[]') ? 'is-invalid' : '' ?>" 
                                                      name="training_learnings[]" 
                                                      rows="3" 
                                                      placeholder="What will participants learn?"><?= old('training_learnings.0') ?></textarea>
                                            <small class="text-muted">Maximum 2000 characters</small>
                                            <?php if(isset($validation) && $validation->hasError('training_learnings[]')): ?>
                                                <div class="invalid-feedback d-block"><?= $validation->getError('training_learnings[]') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addLearning()">
                                        <i class="fas fa-plus"></i> Add Another Learning
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2 mb-4">
                        <a href="<?= site_url('trainings') ?>" class="btn btn-secondary">
                            <i class="fas fa-ban"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Training
                        </button>
                    </div>
                </form>
                
            </div>
            
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="align-items-center justify-content-between small">
                        <div class="text-muted" style="text-align: right;">&copy; 2025 CLSU. All rights reserved. 
                            <br>Powered by <span class="text-success">Management Information System Office (CLSU-MISO)</span>.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
    <script src="<?= js('scripts.js'); ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Show success/error messages with SweetAlert2
            <?php if(session()->getFlashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?= session()->getFlashdata('success') ?>',
                    showConfirmButton: false,
                    timer: 3000
                });
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?= session()->getFlashdata('error') ?>',
                    showConfirmButton: true
                });
            <?php endif; ?>
            
            // Show validation errors summary if validation failed
            <?php if(isset($validation) && $validation->getErrors()): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Errors',
                    html: '<div style="text-align: left;"><ul style="margin: 10px 0; padding-left: 20px;"><?php foreach($validation->getErrors() as $field => $error): ?><li><strong><?= ucfirst(str_replace('_', ' ', $field)) ?>:</strong> <?= $error ?></li><?php endforeach; ?></ul></div>',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            <?php endif; ?>
            
            // Validate date range when end date changes
            $('#training_dateto').on('change', function() {
                var dateFrom = new Date($('#training_datefrom').val());
                var dateTo = new Date($(this).val());
                
                if (dateFrom && dateTo) {
                    // Reset time to midnight for accurate comparison
                    dateFrom.setHours(0, 0, 0, 0);
                    dateTo.setHours(0, 0, 0, 0);
                    
                    if (dateTo < dateFrom) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Date Range',
                            text: 'End date must be after or equal to start date'
                        });
                        $(this).val('');
                    } else {
                        // If deadline is set, validate it against the new end date
                        validateDeadlineAgainstDates();
                    }
                }
            });
            
            // Validate deadline when it changes
            $('#training_deadline').on('change', function() {
                validateDeadlineAgainstDates();
            });
            
            // Also validate when start date changes
            $('#training_datefrom').on('change', function() {
                var dateFrom = new Date($(this).val());
                var dateTo = new Date($('#training_dateto').val());
                
                if (dateFrom && dateTo) {
                    dateFrom.setHours(0, 0, 0, 0);
                    dateTo.setHours(0, 0, 0, 0);
                    
                    // Clear end date if it's now before start date
                    if (dateTo < dateFrom) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Date Range',
                            text: 'End date must be after or equal to start date'
                        });
                        $('#training_dateto').val('');
                    }
                }
                // Validate deadline if it exists
                validateDeadlineAgainstDates();
            });
            
            // Function to validate deadline against start and end dates
            function validateDeadlineAgainstDates() {
                var dateFrom = new Date($('#training_datefrom').val());
                var dateTo = new Date($('#training_dateto').val());
                var deadline = new Date($('#training_deadline').val());
                
                if (dateFrom && dateTo && deadline) {
                    dateFrom.setHours(0, 0, 0, 0);
                    dateTo.setHours(0, 0, 0, 0);
                    deadline.setHours(0, 0, 0, 0);
                    
                    // Check if deadline is after or equal to start date
                    if (deadline >= dateFrom) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Deadline',
                            text: 'Enrollment deadline must be before the start date'
                        });
                        $('#training_deadline').val('');
                        return;
                    }
                    
                    // Check if deadline is after or equal to end date
                    if (deadline >= dateTo) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Deadline',
                            text: 'Enrollment deadline must be before the end date'
                        });
                        $('#training_deadline').val('');
                        return;
                    }
                }
            }
            
            // Character counter for textareas
            $('#descriptionsContainer').on('input', 'textarea', function() {
                var maxLength = 2000;
                var currentLength = $(this).val().length;
                console.log(currentLength + '/' + maxLength);
            });
            
            $('#learningsContainer').on('input', 'textarea', function() {
                var maxLength = 2000;
                var currentLength = $(this).val().length;
                console.log(currentLength + '/' + maxLength);
            });
        });
        
        // Add description function
        function addDescription() {
            const container = document.getElementById('descriptionsContainer');
            const index = container.children.length;
            const div = document.createElement('div');
            div.className = 'description-item mb-2';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted fw-bold">Description #${index + 1}</small>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDescription(this)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <textarea class="form-control" 
                          name="training_description[]" 
                          rows="3" 
                          placeholder="Enter training description"></textarea>
                <small class="text-muted">Maximum 2000 characters</small>
            `;
            container.appendChild(div);
        }
        
        // Remove description function
        function removeDescription(button) {
            const container = document.getElementById('descriptionsContainer');
            if (container.children.length > 1) {
                button.closest('.description-item').remove();
                // Renumber remaining items
                Array.from(container.children).forEach((item, index) => {
                    const label = item.querySelector('small.fw-bold');
                    if (label) {
                        label.textContent = `Description #${index + 1}`;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'At least one description is required'
                });
            }
        }
        
        // Add learning function
        function addLearning() {
            const container = document.getElementById('learningsContainer');
            const index = container.children.length;
            const div = document.createElement('div');
            div.className = 'learning-item mb-2';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <small class="text-muted fw-bold">Learning #${index + 1}</small>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLearning(this)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                <textarea class="form-control" 
                          name="training_learnings[]" 
                          rows="3" 
                          placeholder="What will participants learn?"></textarea>
                <small class="text-muted">Maximum 2000 characters</small>
            `;
            container.appendChild(div);
        }
        
        // Remove learning function
        function removeLearning(button) {
            const container = document.getElementById('learningsContainer');
            if (container.children.length > 1) {
                button.closest('.learning-item').remove();
                // Renumber remaining items
                Array.from(container.children).forEach((item, index) => {
                    const label = item.querySelector('small.fw-bold');
                    if (label) {
                        label.textContent = `Learning #${index + 1}`;
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cannot Remove',
                    text: 'At least one learning objective is required'
                });
            }
        }
    </script>
    
</body>
</html>
