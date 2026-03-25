<?= $this->extend('layout/dashboard_admin') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mt-4">Manage Trainings</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item active">Trainings</li>
            </ol>
        </div>
    </div>

    <?= $this->include('layout/messages') ?>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <title><?= MY_APP_NAME; ?> - Trainings</title>
    
    <!-- Bootstrap 5 CSS -->
    <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
    <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
    <link href="<?= css('styles.css') ?>" rel="stylesheet" />
    <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    
    <style>
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
        
        .card-body.filters-card {
            background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
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
                                    <i class="fas fa-graduation-cap"></i> All Trainings
                                </h1>
                                <ol class="breadcrumb mb-3">
                                    <li class="breadcrumb-item active">Manage and view all training programs</li>
                                </ol>
                            </div>
                            <div>
                                <ul class="page_title_button" style="list-style: none; margin: 0;">
                                    <li style="">
                                        <a href="<?= site_url('trainings/add') ?>" class="btn btn-light">
                                            <i class="fas fa-plus-circle"></i>
                                            <div class="text-muted">Add New Training</div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?= $this->include('layout/messages') ?>
                
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
                                        $categories = array_unique(array_column($trainings, 'training_category_name'));
                                        sort($categories);
                                        foreach($categories as $cat):
                                            if($cat):
                                    ?>
                                        <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                                    <?php 
                                            endif;
                                        endforeach; 
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-3">
                                <label for="filterStatus" class="form-label fw-bold">
                                    <i class="fas fa-check-circle"></i> Filter by Status
                                </label>
                                <select class="form-select" id="filterStatus">
                                    <option value="">All Statuses</option>
                                    <option value="Upcoming">Upcoming</option>
                                    <option value="Open">Open</option>
                                    <option value="Closed">Closed</option>
                                    <option value="Completed">Completed</option>
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
                
                <!-- Trainings Grid -->
                <div class="row">
                    <?php if(empty($trainings)): ?>
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                <h4>No Trainings Available</h4>
                                <p>There are no trainings in the system yet.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($trainings as $training): ?>
                            <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                                <div class="card training-card" 
                                     data-category="<?= esc($training['training_category_name'] ?? '') ?>"
                                     data-status="<?= esc($training['status_name'] ?? '') ?>"
                                     data-name="<?= esc(strtolower($training['training_name'])) ?>"
                                     data-facilitator="<?= esc(strtolower($training['training_facilitator'] ?? '')) ?>"
                                     data-venue="<?= esc(strtolower($training['training_venue'] ?? '')) ?>">
                                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fc;">
                                        <span class="category-badge">
                                            <i class="fas fa-tag"></i> <?= esc($training['training_category_name'] ?? 'Uncategorized') ?>
                                        </span>
                                        <span class="status-badge status-<?= strtolower($training['status_name'] ?? 'pending') ?>">
                                            <?= esc($training['status_name'] ?? 'Pending') ?>
                                        </span>
                                    </div>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title text-primary mb-3">
                                            <i class="fas fa-graduation-cap"></i> 
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
                                        
                                        <?php if($training['objective']): ?>
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <strong>Objective:</strong>
                                                    <p class="mb-0 small"><?= esc(substr($training['objective'], 0, 100)) ?><?= strlen($training['objective']) > 100 ? '...' : '' ?></p>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-footer d-flex justify-content-between">
                                        <a href="<?= site_url('trainings/view/' . $training['id_training']) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        <div>
                                            <a href="<?= site_url('trainings/edit/' . $training['id_training']) ?>" class="btn btn-sm btn-outline-secondary me-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?= $training['id_training'] ?>, '<?= esc(addslashes($training['training_name'])) ?>')">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
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
            
            // Delete confirmation function
            window.confirmDelete = function(trainingId, trainingName) {
                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete <strong>${trainingName}</strong>.<br><br>This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Perform deletion via AJAX
                        $.ajax({
                            url: '<?= site_url('trainings/delete') ?>/' + trainingId,
                            type: 'POST',
                            data: {
                                id: trainingId
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Training has been successfully deleted.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'Failed to delete training. Please try again.'
                                });
                            }
                        });
                    }
                });
            };
        });
    </script>

<?= $this->endSection() ?>
