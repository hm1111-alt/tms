<?= $this->extend('layout/' . (session()->get('user_type_name') === 'Admin' ? 'dashboard_admin' : 'dashboard_employee')) ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            <h2><i class="fas fa-clock me-2"></i>Pending Trainings</h2>
            <p class="text-muted">Training requests awaiting approval</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-triangle-exclamation me-2"></i><?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        <?= stripos(session()->get('user_type_name'), 'admin') !== false ? 'All Pending Trainings' : 'My Pending Requests' ?>
                    </h5>
                </div>
                <div class="col-auto">
                    <span class="badge bg-warning text-dark">
                        <?= count($pending_trainings_list) ?> Pending
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <?php if (!empty($pending_trainings_list)): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Date Submitted</th>
                                <?php if (stripos(session()->get('user_type_name'), 'admin') !== false): ?>
                                <th>Employee</th>
                                <?php endif; ?>
                                <th>Training Name</th>
                                <th>Category</th>
                                <th>Dates</th>
                                <th>Hours</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_trainings_list as $pt): ?>
                                <tr>
                                    <td>
                                        <small class="text-muted">
                                            <?= date('M d, Y', strtotime($pt['added_date'])) ?>
                                        </small>
                                    </td>
                                    
                                    <?php if (stripos(session()->get('user_type_name'), 'admin') !== false): ?>
                                        <td>
                                            <strong>
                                                <?= esc($pt['emp_lname'] ?? 'N/A') ?>, 
                                                <?= esc($pt['emp_fname'] ?? 'N/A') ?>
                                            </strong>
                                            <br>
                                            <small class="text-muted"><?= esc($pt['emp_idno'] ?? '-') ?></small>
                                        </td>
                                    <?php endif; ?>
                                    
                                    <td>
                                        <strong><?= esc($pt['training_name']) ?></strong>
                                        <?php if (!empty($pt['training_venue'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> <?= esc($pt['training_venue']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-info">
                                            <?= esc($pt['training_category_name'] ?? 'General') ?>
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <small class="text-muted">
                                            From: <?= date('M d, Y', strtotime($pt['training_datefrom'])) ?>
                                            <br>To: <?= date('M d, Y', strtotime($pt['training_dateto'])) ?>
                                        </small>
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= esc($pt['training_hours']) ?> hrs
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <?php if (empty($pt['is_approved']) && empty($pt['is_disapproved'])): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        <?php elseif (!empty($pt['is_approved'])): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Approved
                                            </span>
                                        <?php elseif (!empty($pt['is_disapproved'])): ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> Disapproved
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($pt['approve_remarks'])): ?>
                                            <br>
                                            <small class="text-muted"><?= esc($pt['approve_remarks']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary view-pending-btn" 
                                                data-id="<?= $pt['id_pending_training'] ?>"
                                                title="View Details">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No pending trainings found</h5>
                    <p class="text-muted">
                        <?php if (stripos(session()->get('user_type_name'), 'admin') !== false): ?>
                            There are no training requests waiting for approval.
                        <?php else: ?>
                            You haven't submitted any training requests yet.
                            <br>
                            <a href="<?= site_url('trainings') ?>" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Browse Available Trainings
                            </a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- View Pending Training Modal -->
<div class="modal fade" id="viewPendingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clock me-2"></i>Pending Training Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="pendingDetailsContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // View pending training details
    $(document).on('click', '.view-pending-btn', function() {
        var trainingId = $(this).data('id');
        
        $.ajax({
            url: '<?= site_url("trainings/get_pending_details") ?>/' + trainingId,
            type: 'POST',
            data: { id_pending_training: trainingId },
            success: function(response) {
                $('#pendingDetailsContent').html(response);
                $('#viewPendingModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('Error loading pending details:', error);
                $('#pendingDetailsContent').html('<div class="alert alert-danger">Error loading details</div>');
                $('#viewPendingModal').modal('show');
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
