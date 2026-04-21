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
    <title><?= MY_APP_NAME; ?> - <?= esc($training['training_name'] ?? 'Training Details') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
    <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
    <link href="<?= css('styles.css') ?>" rel="stylesheet" />
    <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .detail-card {
            background-color: white;
            border-radius: 0.375rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .card-header-custom {
            background-color: #f8f9fc;
            color: #333;
            padding: 1.5rem;
            border-radius: 0.375rem 0.375rem 0 0;
            border-bottom: 2px solid #e3e6f0;
        }
        
        .card-body-custom {
            padding: 2rem;
        }
        
        .section-title {
            color: #198754;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 0;
        }
        
        .info-table td {
            padding: 0.5rem 0;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: 600;
            color: #5a5c69;
            width: 35%;
        }
        
        .info-value {
            color: #333;
        }
        
        .content-box {
            background-color: #f8f9fc;
            border-left: 4px solid #198754;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
        }
        
        .content-box h5 {
            color: #198754;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .content-box p {
            color: #5a5c69;
            line-height: 1.6;
            margin-bottom: 0;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }
        
        /* Force no sidebar - full width content */
        #layoutSidenav_content {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        /* Add padding for fixed navbar */
        .sb-nav-fixed #layoutSidenav_content {
            padding-top: 56px;
        }
        
        /* Ensure navbar dropdown works properly */
        .dropdown-menu {
            z-index: 1050 !important;
        }
        
        .nav-item.dropdown {
            position: relative;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    
    <?php 
    // Determine which navbar to use based on user type
    $user_type = session()->get('user_type_name');
    if (stripos($user_type, 'admin') !== false): 
        echo $this->include('layout/navbar_admin');
    elseif (stripos($user_type, 'employee') !== false): 
        echo $this->include('layout/navbar_employee');
    elseif (stripos($user_type, 'guest') !== false): 
        echo $this->include('layout/navbar_public');
    else: 
        echo $this->include('layout/navbar');
    endif; 
    ?>
    
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin-top: 1.5rem; margin-bottom: 1rem;">
                    <div class="col-xl-8">
                        <h1 class="mt-2 mb-3">
                            <?= esc($training['training_name']) ?>
                        </h1>
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="<?= site_url('trainings') ?>">All Trainings</a></li>
                            <li class="breadcrumb-item active">
                                Training Management System
                            </li>
                        </ol>
                    </div>
                    <div class="col-xl-4">
                        <div class="text-end mt-4">
                            <ul class="page_title_button" style="list-style: none; margin: 0;">
                                <li>
                                    <a href="<?= site_url('trainings') ?>" class="btn btn-light">
                                        <i class="fas fa-arrow-circle-left"></i>
                                        <div class="text-muted">Back to Training List</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <p class="mb-0 text-muted">
                            View detailed information about this training program including objectives, schedule, and requirements.
                        </p>
                    </div>
                </div>
                
                
                <?= $this->include('layout/messages') ?>
                
                <!-- Two Column Layout -->
                <div class="row">
                    <!-- Left Column: Description, Learnings & Training Info Card -->
                    <div class="col-lg-6">
                        <div class="detail-card">
                            <div class="card-body-custom">
                                <!-- Description Section -->
                                <?php 
                                // Get descriptions from database
                                $descriptions = [];
                                if (isset($training['from_lib']) && $training['from_lib']) {
                                    $db = \Config\Database::connect();
                                    $descriptions = $db->table('lib_training_des')
                                        ->where('training_id', $training['id_training'])
                                        ->get()
                                        ->getResultArray();
                                }
                                ?>
                                
                                <?php if (!empty($descriptions) || !empty($training['objective'])): ?>
                                <div class="content-box">
                                    <h5><i class="fas fa-align-left"></i> Description</h5>
                                    <?php if (!empty($descriptions)): ?>
                                        <?php foreach($descriptions as $desc): ?>
                                            <p><?= nl2br(esc($desc['training_description'])) ?></p>
                                        <?php endforeach; ?>
                                    <?php elseif (!empty($training['objective'])): ?>
                                        <p><?= nl2br(esc($training['objective'])) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Learnings Section -->
                                <?php 
                                // Get learnings from database
                                $learnings = [];
                                if (isset($training['from_lib']) && $training['from_lib']) {
                                    $db = \Config\Database::connect();
                                    $learnings = $db->table('lib_trainings_learnings')
                                        ->where('training_id', $training['id_training'])
                                        ->get()
                                        ->getResultArray();
                                }
                                ?>
                                
                                <?php if (!empty($learnings) || !empty($training['expertise'])): ?>
                                <div class="content-box">
                                    <h5><i class="fas fa-lightbulb"></i> Learning Objectives</h5>
                                    <ul class="list-unstyled mt-3">
                                        <?php if (!empty($learnings)): ?>
                                            <?php foreach($learnings as $learning): ?>
                                                <li style="margin-bottom: 0.75rem; display: flex; align-items: start; gap: 0.75rem;">
                                                    <i class="fas fa-check-circle" style="color: #28a745; font-size: 1.2rem; margin-top: 0.1rem;"></i>
                                                    <span><?= nl2br(esc($learning['training_learning'])) ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        <?php elseif (!empty($training['expertise'])): ?>
                                            <li style="margin-bottom: 0.75rem; display: flex; align-items: start; gap: 0.75rem;">
                                                <i class="fas fa-check-circle" style="color: #28a745; font-size: 1.2rem; margin-top: 0.1rem;"></i>
                                                <span><?= nl2br(esc($training['expertise'])) ?></span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Training Information -->
                                <h5 class="section-title"><i class="fas fa-info-circle"></i> Training Information</h5>
                                <table class="info-table">
                                    <tr>
                                        <td class="info-label">Type:</td>
                                        <td class="info-value"><span class="badge bg-info badge-custom"><?= esc($training_type) ?></span></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Duration:</td>
                                        <td class="info-value"><strong><?= esc($training['training_hours'] ?? $training['hours'] ?? 'N/A') ?></strong> hours</td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Capacity:</td>
                                        <td class="info-value">
                                            <?php 
                                            $capacity = $training['max_no_of_attendees'] ?? 0;
                                            $registered = $training['no_of_attendees'] ?? 0;
                                            if ($capacity > 0): ?>
                                                <strong><?= esc($registered) ?>/<?= esc($capacity) ?></strong>
                                                <?php 
                                                $slots_left = $capacity - $registered;
                                                if ($slots_left <= 0): ?>
                                                    <span class="badge bg-danger ms-1" style="background-color: #dc3545 !important;">No Slot Available</span>
                                                <?php elseif ($slots_left <= 5): ?>
                                                    <span class="badge bg-warning text-dark ms-1" style="background-color: #ffc107 !important; color: #000 !important;">Only <?= $slots_left ?> left</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success ms-1" style="background-color: #28a745 !important;"><?= $slots_left ?> available</span>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                Unlimited
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Date From:</td>
                                        <td class="info-value"><?= !empty($training['training_datefrom']) ? date('M j, Y', strtotime($training['training_datefrom'])) : 
                                              (!empty($training['date_from']) ? date('M j, Y', strtotime($training['date_from'])) : 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Date To:</td>
                                        <td class="info-value"><?= !empty($training['training_dateto']) ? date('M j, Y', strtotime($training['training_dateto'])) : 
                                              (!empty($training['date_to']) ? date('M j, Y', strtotime($training['date_to'])) : 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Venue:</td>
                                        <td class="info-value"><?= esc($training['training_venue'] ?? $training['venue'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Facilitator:</td>
                                        <td class="info-value"><?= esc($training['training_facilitator'] ?? $training['facilitator'] ?? 'N/A') ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Status & Information Card -->
                    <div class="col-lg-6 mb-4">
                        <div class="detail-card">
                            <div class="card-body-custom">
                                <h5 class="section-title"><i class="fas fa-clipboard-check"></i> Status & Information</h5>
                                <table class="info-table">
                                    <tr>
                                        <td class="info-label">Status:</td>
                                        <td class="info-value">
                                            <?php 
                                            // For lib_trainings, show the actual training status
                                            if (isset($training['from_lib']) && $training['from_lib'] && !empty($training['status_name'])): 
                                                $status_name = strtolower($training['status_name']);
                                                $badge_class = 'bg-secondary';
                                                
                                                // Determine badge color based on status
                                                switch($status_name) {
                                                    case 'upcoming':
                                                        $badge_class = 'bg-info';
                                                        break;
                                                    case 'open':
                                                        $badge_class = 'bg-success';
                                                        break;
                                                    case 'closed':
                                                        $badge_class = 'bg-warning text-dark';
                                                        break;
                                                    case 'ongoing':
                                                        $badge_class = 'bg-primary';
                                                        break;
                                                    case 'completed':
                                                        $badge_class = 'bg-secondary';
                                                        break;
                                                    case 'approved':
                                                        $badge_class = 'bg-success';
                                                        break;
                                                    default:
                                                        $badge_class = 'bg-secondary';
                                                }
                                            ?>
                                                <span class="badge <?= $badge_class ?> badge-custom"><?= esc(ucfirst($training['status_name'])) ?></span>
                                            <?php elseif (isset($training['is_pending']) && $training['is_pending']): ?>
                                                <span class="badge bg-warning badge-custom">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-success badge-custom">Approved</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Date Added:</td>
                                        <td class="info-value"><small class="text-muted"><?= !empty($training['training_added_date']) ? date('M j, Y g:i A', strtotime($training['training_added_date'])) : 
                                               (!empty($training['added_date']) ? date('M j, Y g:i A', strtotime($training['added_date'])) : 
                                               (!empty($training['created_at']) ? date('M j, Y g:i A', strtotime($training['created_at'])) : 'N/A')) ?></small>
                                        </td>
                                    </tr>
                                </table>
                                
                                <!-- Action Buttons -->
                                <div class="mt-4">
                                    <h6 class="mb-3"><i class="fas fa-cogs"></i> Actions</h6>
                                    <div class="d-grid gap-2">
                                                
                                        <!-- Employee/Guest Join/Unjoin Button -->
                                        <?php if(stripos($user_type, 'admin') === false): ?>
                                            <?php 
                                            // Check if training is ongoing or completed
                                            $status_name = strtolower($training['status_name'] ?? '');
                                            $is_ongoing_or_completed = in_array($status_name, ['ongoing', 'completed', 'closed']);
                                            ?>
                                            <?php if(isset($training['is_enrolled']) && $training['is_enrolled']): ?>
                                                <?php if($is_ongoing_or_completed || (isset($training['is_ongoing']) && $training['is_ongoing'])): ?>
                                                    <button class="btn btn-secondary" disabled title="Cannot cancel from <?= ucfirst($status_name) ?> training">
                                                        <i class="fas fa-times-circle"></i> Cancel Registration
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-danger toggle-enrollment" 
                                                            data-training-id="<?= $training['id_training'] ?>" 
                                                            data-action="cancel_registration"
                                                            data-is-enrolled="1">
                                                        <i class="fas fa-times-circle"></i> Cancel Registration
                                                    </button>
                                                <?php endif; ?>
                                            <?php elseif(isset($training['has_attended']) && $training['has_attended']): ?>
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="fas fa-check-circle"></i> Already Attended
                                                </button>
                                            <?php else: ?>
                                                <?php
                                                // Check slot availability
                                                $max_capacity = $training['max_no_of_attendees'] ?? 0;
                                                $current_attendees = $training['no_of_attendees'] ?? 0;
                                                $no_slots = ($max_capacity > 0 && $current_attendees >= $max_capacity);
                                                                                            
                                                // Check if training is open for registration
                                                $is_open_for_registration = ($status_name === 'open');
                                                ?>
                                                <?php if($is_ongoing_or_completed || (isset($training['is_ongoing']) && $training['is_ongoing'])): ?>
                                                    <button class="btn btn-secondary" disabled title="Training is <?= ucfirst($status_name) ?>">
                                                        <i class="fas fa-user-plus"></i> Join now
                                                    </button>
                                                <?php elseif(!$is_open_for_registration): ?>
                                                    <button class="btn btn-secondary" disabled title="Training registration is not yet open">
                                                        <i class="fas fa-user-plus"></i> Join Training
                                                    </button>
                                                <?php elseif($no_slots): ?>
                                                    <button class="btn btn-secondary" disabled title="No slots available">
                                                        <i class="fas fa-user-plus"></i> Join Training
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-success toggle-enrollment" 
                                                            data-training-id="<?= $training['id_training'] ?>" 
                                                            data-action="join"
                                                            data-is-enrolled="0">
                                                        <i class="fas fa-user-plus"></i> Join Training
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <!-- Admin Actions -->
                                        <?php if(stripos($user_type, 'admin') !== false): ?>
                                            <?php 
                                            $status = strtolower($training['status_name'] ?? '');
                                            ?>
                                            
                                            <!-- Toggle Open/Close Registration -->
                                            <?php if(in_array($status, ['upcoming', 'open'])): ?>
                                                <?php 
                                                $isOpen = $status === 'open';
                                                ?>
                                                <button class="btn <?= $isOpen ? 'btn-outline-danger' : 'btn-success' ?> toggle-registration" 
                                                        data-training-id="<?= $training['id_training'] ?>"
                                                        data-is-open="<?= $isOpen ? '1' : '0' ?>">
                                                    <i class="fas fa-<?= $isOpen ? 'lock' : 'lock-open' ?>"></i>
                                                    <span><?= $isOpen ? ' Close Registration' : ' Open Registration' ?></span>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <!-- Manage Sessions (for approved, closed, and ongoing trainings) -->
                                            <?php if(in_array($status, ['approved', 'closed', 'ongoing'])): ?>
                                                <button class="btn btn-info manage-sessions" 
                                                        data-training-id="<?= $training['id_training'] ?>">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <span> Manage Sessions</span>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <!-- Complete Training (only for ongoing trainings) -->
                                            <?php if($status === 'ongoing'): ?>
                                                <button class="btn btn-success complete-training" 
                                                        data-training-id="<?= $training['id_training'] ?>"
                                                        data-training-name="<?= esc($training['training_name']) ?>">
                                                    <i class="fas fa-check-circle"></i>
                                                    <span> Complete Training</span>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Training Sessions Card - Only show for enrolled users in ongoing trainings -->
                        <?php if(isset($training['is_enrolled']) && $training['is_enrolled'] && isset($training['is_ongoing']) && $training['is_ongoing'] && !empty($training['sessions'])): ?>
                        <div class="detail-card">
                            <div class="card-body-custom" style="padding: 1.5rem;">
                                <h5 class="section-title"><i class="fas fa-calendar-alt"></i> Training Sessions</h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $session_counter = 1;
                                            foreach($training['sessions'] as $session): 
                                                // Check if session has been started (has started_at)
                                                $is_started = !empty($session['started_at']);
                                                $is_ended = !empty($session['ended_at']);
                                                $has_attended = isset($session['has_attended']) && $session['has_attended'];
                                                
                                                // Determine status
                                                if ($is_ended) {
                                                    $status_badge = '<span class="badge bg-secondary">Done</span>';
                                                } elseif ($is_started) {
                                                    $status_badge = '<span class="badge bg-success">Active</span>';
                                                } else {
                                                    $status_badge = '<span class="badge bg-warning text-dark">Pending</span>';
                                                }
                                            ?>
                                            <tr>
                                                <td><strong><?= $session_counter++ ?></strong></td>
                                                <td><small><?= date('M d', strtotime($session['session_date'])) ?></small></td>
                                                <td><?= $status_badge ?></td>
                                                <td>
                                                    <?php if($is_started && !$is_ended && !$has_attended): ?>
                                                        <button class="btn btn-sm btn-success attend-session-btn" 
                                                                data-session-id="<?= $session['id'] ?>"
                                                                data-training-id="<?= $training['id_training'] ?>"
                                                                data-session-code="<?= esc($session['session_code'] ?? '') ?>">
                                                            <i class="fas fa-user-check"></i>
                                                        </button>
                                                    <?php elseif($has_attended): ?>
                                                        <button class="btn btn-sm btn-secondary" disabled title="Completed">
                                                            <i class="fas fa-check-circle"></i>
                                                        </button>
                                                    <?php elseif(!$is_started): ?>
                                                        <button class="btn btn-sm btn-warning" disabled title="Not Started">
                                                            <i class="fas fa-clock"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-secondary" disabled title="Ended">
                                                            <i class="fas fa-times-circle"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
            
            <?= $this->include('layout/footer') ?>
            
        </div>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= assets('js/scripts.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle enrollment (Join/Cancel) - for employees/guests
            $(document).on('click', '.toggle-enrollment', function() {
                const btn = $(this);
                const trainingId = btn.data('training-id');
                const isEnrolled = btn.data('is-enrolled') == 1;
                const action = isEnrolled ? 'cancel_registration' : 'register';
                
                // Confirm action
                Swal.fire({
                    title: 'Confirm ' + (isEnrolled ? 'Cancel Registration' : 'Join') + ' Training?',
                    html: `<p>Are you sure you want to ${isEnrolled ? 'cancel your registration' : 'join'} this training?</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: isEnrolled ? '#dc3545' : '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, ' + (isEnrolled ? 'Cancel Registration' : 'Join') + '!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Processing...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // AJAX request to toggle enrollment
                        $.ajax({
                            url: '<?= site_url('trainings/') ?>' + action + '/' + trainingId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Successfully ' + (isEnrolled ? 'cancelled registration' : 'registered') + ' for the training!',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'An error occurred. Please try again.'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // If it's a redirect (302), follow it
                                if (xhr.status === 302 || xhr.status === 200) {
                                    location.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'An error occurred. Please try again.'
                                    });
                                }
                            }
                        });
                    }
                });
            });
            
            // Toggle Open/Close Registration
            $(document).on('click', '.toggle-registration', function() {
                const btn = $(this);
                const trainingId = btn.data('training-id');
                const isOpen = btn.data('is-open') == 1;
                const action = isOpen ? 'close' : 'open';
                
                // Confirm action
                Swal.fire({
                    title: 'Confirm ' + (isOpen ? 'Close' : 'Open') + ' Registration?',
                    html: `Are you sure you want to ${action} registration for this training?<br><br>
                           <small>This will ${isOpen ? 'prevent' : 'allow'} users from enrolling.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, ' + (isOpen ? 'Close' : 'Open') + ' it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Updating...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // AJAX request to update registration status
                        $.ajax({
                            url: '<?= site_url('trainings/toggle_registration') ?>',
                            type: 'POST',
                            data: {
                                training_id: trainingId,
                                action: action
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Registration has been ' + (isOpen ? 'closed' : 'opened') + '.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Update button appearance based on new status
                                        const newStatus = response.new_status;
                                        
                                        if (newStatus === 'open') {
                                            btn.removeClass('btn-outline-danger').addClass('btn-success');
                                            btn.find('i').removeClass('fa-lock').addClass('fa-lock-open');
                                            btn.find('span').text(' Close Registration');
                                            btn.data('is-open', 1);
                                        } else if (newStatus === 'closed') {
                                            btn.removeClass('btn-success').addClass('btn-outline-danger');
                                            btn.find('i').removeClass('fa-lock-open').addClass('fa-lock');
                                            btn.find('span').text(' Open Registration');
                                            btn.data('is-open', 0);
                                        }
                                        
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to update registration status.'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Manage sessions button - redirect to sessions management page
            $(document).on('click', '.manage-sessions', function() {
                const trainingId = $(this).data('training-id');
                window.location.href = '<?= site_url('trainings/sessions/') ?>' + trainingId;
            });
            
            // Complete Training button
            $(document).on('click', '.complete-training', function() {
                const trainingId = $(this).data('training-id');
                const trainingName = $(this).data('training-name');
                
                Swal.fire({
                    title: 'Complete Training',
                    html: `
                        <div class="text-start">
                            <p>Mark <strong>${trainingName}</strong> as completed.</p>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Warning:</strong> This action cannot be undone. Once completed:
                                <ul class="mb-0 mt-2">
                                    <li>No more attendees can join</li>
                                    <li>No more sessions can be added</li>
                                    <li>Certificates can be generated (after feedback)</li>
                                </ul>
                            </div>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check"></i> Complete Training',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Completing...',
                            text: 'Please wait while we process your request',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        $.ajax({
                            url: '<?= site_url('trainings/complete_training') ?>',
                            type: 'POST',
                            data: { training_id: trainingId },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: response.message || 'Training has been marked as completed!',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to complete training'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred while completing the training'
                                });
                            }
                        });
                    }
                });
            });
            
            // Attend session button - show modal for code and photo
            $(document).on('click', '.attend-session-btn', function() {
                const sessionId = $(this).data('session-id');
                const trainingId = $(this).data('training-id');
                
                Swal.fire({
                    title: '<i class="fas fa-user-check"></i> Mark Attendance',
                    html: `
                        <div class="text-start">
                            <div class="mb-3">
                                <label for="session-code-input" class="form-label fw-bold">Session Code:</label>
                                <input type="text" 
                                       id="session-code-input" 
                                       class="form-control" 
                                       placeholder="Enter 8-character session code"
                                       maxlength="8"
                                       style="text-transform: uppercase; letter-spacing: 2px; font-weight: bold;">
                                <small class="text-muted">Ask your facilitator for the session code</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="attendance-photo-input" class="form-label fw-bold">Proof of Attendance:</label>
                                <input type="file" 
                                       id="attendance-photo-input" 
                                       class="form-control" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <small class="text-muted">Upload a photo as proof (JPG/PNG, max 5MB)</small>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check"></i> Submit Attendance',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                    width: '600px',
                    preConfirm: () => {
                        const code = document.getElementById('session-code-input').value.trim().toUpperCase();
                        const photoInput = document.getElementById('attendance-photo-input');
                        
                        if (!code) {
                            Swal.showValidationMessage('Please enter the session code');
                            return false;
                        }
                        
                        if (code.length !== 8) {
                            Swal.showValidationMessage('Session code must be 8 characters');
                            return false;
                        }
                        
                        if (!photoInput.files || photoInput.files.length === 0) {
                            Swal.showValidationMessage('Please upload a photo as proof of attendance');
                            return false;
                        }
                        
                        const file = photoInput.files[0];
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            Swal.showValidationMessage('Only JPG and PNG images are allowed');
                            return false;
                        }
                        
                        if (file.size > 5 * 1024 * 1024) {
                            Swal.showValidationMessage('Photo size must be less than 5MB');
                            return false;
                        }
                        
                        return {
                            session_code: code,
                            photo: file
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { session_code, photo } = result.value;
                        
                        // Show loading
                        Swal.fire({
                            title: 'Submitting...',
                            text: 'Please wait while we verify your attendance',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Create FormData for submission
                        const formData = new FormData();
                        formData.append('session_id', sessionId);
                        formData.append('training_id', trainingId);
                        formData.append('session_code', session_code);
                        formData.append('photo', photo);
                        
                        // Submit via AJAX
                        $.ajax({
                            url: '<?= site_url('trainings/submit_attendance') ?>',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Attendance Recorded!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to submit attendance'
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    
</body>
</html>
