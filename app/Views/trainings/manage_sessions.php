<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <title><?= MY_APP_NAME; ?> - Manage Sessions</title>
    
    <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
    <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
    <link href="<?= css('styles.css') ?>" rel="stylesheet" />
    <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Remove sidebar - full width content */
        #layoutSidenav_content {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        /* Fix header overlap - add top margin */
        .container-fluid.px-4 {
            padding-top: 60px !important;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    
    <?= $this->include('layout/navbar_admin') ?>
    
    <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    
                    <!-- Page Header with White Background -->
                    <div class="row" style="background-color: #FFF; border-radius: 0.375rem; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); margin-top: 1.5rem; margin-bottom: 1rem;">
                        <div class="col-xl-8">
                            <h1 class="mt-2 mb-3">
                                <i class="fas fa-calendar-alt"></i> Manage Training Sessions
                            </h1>
                            <ol class="breadcrumb mb-3">
                                <li class="breadcrumb-item"><a href="<?= site_url('trainings') ?>">All Trainings</a></li>
                                <li class="breadcrumb-item active"><?= esc($training['training_name']) ?></li>
                            </ol>
                        </div>
                        <div class="col-xl-4">
                            <div class="text-end mt-4">
                                <ul class="page_title_button" style="list-style: none; margin: 0;">
                                    <li>
                                        <button class="btn btn-light add-session-btn" data-training-id="<?= $training['id_training'] ?>">
                                            <i class="fas fa-plus-circle"></i>
                                            <div class="text-muted">Add Session</div>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?= $this->include('layout/messages') ?>
                    
                    <!-- Two Column Layout -->
                    <div class="row">
                        <!-- Left Column: Training Information -->
                        <div class="col-lg-4 mb-4">
                            <div class="card shadow-sm border-0" style="border-radius: 0.375rem;">
                                <div class="card-header" style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0; padding: 1.5rem;">
                                    <h5 class="mb-0" style="color: #333; font-weight: 600;">
                                        <i class="fas fa-info-circle" style="color: #198754;"></i> Training Information
                                    </h5>
                                </div>
                                <div class="card-body" style="padding: 2rem;">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69; width: 40%;">Training Name:</td>
                                            <td style="padding: 0.5rem 0; color: #333;"><?= esc($training['training_name']) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Status:</td>
                                            <td style="padding: 0.5rem 0;">
                                                <span class="badge bg-<?= $training['status_name'] === 'Ongoing' ? 'success' : 'secondary' ?>" style="padding: 0.5rem 1rem; border-radius: 0.375rem;">
                                                    <?= esc($training['status_name']) ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Date From:</td>
                                            <td style="padding: 0.5rem 0; color: #333;"><?= !empty($training['training_datefrom']) ? date('M j, Y', strtotime($training['training_datefrom'])) : 'TBA' ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Facilitator:</td>
                                            <td style="padding: 0.5rem 0; color: #333;"><?= esc($training['training_facilitator']) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Venue:</td>
                                            <td style="padding: 0.5rem 0; color: #333;"><?= esc($training['training_venue']) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Training Hours:</td>
                                            <td style="padding: 0.5rem 0; color: #333;"><?= esc($training['training_hours']) ?> hours</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Sessions Table -->
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow-sm border-0" style="border-radius: 0.375rem;">
                                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0; padding: 1.5rem;">
                                    <h5 class="mb-0" style="color: #333; font-weight: 600;">
                                        <i class="fas fa-list" style="color: #198754;"></i> Training Sessions
                                    </h5>
                                    <span class="badge" style="background-color: #198754; padding: 0.5rem 1rem; border-radius: 0.375rem;"><?= count($sessions) ?> Session(s)</span>
                                </div>
                                <div class="card-body" style="padding: 2rem;">
                            <?php if(count($sessions) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="sessionsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Session Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Duration</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($sessions as $index => $session): ?>
                                                <?php
                                                    // Session is active if it has been started (has started_at) but not ended (no ended_at)
                                                    $is_active = !empty($session['started_at']) && empty($session['ended_at']);
                                                    $status_badge = $is_active ? 'bg-success' : 'bg-secondary';
                                                    $status_text = $is_active ? 'Active' : 'Closed';
                                                    
                                                    // Calculate duration
                                                    $duration = '';
                                                    if (!empty($session['session_start_time']) && !empty($session['session_end_time'])) {
                                                        $start = new DateTime($session['session_start_time']);
                                                        $end = new DateTime($session['session_end_time']);
                                                        $diff = $start->diff($end);
                                                        $hours = $diff->h;
                                                        $minutes = $diff->i;
                                                        if ($hours > 0) {
                                                            $duration .= $hours . 'h ';
                                                        }
                                                        if ($minutes > 0) {
                                                            $duration .= $minutes . 'm';
                                                        }
                                                        if (empty($duration)) {
                                                            $duration = '< 1m';
                                                        }
                                                    } else {
                                                        $duration = '-';
                                                    }
                                                ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td>
                                                        <strong><?= date('M j, Y', strtotime($session['session_date'])) ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?= date('l', strtotime($session['session_date'])) ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-clock"></i> <?= date('g:i A', strtotime($session['session_start_time'])) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if(!empty($session['session_end_time'])): ?>
                                                            <span class="badge bg-danger">
                                                                <i class="fas fa-clock"></i> <?= date('g:i A', strtotime($session['session_end_time'])) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="fas fa-spinner fa-spin"></i> In Progress
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $duration ?></td>
                                                    <td>
                                                        <span class="badge <?= $status_badge ?>"><?= $status_text ?></span>
                                                    </td>
                                                    <td>
                                                        <!-- View Session Details Button -->
                                                        <button class="btn btn-sm btn-primary view-session-details" 
                                                                data-session-id="<?= $session['id'] ?>"
                                                                data-training-id="<?= $training['id_training'] ?>"
                                                                data-date="<?= date('M j, Y', strtotime($session['session_date'])) ?>"
                                                                data-start="<?= !empty($session['session_start_time']) ? date('g:i A', strtotime($session['session_start_time'])) : 'Not started' ?>"
                                                                data-end="<?= !empty($session['session_end_time']) ? date('g:i A', strtotime($session['session_end_time'])) : 'Not ended' ?>"
                                                                data-code="<?= $session['session_code'] ?? 'N/A' ?>"
                                                                data-status="<?= $status_text ?>"
                                                                data-started-at="<?= $session['started_at'] ?? '' ?>"
                                                                data-ended-at="<?= $session['ended_at'] ?? '' ?>"
                                                                title="View Session Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                        
                                                        <!-- Toggle Start/End Session Button -->
                                                        <?php if(!empty($session['ended_at'])): ?>
                                                            <!-- Show Completed button if session is already ended -->
                                                            <button class="btn btn-sm btn-secondary" disabled title="Session Completed">
                                                                <i class="fas fa-check-circle"></i> Completed
                                                            </button>
                                                        <?php elseif(empty($session['started_at'])): ?>
                                                            <!-- Show Start button if not started yet -->
                                                            <?php if(isset($has_active_session) && $has_active_session && $active_session_id != $session['id']): ?>
                                                                <!-- Disable start button if another session is active -->
                                                                <button class="btn btn-sm btn-secondary" disabled title="Another session is currently active. Please end it first.">
                                                                    <i class="fas fa-ban"></i> Start
                                                                </button>
                                                            <?php else: ?>
                                                                <button class="btn btn-sm btn-success start-specific-session" 
                                                                        data-session-id="<?= $session['id'] ?>"
                                                                        data-training-id="<?= $training['id_training'] ?>"
                                                                        title="Start This Session">
                                                                    <i class="fas fa-play"></i> Start
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <!-- Show End button if session is active (started but not ended) -->
                                                            <button class="btn btn-sm btn-danger end-specific-session" 
                                                                    data-session-id="<?= $session['id'] ?>"
                                                                    data-training-id="<?= $training['id_training'] ?>"
                                                                    title="End This Session">
                                                                <i class="fas fa-stop"></i> End
                                                            </button>
                                                        <?php endif; ?>
                                                        
                                                        <!-- View Attendees Button -->
                                                        <a href="<?= site_url('trainings/session_attendees/' . $training['id_training'] . '/' . $session['id']) ?>" 
                                                           class="btn btn-sm btn-info"
                                                           title="View Attendees List">
                                                            <i class="fas fa-users"></i> Attendees
                                                        </a>
                                                        
                                                        <?php if(empty($session['end_time']) || strtolower($training['status_name']) !== 'completed'): ?>
                                                            <button class="btn btn-sm btn-warning edit-session" 
                                                                    data-session-id="<?= $session['id'] ?>"
                                                                    data-date="<?= $session['session_date'] ?>"
                                                                    data-start="<?= $session['session_start_time'] ?>"
                                                                    data-end="<?= $session['session_end_time'] ?>"
                                                                    title="Edit Session">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                            
                                                            <button class="btn btn-sm btn-danger delete-session" 
                                                                    data-session-id="<?= $session['id'] ?>"
                                                                    title="Delete Session">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                    <h5>No sessions added yet</h5>
                                    <p class="text-muted">Click "Add Session" to create your first session.</p>
                                    <?php if($training['status_name'] === 'Ongoing'): ?>
                                        <button class="btn btn-primary mt-2 add-session-btn" data-training-id="<?= $training['id_training'] ?>">
                                            <i class="fas fa-plus-circle"></i> Add Your First Session
                                        </button>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="align-items-center justify-content-between small">
                        <div class="text-muted" style="text-align: right;">&copy; <?= date('Y') ?> CLSU. All rights reserved. 
                            <br>Powered by <span class="text-success">Management Information System Office (CLSU-MISO)</span>.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    
    <!-- Add Single Session Modal -->
    <div class="modal fade" id="addSessionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add Training Session</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="add-training-id">
                    <div class="mb-3">
                        <label class="form-label">Session Date</label>
                        <input type="date" class="form-control" id="add-session-date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="add-start-time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" id="add-end-time" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> This session will be available for attendance tracking.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-single-session">
                        <i class="fas fa-save"></i> Save Session
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Session Modal -->
    <div class="modal fade" id="editSessionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Training Session</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-session-id">
                    <div class="mb-3">
                        <label class="form-label">Session Date</label>
                        <input type="date" class="form-control" id="edit-session-date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" id="edit-start-time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" id="edit-end-time">
                        <small class="text-muted">Leave empty if session is still ongoing</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="update-session">
                        <i class="fas fa-save"></i> Update Session
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Multiple Sessions Modal -->
    <div class="modal fade" id="addMultipleSessionsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus"></i> Add Multiple Sessions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal-training-id">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Add multiple sessions for this training. Click "Add Row" to add more sessions.
                    </div>
                    
                    <div id="sessions-container">
                        <div class="session-row mb-3 p-3 border rounded">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small">Date</label>
                                    <input type="date" class="form-control form-control-sm session-date" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Start Time</label>
                                    <input type="time" class="form-control form-control-sm start-time" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">End Time</label>
                                    <input type="time" class="form-control form-control-sm end-time" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-danger remove-session-row w-100">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-session-row">
                        <i class="fas fa-plus"></i> Add Another Row
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-multiple-sessions">
                        <i class="fas fa-save"></i> Save All Sessions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Attendance Modal -->
    <div class="modal fade" id="viewAttendanceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-users"></i> Session Attendance</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="attendance-content">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading attendance data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Session Code Display Modal -->
    <div class="modal fade" id="sessionCodeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle"></i> Session Started!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-3">Share this code with attendees:</p>
                    <div class="bg-light p-4 rounded mb-3">
                        <h2 class="text-success fw-bold" id="session-code-display" style="letter-spacing: 5px; font-size: 2.5rem;"></h2>
                    </div>
                    <p class="text-muted small">Attendees must enter this code and upload a photo as proof of attendance.</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Session:</strong> <span id="session-date-display"></span><br>
                        <strong>Started at:</strong> <span id="session-time-display"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="copySessionCode()">
                        <i class="fas fa-copy"></i> Copy Code
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Session Details Modal -->
    <div class="modal fade" id="viewSessionDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-info-circle"></i> Session Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Session Date</label>
                                <p class="mb-0" id="vsd-date"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Scheduled Start Time</label>
                                <p class="mb-0" id="vsd-start"></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Scheduled End Time</label>
                                <p class="mb-0" id="vsd-end"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Status</label>
                                <p class="mb-0"><span class="badge bg-secondary" id="vsd-status"></span></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Session Code</label>
                                <p class="mb-0">
                                    <code class="fs-5" id="vsd-code"></code>
                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyVsdCode()" title="Copy Code">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Actual Started At</label>
                                <p class="mb-0" id="vsd-started-at">-</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold text-muted">Actual Ended At</label>
                                <p class="mb-0" id="vsd-ended-at">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
    <script src="<?= js('scripts.js'); ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Add single session button
            $('.add-session-btn').on('click', function() {
                const trainingId = $(this).data('training-id');
                $('#add-training-id').val(trainingId);
                $('#addSessionModal').modal('show');
            });
            
            // Save single session
            $('#save-single-session').on('click', function() {
                const trainingId = $('#add-training-id').val();
                const sessionDate = $('#add-session-date').val();
                const startTime = $('#add-start-time').val();
                const endTime = $('#add-end-time').val();
                
                if (!sessionDate || !startTime || !endTime) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please fill in all fields.'
                    });
                    return;
                }
                
                $.ajax({
                    url: '<?= site_url('trainings/save_single_session') ?>',
                    type: 'POST',
                    data: {
                        training_id: trainingId,
                        session_date: sessionDate,
                        start_time: startTime,
                        end_time: endTime
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000
                            }).then(() => {
                                // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                setTimeout(function() {
                                    window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                }, 500);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'An error occurred.'
                        });
                    }
                });
            });
            
            // Edit session button
            $(document).on('click', '.edit-session', function() {
                const sessionId = $(this).data('session-id');
                const date = $(this).data('date');
                const start = $(this).data('start');
                const end = $(this).data('end');
                
                $('#edit-session-id').val(sessionId);
                $('#edit-session-date').val(date);
                $('#edit-start-time').val(start.substring(0, 5));
                $('#edit-end-time').val(end ? end.substring(0, 5) : '');
                
                $('#editSessionModal').modal('show');
            });
            
            // Update session
            $('#update-session').on('click', function() {
                const sessionId = $('#edit-session-id').val();
                const sessionDate = $('#edit-session-date').val();
                const startTime = $('#edit-start-time').val();
                const endTime = $('#edit-end-time').val();
                
                if (!sessionDate || !startTime) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Date and start time are required.'
                    });
                    return;
                }
                
                $.ajax({
                    url: '<?= site_url('trainings/update_session') ?>',
                    type: 'POST',
                    data: {
                        session_id: sessionId,
                        session_date: sessionDate,
                        start_time: startTime,
                        end_time: endTime
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000
                            }).then(() => {
                                // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                setTimeout(function() {
                                    window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                }, 500);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'An error occurred.'
                        });
                    }
                });
            });
            
            // Delete session button
            $(document).on('click', '.delete-session', function() {
                const sessionId = $(this).data('session-id');
                
                Swal.fire({
                    title: 'Delete Session?',
                    text: 'Are you sure you want to delete this session? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/delete_session') ?>',
                            type: 'POST',
                            data: {
                                session_id: sessionId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message,
                                        timer: 2000
                                    }).then(() => {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Start session button
            $('.start-session-btn').on('click', function() {
                const trainingId = $(this).data('training-id');
                
                Swal.fire({
                    title: 'Start Session?',
                    html: '<p>This will record the current date and time as the session start.</p>',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, start session!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/start_session') ?>',
                            type: 'POST',
                            data: {
                                training_id: trainingId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Session Started!',
                                        text: response.message,
                                        timer: 2000
                                    }).then(() => {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Close session button (inline)
            $(document).on('click', '.close-session-inline', function() {
                const trainingId = $(this).data('training-id');
                
                Swal.fire({
                    title: 'Close Session?',
                    text: 'This will record the end time for the current session.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/close_session') ?>',
                            type: 'POST',
                            data: {
                                training_id: trainingId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Session Closed!',
                                        text: response.message,
                                        timer: 2000
                                    }).then(() => {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Close session button (quick action)
            $('.close-session-btn').on('click', function() {
                const trainingId = $(this).data('training-id');
                
                Swal.fire({
                    title: 'Close Current Session?',
                    html: '<p>This will find and close the most recent active session.</p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, close it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/close_session') ?>',
                            type: 'POST',
                            data: {
                                training_id: trainingId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Session Closed!',
                                        text: response.message,
                                        timer: 2000
                                    }).then(() => {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Add multiple sessions button
            $('.add-multiple-sessions-btn').on('click', function() {
                const trainingId = $(this).data('training-id');
                $('#modal-training-id').val(trainingId);
                $('#addMultipleSessionsModal').modal('show');
            });
            
            // Add session row
            $('#add-session-row').on('click', function() {
                const newRow = `
                    <div class="session-row mb-3 p-3 border rounded">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small">Date</label>
                                <input type="date" class="form-control form-control-sm session-date" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Start Time</label>
                                <input type="time" class="form-control form-control-sm start-time" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">End Time</label>
                                <input type="time" class="form-control form-control-sm end-time" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-danger remove-session-row w-100">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#sessions-container').append(newRow);
            });
            
            // Remove session row
            $(document).on('click', '.remove-session-row', function() {
                if ($('.session-row').length > 1) {
                    $(this).closest('.session-row').remove();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Remove',
                        text: 'You must have at least one session.'
                    });
                }
            });
            
            // Save multiple sessions
            $('#save-multiple-sessions').on('click', function() {
                const trainingId = $('#modal-training-id').val();
                const sessions = [];
                let hasError = false;
                
                $('.session-row').each(function() {
                    const date = $(this).find('.session-date').val();
                    const startTime = $(this).find('.start-time').val();
                    const endTime = $(this).find('.end-time').val();
                    
                    if (!date || !startTime || !endTime) {
                        hasError = true;
                        return false;
                    }
                    
                    sessions.push({
                        session_date: date,
                        start_time: startTime,
                        end_time: endTime
                    });
                });
                
                if (hasError) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please fill in all fields for each session.'
                    });
                    return;
                }
                
                $.ajax({
                    url: '<?= site_url('trainings/save_multiple_sessions') ?>',
                    type: 'POST',
                    data: {
                        training_id: trainingId,
                        sessions: JSON.stringify(sessions)
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message + ' Total sessions saved: ' + response.total_saved,
                                timer: 2000
                            }).then(() => {
                                // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                setTimeout(function() {
                                    window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                }, 500);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'An error occurred.'
                        });
                    }
                });
            });
                        // View attendance button
            $(document).on('click', '.view-attendance', function() {
                const sessionId = $(this).data('session-id');
                const trainingId = $(this).data('training-id');
                
                $('#viewAttendanceModal').modal('show');
                
                $.ajax({
                    url: '<?= site_url('trainings/get_session_attendance') ?>',
                    type: 'POST',
                    data: {
                        session_id: sessionId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const session = response.session;
                            const attendees = response.attendees;
                            
                            let html = `
                                <div class="mb-3">
                                    <h6><i class="fas fa-calendar"></i> Session: ${session.session_date}</h6>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-clock"></i> ${formatTime(session.start_time)} - ${formatTime(session.end_time || 'N/A')}
                                    </p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Participant</th>
                                                <th>Check-in Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;
                            
                            if (attendees.length === 0) {
                                html += `
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-user-slash fa-2x mb-2"></i>
                                            <p>No attendance records yet</p>
                                        </td>
                                    </tr>
                                `;
                            } else {
                                attendees.forEach((attendee, index) => {
                                    const isVerified = attendee.is_verified == 1;
                                    const verifiedBadge = isVerified
                                        ? '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Verified</span>'
                                        : '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Unverified</span>';
                                    
                                    html += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>
                                                <strong>${escapeHtml(attendee.full_name || attendee.username || 'Unknown')}</strong>
                                                <br><small class="text-muted">${escapeHtml(attendee.userid || '')}</small>
                                            </td>
                                            <td>
                                                ${attendee.attendance_time 
                                                    ? '<span class="badge bg-primary"><i class="fas fa-clock"></i> ' + formatTime(attendee.attendance_time) + '</span>' 
                                                    : '<span class="badge bg-secondary">-</span>'}
                                            </td>
                                            <td>
                                                ${verifiedBadge}
                                            </td>
                                            <td>
                                                ${attendee.attendance_file 
                                                    ? `<a href="#" class="btn btn-sm btn-info view-photo" data-file="${escapeHtml(attendee.attendance_file)}" data-name="${escapeHtml(attendee.full_name || 'Attendee')}">
                                                           <i class="fas fa-image"></i> View Photo
                                                       </a>` 
                                                    : '<span class="badge bg-secondary">No Photo</span>'}
                                                <hr class="my-1">
                                                ${isVerified 
                                                    ? `<button class="btn btn-sm btn-outline-danger unverify-attendance" data-attendance-id="${attendee.attendance_id}">
                                                           <i class="fas fa-undo"></i> Unverify
                                                       </button>`
                                                    : `<button class="btn btn-sm btn-success verify-attendance" data-attendance-id="${attendee.attendance_id}">
                                                           <i class="fas fa-check"></i> Verify
                                                       </button>`}
                                            </td>
                                        </tr>
                                    `;
                                });
                            }
                            
                            html += `
                                        </tbody>
                                    </table>
                                </div>
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Total Attendees:</strong> ${attendees.length}
                                </div>
                            `;
                            
                            $('#attendance-content').html(html);
                        } else {
                            $('#attendance-content').html(`
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    ${response.message || 'Failed to load attendance'}
                                </div>
                            `);
                        }
                    },
                    error: function(xhr) {
                        $('#attendance-content').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                ${xhr.responseJSON?.message || 'An error occurred'}
                            </div>
                        `);
                    }
                });
            });
            
            // Helper function to format time
            function formatTime(timeString) {
                if (!timeString) return 'N/A';
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour % 12 || 12;
                return `${displayHour}:${minutes} ${ampm}`;
            }
            
            // Helper function to escape HTML
            function escapeHtml(text) {
                if (!text) return '';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }
            
            // Copy session code to clipboard
            function copySessionCode() {
                const code = $('#session-code-display').text();
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Session code copied to clipboard',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }
            
            // Copy view session details code to clipboard
            function copyVsdCode() {
                const code = $('#vsd-code').text();
                if (code === 'N/A' || !code) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Code',
                        text: 'Session code has not been generated yet.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                navigator.clipboard.writeText(code).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Session code copied to clipboard',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }
            
            // OLD CODE - View Session Attendees button (replaced with full page)
            // This code is kept for reference but no longer used
            /*
            $(document).on('click', '.view-session-attendees', function() {
                const sessionId = $(this).data('session-id');
                const trainingId = $(this).data('training-id');
                
                // Show loading modal
                Swal.fire({
                    title: 'Loading Attendees...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Fetch attendees list
                $.ajax({
                    url: '<?= site_url('trainings/get_session_attendance') ?>',
                    type: 'POST',
                    data: {
                        session_id: sessionId
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        
                        if (response.success) {
                            const session = response.session;
                            const attendees = response.attendees || [];
                            
                            // Build attendees table HTML
                            let attendeesHtml = `
                                <div class="text-start">
                                    <h6 class="mb-3"><i class="fas fa-calendar-alt"></i> Session: ${session.session_date}</h6>
                                    <p class="mb-2"><strong>Time:</strong> ${session.session_start_time} - ${session.session_end_time || 'Ongoing'}</p>
                                    <p class="mb-3"><strong>Code:</strong> <span class="badge bg-primary">${session.session_code || 'N/A'}</span></p>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>User ID</th>
                                                    <th>Attendance Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                            `;
                            
                            if (attendees.length > 0) {
                                attendees.forEach((att, index) => {
                                    let statusBadge = '';
                                    let actionButton = '';
                                    
                                    if (!att.has_attendance) {
                                        statusBadge = '<span class="badge bg-secondary"><i class="fas fa-times-circle"></i> Not Attended</span>';
                                        actionButton = '<button class="btn btn-sm btn-outline-secondary" disabled>No Action</button>';
                                    } else if (att.is_verified == 1) {
                                        statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Verified</span>';
                                        actionButton = '<button class="btn btn-sm btn-outline-success verify-btn" disabled><i class="fas fa-check"></i> Verified</button>';
                                    } else {
                                        statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending Verification</span>';
                                        actionButton = `<button class="btn btn-sm btn-success verify-attendance-btn" data-attendance-id="${att.attendance_id}" data-user-id="${att.user_id}"><i class="fas fa-check-double"></i> Verify</button>`;
                                    }
                                    
                                    const attendanceTime = att.attendance_time 
                                        ? new Date('2000-01-01 ' + att.attendance_time).toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'}) 
                                        : 'N/A';
                                    
                                    attendeesHtml += `
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td><strong>${att.full_name || 'N/A'}</strong></td>
                                            <td>${att.userid || att.user_id || 'N/A'}</td>
                                            <td>${attendanceTime}</td>
                                            <td>${statusBadge}</td>
                                            <td>
                                                ${actionButton}
                                                <input type="hidden" class="attendance-file-data" value="${att.attendance_file || ''}">
                                            </td>
                                        </tr>
                                    `;
                                });
                            } else {
                                attendeesHtml += `
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-info-circle"></i> No attendees enrolled in this training
                                        </td>
                                    </tr>
                                `;
                            }
                            
                            attendeesHtml += `
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-3 d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <span class="badge bg-success">${attendees.filter(a => a.is_verified == 1).length}</span> Verified &nbsp;
                                            <span class="badge bg-warning text-dark">${attendees.filter(a => a.has_attendance && a.is_verified != 1).length}</span> Pending &nbsp;
                                            <span class="badge bg-secondary">${attendees.filter(a => !a.has_attendance).length}</span> Not Attended
                                        </small>
                                        <small class="text-muted">Total: ${attendees.length} attendee(s)</small>
                                    </div>
                                </div>
                            `;
                            
                            // Show modal with attendees list
                            Swal.fire({
                                title: '<i class="fas fa-users"></i> Session Attendees',
                                html: attendeesHtml,
                                width: '800px',
                                showConfirmButton: true,
                                confirmButtonText: '<i class="fas fa-times"></i> Close',
                                confirmButtonColor: '#6c757d'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Failed to load attendees'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'An error occurred while loading attendees'
                        });
                    }
                });
            });
            */
            
            // Verify attendance button (delegated event for dynamically created buttons)
            $(document).on('click', '.verify-attendance-btn', function() {
                const attendanceId = $(this).data('attendance-id');
                const userId = $(this).data('user-id');
                            
                // Find the attendee data from the current modal
                const row = $(this).closest('tr');
                const attendeeName = row.find('td:eq(1)').text().trim();
                const attendanceTime = row.find('td:eq(3)').text().trim();
                const attendanceFile = row.find('.attendance-file-data').val();
                            
                // Build photo HTML if file exists
                let photoHtml = '';
                if (attendanceFile) {
                    photoHtml = `
                        <div class="mb-3">
                            <label class="form-label fw-bold"><i class="fas fa-image"></i> Proof of Attendance:</label>
                            <div class="text-center p-3 bg-light rounded">
                                <img src="<?= base_url('uploads/trainings/attendance/') ?>${attendanceFile}" 
                                     alt="Attendance Proof" 
                                     class="img-fluid rounded" 
                                     style="max-height: 300px; max-width: 100%;"
                                     onerror="this.parentElement.innerHTML='<p class=text-muted><i class=fas fa-exclamation-triangle></i> Image not found</p>';">
                            </div>
                            <small class="text-muted d-block mt-2">Photo uploaded by attendee as proof</small>
                        </div>
                    `;
                } else {
                    photoHtml = `
                        <div class="mb-3">
                            <div class="alert alert-secondary">
                                <i class="fas fa-info-circle"></i> No photo uploaded for this attendance record
                            </div>
                        </div>
                    `;
                }
                            
                Swal.fire({
                    title: '<i class="fas fa-check-double"></i> Verify Attendance',
                    html: `
                        <div class="text-start">
                            <div class="alert alert-info mb-3">
                                <h6 class="mb-2"><i class="fas fa-user"></i> Attendee Details</h6>
                                <p class="mb-1"><strong>Name:</strong> ${attendeeName}</p>
                                <p class="mb-1"><strong>User ID:</strong> ${userId}</p>
                                <p class="mb-1"><strong>Attendance Time:</strong> ${attendanceTime}</p>
                            </div>
                                        
                            ${photoHtml}
                                        
                            <div class="alert alert-warning">
                                <h6 class="mb-2"><i class="fas fa-info-circle"></i> Verification Note</h6>
                                <p class="mb-0 small">
                                    By verifying this attendance, you confirm that this attendee was present at the session.
                                    This action cannot be undone.
                                </p>
                            </div>
                                        
                            <p class="text-muted small mt-2">
                                <i class="fas fa-clock"></i> Verification will be recorded with current timestamp
                            </p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-check"></i> Yes, Verify!',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel',
                    width: '700px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/verify_attendance') ?>',
                            type: 'POST',
                            data: {
                                attendance_id: attendanceId,
                                is_verified: 1
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Verified!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Close current modal and reopen to refresh data
                                        Swal.close();
                                        // Trigger click on the attendees button again to refresh
                                        $('.view-session-attendees[data-session-id]').first().trigger('click');
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred'
                                });
                            }
                        });
                    }
                });
            });
                        
            // View Session Details button
            $(document).on('click', '.view-session-details', function() {
                const date = $(this).data('date');
                const start = $(this).data('start');
                const end = $(this).data('end');
                const code = $(this).data('code');
                const status = $(this).data('status');
                const startedAt = $(this).data('started-at');
                const endedAt = $(this).data('ended-at');
                
                $('#vsd-date').text(date);
                $('#vsd-start').text(start);
                $('#vsd-end').text(end);
                $('#vsd-status').text(status);
                $('#vsd-code').text(code);
                
                // Format actual timestamps if they exist
                if (startedAt) {
                    const startDate = new Date(startedAt);
                    $('#vsd-started-at').text(startDate.toLocaleString());
                } else {
                    $('#vsd-started-at').text('-');
                }
                
                if (endedAt) {
                    const endDate = new Date(endedAt);
                    $('#vsd-ended-at').text(endDate.toLocaleString());
                } else {
                    $('#vsd-ended-at').text('-');
                }
                
                $('#viewSessionDetailsModal').modal('show');
            });
            
            // Start specific session button
            $(document).on('click', '.start-specific-session', function() {
                const sessionId = $(this).data('session-id');
                const trainingId = $(this).data('training-id');
                
                Swal.fire({
                    title: 'Start This Session?',
                    html: `
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <p class="mb-0">This will generate an attendance code, record start time, allow check-ins, and change status to "Ongoing".</p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, start session!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/start_specific_session') ?>',
                            type: 'POST',
                            data: {
                                session_id: sessionId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    // Display the generated code
                                    $('#session-code-display').text(response.session_code);
                                    $('#session-date-display').text(response.session_date);
                                    $('#session-time-display').text(response.start_time);
                                    
                                    $('#viewAttendanceModal').modal('hide');
                                    $('#sessionCodeModal').modal('show');
                                    
                                    // Reload page after modal is closed (use one() to prevent multiple bindings)
                                    $('#sessionCodeModal').one('hidden.bs.modal', function () {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // End specific session button
            $(document).on('click', '.end-specific-session', function() {
                const sessionId = $(this).data('session-id');
                const trainingId = $(this).data('training-id');
                
                Swal.fire({
                    title: 'End This Session?',
                    html: `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>This will:</p>
                            <ul class="text-start mb-0">
                                <li>Record the current time as end time</li>
                                <li>Close attendance for this session</li>
                                <li>Mark session as completed</li>
                            </ul>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, end session!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/end_specific_session') ?>',
                            type: 'POST',
                            data: {
                                session_id: sessionId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Session Ended!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Verify attendance button
            $(document).on('click', '.verify-attendance', function() {
                const attendanceId = $(this).data('attendance-id');
                
                Swal.fire({
                    title: 'Verify Attendance?',
                    text: 'Mark this attendance record as verified.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, verify it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/verify_attendance') ?>',
                            type: 'POST',
                            data: {
                                attendance_id: attendanceId,
                                is_verified: 1
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Verified!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Unverify attendance button
            $(document).on('click', '.unverify-attendance', function() {
                const attendanceId = $(this).data('attendance-id');
                
                Swal.fire({
                    title: 'Unverify Attendance?',
                    text: 'Remove verification status from this attendance record.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, unverify it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= site_url('trainings/verify_attendance') ?>',
                            type: 'POST',
                            data: {
                                attendance_id: attendanceId,
                                is_verified: 0
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Unverified!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Add small delay to ensure database commit, then reload with cache-busting timestamp
                                        setTimeout(function() {
                                            window.location.href = window.location.pathname + '?t=' + new Date().getTime();
                                        }, 500);
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred.'
                                });
                            }
                        });
                    }
                });
            });
            
            // View photo button
            $(document).on('click', '.view-photo', function(e) {
                e.preventDefault();
                const file = $(this).data('file');
                const name = $(this).data('name');
                
                // Build the correct URL to the attendance photo
                const photoUrl = '<?= base_url('uploads/trainings/attendance/') ?>' + file;
                
                Swal.fire({
                    title: `${name}'s Attendance Photo`,
                    imageUrl: photoUrl,
                    imageAlt: 'Attendance photo',
                    imageClass: 'img-fluid',
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 'auto',
                    padding: '20px'
                });
            });
        });
    </script>
</body>
</html>
