<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <title><?= MY_APP_NAME; ?> - Session Attendees</title>
    
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
                            <i class="fas fa-users"></i> Session Attendees
                        </h1>
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="<?= site_url('trainings/sessions/' . $training['id_training']) ?>">Manage Sessions</a></li>
                            <li class="breadcrumb-item active">Session Attendees</li>
                        </ol>
                    </div>
                    <div class="col-xl-4">
                        <div class="text-end mt-4">
                            <ul class="page_title_button" style="list-style: none; margin: 0;">
                                <li>
                                    <a href="<?= site_url('trainings/sessions/' . $training['id_training']) ?>" class="btn btn-light">
                                        <i class="fas fa-arrow-circle-left"></i>
                                        <div class="text-muted">Back to Manage Sessions</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?= $this->include('layout/messages') ?>
                
                <!-- Two Column Layout -->
                <div class="row">
                    <!-- Left Column: Training & Session Details -->
                    <div class="col-lg-4 mb-4">
                        <!-- Training Info Card -->
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 0.375rem;">
                            <div class="card-header" style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0; padding: 1.5rem;">
                                <h5 class="mb-0" style="color: #333; font-weight: 600;">
                                    <i class="fas fa-graduation-cap" style="color: #198754;"></i> Training Information
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
                                            <span class="badge bg-<?= esc($training['status_name'] ?? 'secondary') ?>" style="padding: 0.5rem 1rem; border-radius: 0.375rem;">
                                                <?= esc($training['status_name'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Date From:</td>
                                        <td style="padding: 0.5rem 0; color: #333;"><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                    </tr>
                                    <?php if (!empty($training['training_facilitator'])): ?>
                                    <tr>
                                        <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Facilitator:</td>
                                        <td style="padding: 0.5rem 0; color: #333;"><?= esc($training['training_facilitator']) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($training['training_venue'])): ?>
                                    <tr>
                                        <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Venue:</td>
                                        <td style="padding: 0.5rem 0; color: #333;"><?= esc($training['training_venue']) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($training['training_hours'])): ?>
                                    <tr>
                                        <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Training Hours:</td>
                                        <td style="padding: 0.5rem 0; color: #333;"><?= esc($training['training_hours']) ?> hours</td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Session Details Card -->
                        <div class="card shadow-sm border-0" style="border-radius: 0.375rem;">
                            <div class="card-header" style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0; padding: 1.5rem;">
                                <h5 class="mb-0" style="color: #333; font-weight: 600;">
                                    <i class="fas fa-calendar-alt" style="color: #198754;"></i> Session Details
                                </h5>
                            </div>
                            <div class="card-body" style="padding: 2rem;">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69; width: 40%;">Session Date:</td>
                                        <td style="padding: 0.5rem 0; color: #333;"><?= date('M d, Y (l)', strtotime($session['session_date'])) ?></td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Time:</td>
                                        <td style="padding: 0.5rem 0; color: #333;">
                                            <?= date('g:i A', strtotime($session['session_start_time'])) ?> - 
                                            <?= $session['session_end_time'] ? date('g:i A', strtotime($session['session_end_time'])) : 'Ongoing' ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0.5rem 0; font-weight: 600; color: #5a5c69;">Session Code:</td>
                                        <td style="padding: 0.5rem 0;">
                                            <span class="badge" style="background-color: #198754; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 1rem;">
                                                <?= esc($session['session_code'] ?? 'N/A') ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Attendees List -->
                    <div class="col-lg-8 mb-4">
                        <div class="card shadow-sm border-0" style="border-radius: 0.375rem;">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #f8f9fc; border-bottom: 2px solid #e3e6f0; padding: 1.5rem;">
                                <h5 class="mb-0" style="color: #333; font-weight: 600;">
                                    <i class="fas fa-users" style="color: #198754;"></i> Attendees List
                                </h5>
                                <span class="badge" style="background-color: #198754; padding: 0.5rem 1rem; border-radius: 0.375rem;"><?= count($attendees) ?> Attendee(s)</span>
                            </div>
                            <div class="card-body" style="padding: 2rem;">
            <?php if (count($attendees) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="attendeesTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>User ID</th>
                                <th>Attendance Time</th>
                                <th>Status</th>
                                <th>Proof</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendees as $index => $attendee): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= esc($attendee['full_name'] ?? 'N/A') ?></strong></td>
                                    <td><?= esc($attendee['userid'] ?? $attendee['user_id'] ?? 'N/A') ?></td>
                                    <td>
                                        <?php if ($attendee['attendance_time']): ?>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-clock"></i> 
                                                <?= date('g:i A', strtotime($attendee['attendance_time'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!$attendee['has_attendance']): ?>
                                            <span class="badge bg-secondary"><i class="fas fa-times-circle"></i> Not Attended</span>
                                        <?php elseif ($attendee['is_verified'] == 1): ?>
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Pending Verification</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($attendee['attendance_file']): ?>
                                            <button class="btn btn-sm btn-info view-photo-btn" 
                                                    data-file="<?= esc($attendee['attendance_file']) ?>"
                                                    data-name="<?= esc($attendee['full_name'] ?? 'Attendee') ?>">
                                                <i class="fas fa-image"></i> View Photo
                                            </button>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">No Photo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($attendee['has_attendance']): ?>
                                            <?php if ($attendee['is_verified'] == 1): ?>
                                                <button class="btn btn-sm btn-success" disabled>
                                                    <i class="fas fa-check-circle"></i> Verified
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-success verify-attendance" 
                                                        data-attendance-id="<?= $attendee['attendance_id'] ?>">
                                                    <i class="fas fa-check-double"></i> Verify
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary" disabled>No Action</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-user-slash fa-3x mb-3"></i>
                    <h5>No attendees enrolled in this training</h5>
                    <p class="mb-0">There are no attendees registered for this training yet.</p>
                </div>
            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable if available
    if ($.fn.DataTable) {
        $('#attendeesTable').DataTable({
            "pageLength": 25,
            "order": [[0, 'asc']],
            "columnDefs": [
                { "orderable": false, "targets": [5, 6] }
            ]
        });
    }
    
    // View photo button
    $(document).on('click', '.view-photo-btn', function(e) {
        e.preventDefault();
        const file = $(this).data('file');
        const name = $(this).data('name');
        
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
    
    // Verify attendance
    $(document).on('click', '.verify-attendance', function() {
        const attendanceId = $(this).data('attendance-id');
        const row = $(this).closest('tr');
        const attendeeName = row.find('td:eq(1)').text().trim();
        const userId = row.find('td:eq(2)').text().trim();
        const attendanceTime = row.find('td:eq(3)').text().trim();
        const attendanceFile = row.find('.view-photo-btn').data('file') || null;
        
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
                        </p>
                    </div>
                    
                    <p class="text-muted small mt-2">
                        <i class="fas fa-clock"></i> Verification will be recorded with current timestamp
                    </p>
                </div>
            `,
            icon: 'question',
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
                                title: 'Success!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message || 'Failed to verify attendance'
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
});
</script>

        </main>
        
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; CLSU <?= date('Y') ?></div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
