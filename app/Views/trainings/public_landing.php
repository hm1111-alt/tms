<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
        <title>Training Management System - Available Trainings</title>

        <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
        
        <link href="<?= assets('bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <link href="<?= css('styles.css') ?>" rel="stylesheet">
        <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
        
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            body {
                background-color: #f8f9fa;
            }
            
            .sb-nav-fixed #layoutSidenav_content {
                padding-top: 56px;
            }
            
            .card {
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                border: none;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            }
            
            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
            }
            
            .training-card {
                height: 100%;
                display: flex;
                flex-direction: column;
            }
            
            .training-card .card-body {
                flex: 1;
                display: flex;
                flex-direction: column;
            }
            
            .training-card .card-footer {
                background-color: #f8f9fc;
                border-top: 1px solid #e3e6f0;
                padding: 0.75rem 1.25rem;
            }
            
            .status-badge {
                font-size: 0.75rem;
                padding: 0.35rem 0.65rem;
                border-radius: 0.35rem;
                font-weight: 600;
                text-transform: uppercase;
            }
            
            .status-upcoming { background-color: #36b9cc; color: white; }
            .status-open { background-color: #1cc88a; color: white; }
            .status-ongoing { background-color: #f6c23e; color: white; }
            .status-closed { background-color: #858796; color: white; }
            .status-completed { background-color: #4e73df; color: white; }
            
            .category-badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
                background-color: #e9ecef;
                color: #495057;
                border-radius: 0.25rem;
                font-weight: 500;
            }
            
            /* Filter Section Styles */
            .form-select:focus, .form-control:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
            }
        </style>
    </head>
    
    <body class="sb-nav-fixed">
        <?= $this->include('layout/navbar_public') ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                    <div class="col-xl-8">
                        <h1 class="mt-4 mb-3">
                            Available Trainings
                        </h1>
                        <!-- DEBUG: total=<?= $total_trainings ?? 'X' ?>, count=<?= count($trainings ?? []) ?> -->
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item active">
                                Training Management System
                            </li>
                        </ol>
                    </div>
                    <div class="col-xl-4">
                        <div class="text-end mt-4">
                            <ul class="page_title_button" style="list-style: none; margin: 0;">
                                <li>
                                    <a href="<?= site_url('dashboard') ?>" class="btn btn-light">
                                        <i class="fas fa-arrow-circle-left"></i>
                                        <div class="text-muted">Back to Dashboard</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <p class="mb-0 text-muted">
                            Browse our comprehensive list of training programs designed to enhance your skills and professional development.
                        </p>
                    </div>
                </div>
                
                <?= $this->include('layout/messages') ?>

    <div class="container-fluid px-4">
        
        <!-- Filters and Search -->
        <div class="card mb-4">
            <div class="card-body filters-card">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="filterCategory" class="form-label fw-bold">
                            <i class="fas fa-tags"></i> Filter by Category
                        </label>
                        <select class="form-select" id="filterCategory">
                            <option value="">All Categories</option>
                            <?php 
                                if(!empty($trainings)):
                                    $categories = array_unique(array_column($trainings, 'training_category_name'));
                                    sort($categories);
                                    foreach($categories as $cat):
                                        if($cat):
                            ?>
                                <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                            <?php 
                                        endif;
                                    endforeach; 
                                endif; 
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="filterStatus" class="form-label fw-bold">
                            <i class="fas fa-check-circle"></i> Filter by Status
                        </label>
                        <select class="form-select" id="filterStatus">
                            <option value="">All Statuses</option>
                            <?php 
                                if(!empty($trainings)):
                                    $statuses = array_unique(array_column($trainings, 'status_name'));
                                    sort($statuses);
                                    foreach($statuses as $status):
                                        if($status):
                            ?>
                                <option value="<?= esc($status) ?>"><?= esc($status) ?></option>
                            <?php 
                                        endif;
                                    endforeach; 
                                else:
                            ?>
                                <option value="Upcoming">Upcoming</option>
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                                <option value="Completed">Completed</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="searchInput" class="form-label fw-bold">
                            <i class="fas fa-search"></i> Search Trainings
                        </label>
                        <input type="text" class="form-control" id="searchInput" 
                               placeholder="Search by training name, facilitator, or venue...">
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12 text-end">
                        <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()">
                            <i class="fas fa-redo"></i> Clear Filters
                        </button>
                        <span class="ms-2 text-muted" id="resultCount">
                            Showing <?= count($trainings) ?> of <?= count($trainings) ?> trainings
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if(empty($trainings)): ?>
            <div class="alert alert-info shadow-sm" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                No trainings available at the moment. Please check back later!
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($trainings as $training): ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="card training-card" 
                             data-category="<?= esc($training['training_category_name'] ?? '') ?>"
                             data-status="<?= esc($training['status_name'] ?? '') ?>"
                             data-name="<?= esc(strtolower($training['training_name'])) ?>"
                             data-facilitator="<?= esc(strtolower($training['training_facilitator'] ?? '')) ?>"
                             data-venue="<?= esc(strtolower($training['training_venue'] ?? '')) ?>">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="category-badge">
                                        <i class="fas fa-tag"></i> <?= esc($training['training_category_name'] ?? 'Uncategorized') ?>
                                    </span>
                                    <span class="status-badge status-<?= strtolower($training['status_name'] ?? 'pending') ?>">
                                        <?= esc($training['status_name'] ?? 'Pending') ?>
                                    </span>
                                </div>
                                
                                <h5 class="card-title text-primary mb-3">
                                    <?= esc($training['training_name']) ?>
                                </h5>
                                
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt"></i> 
                                        <strong>Date:</strong> 
                                        <?php 
                                            if($training['training_datefrom']) {
                                                echo date('M d, Y', strtotime($training['training_datefrom']));
                                                if($training['training_dateto'] && $training['training_dateto'] != $training['training_datefrom']) {
                                                    echo ' - ' . date('M d, Y', strtotime($training['training_dateto']));
                                                }
                                            } else {
                                                echo 'TBA';
                                            }
                                        ?>
                                    </small>
                                </div>
                                
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> 
                                        <strong>Duration:</strong> <?= esc($training['training_hours'] ?? 'N/A') ?> hours 
                                        <?php 
                                            $date_from = !empty($training['training_datefrom']) ? date('M d, Y', strtotime($training['training_datefrom'])) : '';
                                            $date_to = !empty($training['training_dateto']) ? date('M d, Y', strtotime($training['training_dateto'])) : '';
                                            if ($date_from && $date_to && $date_from !== $date_to): 
                                        ?>
                                            (<?= $date_from ?> - <?= $date_to ?>)
                                        <?php elseif ($date_from): ?>
                                            (<?= $date_from ?>)
                                        <?php endif; ?>
                                    </small>
                                </div>
                                
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <strong>Venue:</strong> <?= esc($training['training_venue'] ?? 'TBA') ?>
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-chalkboard-teacher"></i> 
                                        <strong>Facilitator:</strong> <?= esc($training['training_facilitator'] ?? 'TBA') ?>
                                    </small>
                                </div>
                                
                                <?php if(!empty($training['objective'])): ?>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <strong>Objective:</strong>
                                            <p class="mb-0 small"><?= esc(substr($training['objective'], 0, 100)) ?><?= strlen($training['objective']) > 100 ? '...' : '' ?></p>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer d-flex justify-content-between">
                                <?php if(session()->get('logged_in')): ?>
                                    <?php if(isset($training['is_enrolled']) && $training['is_enrolled']): ?>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fas fa-check-circle"></i> Already Joined
                                        </button>
                                    <?php elseif(isset($training['has_attended']) && $training['has_attended']): ?>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fas fa-check-circle"></i> Already Attended
                                        </button>
                                    <?php else: ?>
                                        <?php 
                                        $status_name = strtolower($training['status_name'] ?? '');
                                        $is_ongoing = isset($training['is_ongoing']) && $training['is_ongoing'];
                                        $is_open = ($status_name === 'open');
                                        
                                        // Check slot availability
                                        $max_capacity = $training['max_no_of_attendees'] ?? 0;
                                        $current_attendees = $training['no_of_attendees'] ?? 0;
                                        $no_slots = ($max_capacity > 0 && $current_attendees >= $max_capacity);
                                        
                                        $is_disabled = !$is_open || $is_ongoing || $no_slots;
                                        ?>
                                        <button class="btn btn-sm <?= $is_disabled ? 'btn-secondary disabled' : 'btn-success join-training-btn' ?>" 
                                                data-training-id="<?= $training['id_training'] ?>"
                                                data-training-name="<?= esc($training['training_name']) ?>"
                                                <?= $is_disabled ? 'disabled' : '' ?>
                                                title="<?= !$is_open ? 'Registration not yet open' : ($is_ongoing ? 'Training is ongoing' : ($no_slots ? 'No slots available' : '')) ?>">
                                            <i class="fas fa-user-plus"></i> Join Now
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?= site_url('trainings/view/' . $training['id_training']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                <?php else: ?>
                                    <?php 
                                    $status_name = strtolower($training['status_name'] ?? '');
                                    $is_open = ($status_name === 'open');
                                    $is_ongoing = isset($training['is_ongoing']) && $training['is_ongoing'];
                                    $is_disabled = !$is_open || $is_ongoing;
                                    ?>
                                    <a href="<?= site_url('/login') ?>" class="btn btn-sm <?= $is_disabled ? 'btn-secondary disabled' : 'btn-success' ?>" <?= $is_disabled ? 'disabled tabindex="-1" aria-disabled="true"' : '' ?>>
                                        <i class="fas fa-sign-in-alt"></i> Login to Join
                                    </a>
                                    <a href="<?= site_url('trainings/view/' . $training['id_training']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-info-circle"></i> View Details
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
            </main>
        </div>

<script>
    $(document).ready(function() {
        // Store all trainings data
        const allTrainings = <?= json_encode($trainings) ?>;
        
        // Filter function
        function filterTrainings() {
            const categoryFilter = $('#filterCategory').val().toLowerCase();
            const statusFilter = $('#filterStatus').val().toLowerCase();
            const searchTerm = $('#searchInput').val().toLowerCase();
            
            let visibleCount = 0;
            
            $('.training-card').each(function() {
                const card = $(this);
                const category = card.data('category') || '';
                const status = card.data('status') || '';
                const name = card.data('name') || '';
                const facilitator = card.data('facilitator') || '';
                const venue = card.data('venue') || '';
                
                // Check category filter
                const categoryMatch = categoryFilter === '' || category.toLowerCase().includes(categoryFilter);
                
                // Check status filter
                const statusMatch = statusFilter === '' || status.toLowerCase() === statusFilter;
                
                // Check search term
                const searchMatch = searchTerm === '' || 
                                   name.toLowerCase().includes(searchTerm) ||
                                   facilitator.toLowerCase().includes(searchTerm) ||
                                   venue.toLowerCase().includes(searchTerm);
                
                // Show/hide based on all filters
                if (categoryMatch && statusMatch && searchMatch) {
                    card.closest('.col-xl-4, .col-lg-6, .col-md-6').fadeIn();
                    visibleCount++;
                } else {
                    card.closest('.col-xl-4, .col-lg-6, .col-md-6').fadeOut();
                }
            });
            
            // Update result count
            $('#resultCount').text(`Showing ${visibleCount} of ${allTrainings.length} trainings`);
        }
        
        // Event listeners for filters
        $('#filterCategory').on('change', filterTrainings);
        $('#filterStatus').on('change', filterTrainings);
        $('#searchInput').on('input', filterTrainings);
        
        // Clear filters function
        window.clearFilters = function() {
            $('#filterCategory').val('');
            $('#filterStatus').val('');
            $('#searchInput').val('');
            filterTrainings();
        };
        
        // Join training button with confirmation
        $(document).on('click', '.join-training-btn', function() {
            const btn = $(this);
            const trainingId = btn.data('training-id');
            const trainingName = btn.data('training-name');
            
            // Show confirmation dialog
            Swal.fire({
                title: 'Join Training?',
                html: `<p>Are you sure you want to join <strong>${trainingName}</strong>?</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check"></i> Yes, Join Now!',
                cancelButtonText: '<i class="fas fa-times"></i> Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Enrolling you in the training...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Redirect to enroll endpoint
                    window.location.href = '<?= site_url('trainings/register/') ?>' + trainingId;
                }
            });
        });
    });
</script>

<?= $this->include('layout/footer') ?>

<?= $this->renderSection('footer_jscript') ?>
