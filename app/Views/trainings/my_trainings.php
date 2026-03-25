<?= $this->extend('layout/' . (session()->get('user_type_name') === 'Admin' ? 'dashboard_admin' : 'dashboard_employee')) ?>

<?= $this->section('content') ?>

<div class="container-fluid px-4">
    <?= $this->include('layout/messages') ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Upcoming</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($total_upcoming) ?></h2>
                        </div>
                        <i class="fas fa-calendar fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Ongoing</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($total_ongoing) ?></h2>
                        </div>
                        <i class="fas fa-play-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Completed</h6>
                            <h2 class="mb-0 fw-bold"><?= number_format($total_completed) ?></h2>
                        </div>
                        <i class="fas fa-check-circle fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-3" id="myTrainingsTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">
                <i class="fas fa-calendar me-2"></i>Upcoming (<?= $total_upcoming ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#ongoing" type="button" role="tab" aria-controls="ongoing" aria-selected="false">
                <i class="fas fa-play-circle me-2"></i>Ongoing (<?= $total_ongoing ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                <i class="fas fa-check-circle me-2"></i>Completed (<?= $total_completed ?>)
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="myTrainingsTabContent">
        
        <!-- Upcoming Trainings Tab -->
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php if (count($upcoming_trainings_list) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="upcomingTable">
                                <thead>
                                    <tr>
                                        <th>Training Name</th>
                                        <th>Category</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Facilitator</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcoming_trainings_list as $training): ?>
                                        <tr>
                                            <td><?= esc($training['training_name']) ?></td>
                                            <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                            <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                            <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                            <td><?= esc($training['training_facilitator']) ?></td>
                                            <td><span class="badge bg-info"><?= esc($training['status_name'] ?? 'Upcoming') ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>You have no upcoming trainings.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Ongoing Trainings Tab -->
        <div class="tab-pane fade" id="ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php if (count($ongoing_trainings_list) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="ongoingTable">
                                <thead>
                                    <tr>
                                        <th>Training Name</th>
                                        <th>Category</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Facilitator</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($ongoing_trainings_list as $training): ?>
                                        <tr>
                                            <td><?= esc($training['training_name']) ?></td>
                                            <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                            <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                            <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                            <td><?= esc($training['training_facilitator']) ?></td>
                                            <td><span class="badge bg-success">Ongoing</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php if (count($completed_trainings_list) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="completedTable">
                                <thead>
                                    <tr>
                                        <th>Training Name</th>
                                        <th>Category</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Facilitator</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($completed_trainings_list as $training): ?>
                                        <tr>
                                            <td><?= esc($training['training_name']) ?></td>
                                            <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                            <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                            <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                            <td><?= esc($training['training_facilitator']) ?></td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>You have no completed trainings yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pending Requests Tab -->
        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <?php if (count($pending_trainings_list) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="pendingTable">
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
                                    <?php foreach ($pending_trainings_list as $pending): ?>
                                        <tr>
                                            <td><?= esc($pending['training_name']) ?></td>
                                            <td><?= esc($pending['training_category_name'] ?? 'N/A') ?></td>
                                            <td><?= date('M d, Y', strtotime($pending['date_requested'])) ?></td>
                                            <td>
                                                <?php if ($pending['is_approved'] == 1): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php elseif ($pending['is_disapproved'] == 1): ?>
                                                    <span class="badge bg-danger">Rejected</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($pending['approve_remarks'] ?? '-') ?></td>
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

<script src="<?= assets('simple-datatables/date-fns.min.js') ?>"></script>
<script src="<?= assets('simple-datatables/umd/index.js') ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize upcoming table
        if (document.getElementById('upcomingTable')) {
            new simpleDatatables.DataTable("#upcomingTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50]
            });
        }
        
        // Initialize ongoing table
        if (document.getElementById('ongoingTable')) {
            new simpleDatatables.DataTable("#ongoingTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50]
            });
        }
        
        // Initialize completed table
        if (document.getElementById('completedTable')) {
            new simpleDatatables.DataTable("#completedTable", {
                searchable: true,
                sortable: true,
                perPage: 10,
                perPageSelect: [5, 10, 25, 50]
            });
        }
    });
</script>

<?= $this->endSection() ?>
