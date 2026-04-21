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
    // Use public navbar (no sidebar) for non-logged-in users
    echo $this->include('layout/navbar_public');
    ?>

    <div id="layoutSidenav_content">
            <main>
            <div class="container-fluid px-4">
                
                <!-- Page Header -->
                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin-top: 1.5rem; margin-bottom: 1rem;">
                    <div class="col-xl-8">
                        <h1 class="mt-2 mb-3">
                            <?= esc($training['training_name']) ?>
                        </h1>
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Available Trainings</a></li>
                            <li class="breadcrumb-item active">
                                Training Management System
                            </li>
                        </ol>
                    </div>
                    <div class="col-xl-4">
                        <div class="text-end mt-4">
                            <ul class="page_title_button" style="list-style: none; margin: 0;">
                                <li>
                                    <a href="<?= site_url('/') ?>" class="btn btn-light">
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
                    <!-- Left Column: Description, Learnings & Training Info Card (Larger) -->
                    <div class="col-lg-8">
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
                    
                    <!-- Right Column: Status & Information Card (Smaller) -->
                    <div class="col-lg-4 mb-4">
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
                                        <td class="info-label">Certificate:</td>
                                        <td class="info-value">
                                            <?php if (!empty($training['training_certificate_file']) || !empty($training['certificate_file'])): ?>
                                                <a href="<?= base_url('uploads/trainings/certificates/' . esc($training['training_certificate_file'] ?? $training['certificate_file'])) ?>" target="_blank" class="btn btn-sm btn-success">
                                                    <i class="fas fa-eye"></i> View Certificate
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-secondary badge-custom">Not Available</span>
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
                                        
                                        <?php if(session()->get('logged_in')): ?>
                                            <?php if(isset($training['is_enrolled']) && $training['is_enrolled']): ?>
                                                <?php 
                                                $status_name = strtolower($training['status_name'] ?? '');
                                                $is_ongoing = (isset($training['is_ongoing']) && $training['is_ongoing']);
                                                $is_closed = ($status_name === 'closed');
                                                $is_completed = ($status_name === 'completed');
                                                $cannot_cancel = $is_ongoing || $is_closed || $is_completed;
                                                ?>
                                                <?php if($cannot_cancel): ?>
                                                    <button class="btn btn-secondary" disabled title="Cannot cancel registration for <?= ucfirst($status_name) ?> training">
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
                                                $status_name = strtolower($training['status_name'] ?? '');
                                                $is_ongoing_or_completed = in_array($status_name, ['ongoing', 'completed']);
                                                
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
                                        <?php else: ?>
                                            <a href="<?= site_url('/login') ?>" class="btn <?= isset($training['is_ongoing']) && $training['is_ongoing'] ? 'btn-secondary disabled' : 'btn-success' ?>" <?= isset($training['is_ongoing']) && $training['is_ongoing'] ? 'disabled tabindex="-1" aria-disabled="true"' : '' ?>>
                                                <i class="fas fa-sign-in-alt"></i> Login to Join
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <?= $this->include('layout/footer') ?>
            
        </main>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= assets('js/scripts.js') ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle enrollment (Join/Cancel)
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
                                // Since the controller redirects, we'll reload the page
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Successfully ' + (isEnrolled ? 'cancelled registration' : 'registered') + ' for the training!',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
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
        });
    </script>
    
</body>
</html>
