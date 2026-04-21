<?= $this->extend('layout/' . (session()->get('user_type_name') === 'Admin' ? 'dashboard_admin' : 'dashboard_employee')) ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mt-4">Browse All Trainings</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?= site_url('mytrainings') ?>">My Trainings</a></li>
                <li class="breadcrumb-item active">Browse All</li>
            </ol>
        </div>
    </div>

    <?= $this->include('layout/messages') ?>

    <!-- Statistics Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-0">Available Trainings</h5>
                    <p class="text-muted mb-0">Browse and join trainings that interest you</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary" style="font-size: 1.5rem;"><?= number_format($browse_total) ?></span>
                    <p class="text-muted mb-0">Total Trainings</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Trainings Grid -->
    <?php if ($browse_total > 0): ?>
        <div class="row g-4">
            <?php foreach ($browse_trainings as $training): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0 text-primary"><?= esc($training['training_name']) ?></h5>
                                <?php if ($training['is_enrolled']): ?>
                                    <span class="badge bg-success">Enrolled</span>
                                <?php else: ?>
                                    <span class="badge bg-<?= esc($training['status_name'] ?? 'secondary') ?>"><?= esc($training['status_name'] ?? 'N/A') ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="text-muted small mb-3">
                                <i class="fas fa-tag me-1"></i><?= esc($training['training_category_name'] ?? 'No Category') ?>
                            </p>
                            
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    From: <?= date('M d, Y', strtotime($training['training_datefrom'])) ?>
                                </small>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    To: <?= date('M d, Y', strtotime($training['training_dateto'])) ?>
                                </small>
                            </div>
                            <?php if (!empty($training['training_venue'])): ?>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= esc($training['training_venue']) ?>
                                </small>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($training['training_facilitator'])): ?>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                    <?= esc($training['training_facilitator']) ?>
                                </small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <div class="d-grid">
                                <?php if ($training['is_enrolled']): ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-check-circle me-1"></i>Already Joined
                                    </button>
                                <?php elseif (isset($training['has_attended']) && $training['has_attended']): ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-check-circle me-1"></i>Already Attended
                                    </button>
                                <?php else: ?>
                                    <?php
                                    // Check if training is open for registration
                                    $status_name = strtolower($training['status_name'] ?? '');
                                    $is_ongoing = isset($training['is_ongoing']) && $training['is_ongoing'];
                                    $is_open = ($status_name === 'open');
                                    $is_disabled = $is_ongoing || !$is_open;
                                    ?>
                                    <button class="btn <?= $is_disabled ? 'btn-secondary disabled' : 'btn-success join-training-btn' ?>" 
                                            data-training-id="<?= $training['id_training'] ?>"
                                            data-training-name="<?= esc($training['training_name']) ?>"
                                            <?= $is_disabled ? 'disabled' : '' ?>>
                                        <i class="fas fa-user-plus me-1"></i>Join Now
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mb-0">
            <i class="fas fa-info-circle me-2"></i>No trainings available at the moment. Please check back later.
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
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

<?= $this->endSection() ?>
