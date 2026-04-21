<?php
$userType = session()->get('user_type_name') ?? 'guest';

// Determine navbar based on user type
if (stripos($userType, 'admin') !== false) {
    $navbarFile = 'layout/navbar_admin';
    $layoutName = 'Admin';
} elseif (stripos($userType, 'guest') !== false) {
    $navbarFile = 'layout/navbar_public';
    $layoutName = 'Guest';
} else {
    $navbarFile = 'layout/navbar_employee';
    $layoutName = 'Employee';
}

// Debug: Log what we're trying to load
log_message('debug', 'My Trainings - User type: ' . $userType . ', Navbar: ' . $navbarFile);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <title><?= MY_APP_NAME; ?> - My Trainings</title>
    
    <!-- Bootstrap 5 CSS -->
    <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
    <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
    <link href="<?= css('styles.css') ?>" rel="stylesheet" />
    <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Prevent navbar overlap */
        #layoutSidenav_content {
            margin-left: 0 !important;
        }
        
        /* Add padding for fixed navbar */
        .sb-nav-fixed #layoutSidenav_content {
            padding-top: 56px;
        }
        
        main {
            min-height: calc(100vh - 56px);
        }
        
        .container-fluid.px-4 {
            padding-top: 0px !important;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    
    <?php echo $this->include($navbarFile); ?>
    
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                
                <!-- Page Header - Same as Dashboard -->
                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                    <div class="col-xl-8">
                        <h1 class="mt-4 mb-3">My Trainings</h1>
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item active">My Trainings</li>
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
                
                <?= $this->include('layout/messages') ?>

<!-- Statistics Cards -->
<div class="row" style="margin-bottom: 15px;">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm border-0" style="min-height: 120px !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0" style="font-size: 0.9rem;">Upcoming</h6>
                        <h2 class="mb-0 fw-bold" style="font-size: 2rem;"><?= number_format($total_upcoming ?? 0) ?></h2>
                    </div>
                    <i class="fas fa-calendar fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm border-0" style="min-height: 120px !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0" style="font-size: 0.9rem;">Ongoing</h6>
                        <h2 class="mb-0 fw-bold" style="font-size: 2rem;"><?= number_format($total_ongoing ?? 0) ?></h2>
                    </div>
                    <i class="fas fa-play-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white shadow-sm border-0" style="min-height: 120px !important;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0" style="font-size: 0.9rem;">Completed</h6>
                        <h2 class="mb-0 fw-bold" style="font-size: 2rem;"><?= number_format($total_completed ?? 0) ?></h2>
                    </div>
                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-2" id="myTrainingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
            <i class="fas fa-calendar me-2"></i>Upcoming (<?= $total_upcoming ?? 0 ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab" aria-controls="ongoing" aria-selected="false">
            <i class="fas fa-play-circle me-2"></i>Ongoing (<?= $total_ongoing ?? 0 ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
            <i class="fas fa-check-circle me-2"></i>Completed (<?= $total_completed ?? 0 ?>)
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="myTrainingsTabContent">
    <!-- Upcoming Trainings Tab -->
    <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
        
        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm" style="border: none; border-radius: 0.375rem; background-color: #FFF; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="searchUpcoming" class="form-label fw-bold">
                                <i class="fas fa-search"></i> Search Trainings
                            </label>
                            <input type="text" class="form-control" id="searchUpcoming" 
                                   placeholder="Search by training name, facilitator, or venue...">
                        </div>
                        <div class="col-md-6">
                            <label for="filterUpcomingCategory" class="form-label fw-bold">
                                <i class="fas fa-tags"></i> Filter by Category
                            </label>
                            <select class="form-select" id="filterUpcomingCategory">
                                <option value="">All Categories</option>
                                <?php 
                                    $categories = [];
                                    foreach($upcoming_trainings_list as $training) {
                                        if(!empty($training['training_category_name'])) {
                                            $categories[] = $training['training_category_name'];
                                        }
                                    }
                                    $categories = array_unique($categories);
                                    sort($categories);
                                    foreach($categories as $cat):
                                ?>
                                    <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-end">
                            <button class="btn btn-sm btn-outline-secondary" onclick="clearUpcomingFilters()">
                                <i class="fas fa-redo"></i> Clear Filters
                            </button>
                            <span class="ms-2 text-muted" id="upcomingResultCount">
                                Showing <?= count($upcoming_trainings_list) ?> of <?= count($upcoming_trainings_list) ?> trainings
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm" style="border: none; border-radius: 0.375rem; background-color: #FFF; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
                    <!-- Entries per page dropdown -->
                    <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div class="datatable-dropdown">
                                <label>
                                    <select id="limit_upcoming" class="datatable-selector">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="all">All</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: inline-block; min-width: 100%;">
                        <table class="table table-hover" id="upcomingTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Training Name</th>
                                    <th>Category</th>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                    <th>Facilitator</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $counter = 1;
                                foreach ($upcoming_trainings_list as $training): ?>
                                    <tr class="upcoming-row" 
                                        data-category="<?= esc(strtolower($training['training_category_name'] ?? '')) ?>"
                                        data-name="<?= esc(strtolower($training['training_name'])) ?>"
                                        data-facilitator="<?= esc(strtolower($training['training_facilitator'] ?? '')) ?>"
                                        data-venue="<?= esc(strtolower($training['training_venue'] ?? '')) ?>">
                                        <td><strong><?= $counter++ ?></strong></td>
                                        <td><?= esc($training['training_name']) ?></td>
                                        <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                        <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                        <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                        <td><?= esc($training['training_facilitator']) ?></td>
                                        <td><span class="badge bg-info"><?= esc($training['status_name'] ?? 'Upcoming') ?></span></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" 
                                                    onclick="viewTrainingDetails(<?= $training['id_training'] ?>, '<?= esc(addslashes($training['training_name'])) ?>')" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php 
                        if (count($upcoming_trainings_list) > 0) {
                            $upcoming_pagination_count = count($upcoming_trainings_list);
                            $upcoming_max_page = ceil($upcoming_pagination_count / 10);
                            $upcoming_details = [
                                'num' => min(10, $upcoming_pagination_count),
                                'aa' => 0,
                                'event_count' => $upcoming_pagination_count,
                                'max_page' => $upcoming_max_page > 0 ? $upcoming_max_page : 1,
                                'page' => 1
                            ];
                            echo view('layout/mytable/my_table_pagination', $upcoming_details);
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ongoing Trainings Tab -->
    <div class="tab-pane fade" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
        <div class="card shadow-sm border-0" style="border: none; border-radius: 0.375rem; background-color: #FFF; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
            <!-- Entries per page dropdown -->
            <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="datatable-dropdown">
                        <label>
                            <select id="limit_ongoing" class="datatable-selector">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <?php if (count($ongoing_trainings_list) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="ongoingTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Training Name</th>
                                    <th>Category</th>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                    <th>Facilitator</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $counter = 1;
                                foreach ($ongoing_trainings_list as $training): ?>
                                    <tr>
                                        <td><strong><?= $counter++ ?></strong></td>
                                        <td><?= esc($training['training_name']) ?></td>
                                        <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                        <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                        <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                        <td><?= esc($training['training_facilitator']) ?></td>
                                        <td><span class="badge bg-success">Ongoing</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" 
                                                    onclick="viewTrainingDetails(<?= $training['id_training'] ?>, '<?= esc(addslashes($training['training_name'])) ?>')" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php 
                        if (count($ongoing_trainings_list) > 0) {
                            $ongoing_pagination_count = count($ongoing_trainings_list);
                            $ongoing_max_page = ceil($ongoing_pagination_count / 10);
                            $ongoing_details = [
                                'num' => min(10, $ongoing_pagination_count),
                                'aa' => 0,
                                'event_count' => $ongoing_pagination_count,
                                'max_page' => $ongoing_max_page > 0 ? $ongoing_max_page : 1,
                                'page' => 1
                            ];
                            echo view('layout/mytable/my_table_pagination', $ongoing_details);
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>You have no ongoing trainings.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Completed Trainings Tab -->
    <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
        <div class="card shadow-sm border-0" style="border: none; border-radius: 0.375rem; background-color: #FFF; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
            <!-- Entries per page dropdown -->
            <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="datatable-dropdown">
                        <label>
                            <select id="limit_completed" class="datatable-selector">
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-0">
                <?php if (count($completed_trainings_list) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="completedTable">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Training Name</th>
                                    <th>Category</th>
                                    <th>Date From</th>
                                    <th>Date To</th>
                                    <th>Facilitator</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $counter = 1;
                                foreach ($completed_trainings_list as $training): ?>
                                    <tr>
                                        <td><strong><?= $counter++ ?></strong></td>
                                        <td><?= esc($training['training_name']) ?></td>
                                        <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                        <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                        <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                        <td><?= esc($training['training_facilitator']) ?></td>
                                        <td><span class="badge bg-success">Completed</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary me-1" 
                                                    onclick="viewTrainingDetails(<?= $training['id_training'] ?>, '<?= esc(addslashes($training['training_name'])) ?>')" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php if (!empty($training['has_feedback'])): ?>
                                                <button class="btn btn-sm btn-secondary me-1" 
                                                        disabled 
                                                        title="Feedback already submitted">
                                                    <i class="fas fa-comment-alt"></i> Feedback
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-warning me-1" 
                                                        onclick="submitFeedback(<?= $training['id_training'] ?>, '<?= esc(addslashes($training['training_name'])) ?>')" 
                                                        title="Submit Feedback">
                                                    <i class="fas fa-comment-alt"></i> Feedback
                                                </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-info" 
                                                    onclick="viewCertificate(<?= $training['id_training'] ?>, '<?= esc(addslashes($training['training_name'])) ?>')" 
                                                    title="View Certificate">
                                                <i class="fas fa-certificate"></i> Certificate
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <!-- Pagination -->
                        <?php 
                        if (count($completed_trainings_list) > 0) {
                            $completed_pagination_count = count($completed_trainings_list);
                            $completed_max_page = ceil($completed_pagination_count / 10);
                            $completed_details = [
                                'num' => min(10, $completed_pagination_count),
                                'aa' => 0,
                                'event_count' => $completed_pagination_count,
                                'max_page' => $completed_max_page > 0 ? $completed_max_page : 1,
                                'page' => 1
                            ];
                            echo view('layout/mytable/my_table_pagination', $completed_details);
                        }
                        ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>You have no completed trainings.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pending Requests Tab -->
    <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <?php if (count($pending_trainings_list) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="pendingTable">
                            <thead>
                                <tr>
                                    <th>Training Name</th>
                                    <th>Category</th>
                                    <th>Date Requested</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending_trainings_list as $training): ?>
                                    <tr>
                                        <td><?= esc($training['training_name'] ?? 'N/A') ?></td>
                                        <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                        <td><?= date('M d, Y', strtotime($training['date_requested'])) ?></td>
                                        <td>
                                            <?php if ($training['is_approved'] == 0): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif ($training['is_approved'] == 1): ?>
                                                <span class="badge bg-success">Approved</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Disapproved</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($training['approve_remarks'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>You have no pending training requests.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

            </div>
        </main>
        
        <?= $this->include('layout/footer') ?>
        
    </div>
    
    <!-- Scripts -->
    <script src="<?= assets('bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= assets('js/scripts.js') ?>"></script>
    <script src="<?= base_url('public/assets/js/myscript/my_table.js'); ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Set up load_table_url for my_table.js to work with each tab
            // This is needed by the existing pagination system
            
            // Upcoming Tab Search and Filter
            $('#searchUpcoming').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.upcoming-row').filter(function() {
                    var name = $(this).data('name') || '';
                    var facilitator = $(this).data('facilitator') || '';
                    var venue = $(this).data('venue') || '';
                    $(this).toggle(name.indexOf(value) > -1 || facilitator.indexOf(value) > -1 || venue.indexOf(value) > -1);
                });
                updateUpcomingCount();
            });
            
            $('#filterUpcomingCategory').on('change', function() {
                var category = $(this).val().toLowerCase();
                $('.upcoming-row').filter(function() {
                    var rowCategory = $(this).data('category') || '';
                    $(this).toggle(category === '' || rowCategory.indexOf(category) > -1);
                });
                updateUpcomingCount();
            });
            
            function updateUpcomingCount() {
                var visible = $('.upcoming-row:visible').length;
                var total = $('.upcoming-row').length;
                $('#upcomingResultCount').text('Showing ' + visible + ' of ' + total + ' trainings');
            }
            
            function clearUpcomingFilters() {
                $('#searchUpcoming').val('');
                $('#filterUpcomingCategory').val('');
                $('.upcoming-row').show();
                updateUpcomingCount();
            }
            
            // View training details function
            window.viewTrainingDetails = function(trainingId, trainingName) {
                // Redirect to the training view page
                window.location.href = '<?= site_url("trainings/view/") ?>' + trainingId;
            };
            
            // Mark attendance function
            window.markAttendance = function(trainingId, trainingName) {
                Swal.fire({
                    icon: 'info',
                    title: 'Mark Attendance',
                    html: '<p>Marking attendance for: <strong>' + trainingName + '</strong></p>' +
                          '<p class="text-muted mt-2">Attendance marking implementation pending...</p>',
                    confirmButtonText: 'OK'
                });
            };
            
            // Submit feedback function
            window.submitFeedback = function(trainingId, trainingName) {
                // Redirect to feedback page
                window.location.href = '<?= site_url("feedback/submit/") ?>' + trainingId;
            };
            
            // View certificate function
            window.viewCertificate = function(trainingId, trainingName) {
                // Redirect to certificate view/download
                window.location.href = '<?= site_url("trainings/certificate/") ?>' + trainingId;
            };
        });
    </script>
    
</body>
</html>
