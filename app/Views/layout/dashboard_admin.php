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
        <title>TMS Admin - Dashboard</title>
        
        <!-- jQuery -->
        <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>

        <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
        <link href="<?= css('styles.css') ?>" rel="stylesheet" />
        <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
        
        <style>
            .training-card {
                border: none;
                border-radius: 0.375rem;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
                transition: transform 0.2s, box-shadow 0.2s;
                height: 100%;
                margin-bottom: 1.5rem;
                display: flex;
                flex-direction: column;
            }
            
            .training-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.25);
            }
            
            .card-header-custom {
                background: #f8f9fc;
                border-bottom: 2px solid #198754;
                padding: 1rem 1.25rem;
                min-height: 80px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
            }
            
            .training-title {
                font-size: 1.1rem;
                font-weight: 600;
                color: #198754;
                margin-bottom: 0.5rem;
                line-height: 1.4;
            }
            
            .btn-outline-primary:hover,
            .btn-outline-success:hover,
            .btn-outline-warning:hover,
            .btn-outline-info:hover,
            .btn-outline-secondary:hover,
            .btn-outline-dark:hover {
                transform: translateY(-2px);
                transition: all 0.2s ease-in-out;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            
            .display-4 {
                font-size: 2.5rem;
                font-weight: 700;
            }
            
            .stat-card {
                border-left: 4px solid;
                transition: all 0.3s ease;
            }
            
            .stat-card:hover {
                transform: translateX(5px);
            }
        </style>
        
</head>

<body class="sb-nav-fixed">
        
        <?= $this->include('layout/navbar_admin') ?>
        
        <div id="layoutSidenav">
            
                <?= $this->include('layout/sidebar') ?>

                <div id="layoutSidenav_content">
                        <main>
                        <div class="container-fluid px-4">
                                <!-- Header Section -->
                                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                                    <div class="col-xl-8">
                                        <h1 class="mt-4 mb-3">
                                            Admin Dashboard
                                        </h1>
                                        <ol class="breadcrumb mb-3">
                                            <li class="breadcrumb-item active">
                                                Training Management System
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="text-end mt-4">
                                            <div class="bg-white p-3 rounded shadow-sm d-inline-block">
                                                <h2 class="h4 mb-1 text-success fw-bold"><?= number_format($total_trainings) ?></h2>
                                                <small class="text-muted">Total Trainings</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card mb-4 shadow-sm border-0">
                                    <div class="card-body">
                                        <p class="mb-0 text-muted">
                                            Welcome, <?= session()->get('first_name'); ?>! Manage and monitor all training programs across the organization.
                                        </p>
                                    </div>
                                </div>

                                <?= $this->include('layout/messages') ?>

                                <!-- Admin Statistics Cards -->
                                <div class="row mb-4">
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0 stat-card" style="border-left-color: #0d6efd;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #0d6efd; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #0d6efd;">
                                                    <i class="fas fa-graduation-cap me-2"></i>Total Trainings
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #0d6efd;"><?= number_format($total_trainings) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0 stat-card" style="border-left-color: #198754;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #198754; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #198754;">
                                                    <i class="fas fa-calendar-check me-2"></i>This Month
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #198754;"><?= number_format($trainings_this_month) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0 stat-card" style="border-left-color: #ffc107;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #ffc107; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #664d03;">
                                                    <i class="fas fa-clock me-2"></i>Upcoming
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #664d03;"><?= number_format($upcoming_trainings) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0 stat-card" style="border-left-color: #dc3545;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #dc3545; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #dc3545;">
                                                    <i class="fas fa-hourglass-half me-2"></i>Pending Requests
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #dc3545;"><?= number_format($pending_trainings) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>

<!-- Quick Actions for Admin -->
<?php if (!isset($hide_quick_actions) || !$hide_quick_actions): ?>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white">
                                                <i class="fas fa-cogs"></i> Admin Quick Actions
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings') ?>" class="btn btn-outline-primary w-100 py-3">
                                                            <i class="fas fa-list fa-2x mb-2"></i>
                                                            <div>Manage All Trainings</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings/add') ?>" class="btn btn-outline-success w-100 py-3">
                                                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                                            <div>Add New Training</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings/pending') ?>" class="btn btn-outline-warning w-100 py-3">
                                                            <i class="fas fa-tasks fa-2x mb-2"></i>
                                                            <div>Manage Pending Requests</div>
                                                            <?php if($pending_trainings > 0): ?>
                                                                <span class="badge bg-warning text-dark ms-1"><?= $pending_trainings ?></span>
                                                            <?php endif; ?>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings/categories') ?>" class="btn btn-outline-secondary w-100 py-3">
                                                            <i class="fas fa-tags fa-2x mb-2"></i>
                                                            <div>Manage Categories</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('reports') ?>" class="btn btn-outline-info w-100 py-3">
                                                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                                            <div>View Reports</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('users') ?>" class="btn btn-outline-dark w-100 py-3">
                                                            <i class="fas fa-users-cog fa-2x mb-2"></i>
                                                            <div>Manage Users</div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<?php endif; ?>

                                <!-- Recent Trainings Table -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card shadow-sm mb-4">
                                            <div class="card-header bg-white">
                                                <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Recent Trainings</h5>
                                            </div>
                                            <div class="card-body">
                                                <?php if(!empty($recent_trainings)): ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Training Name</th>
                                                                    <th>Category</th>
                                                                    <th>Date From</th>
                                                                    <th>Date To</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach($recent_trainings as $training): ?>
                                                                    <tr>
                                                                        <td><?= esc($training['training_name']) ?></td>
                                                                        <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                                                        <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                                                        <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                                                        <td><span class="badge bg-<?= $training['status_name'] === 'Active' ? 'success' : 'secondary' ?>"><?= esc($training['status_name'] ?? 'N/A') ?></span></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <p class="text-muted text-center">No recent trainings found.</p>
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
        </div>
        
    </body>
</html>

<script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
<script src="<?= js('scripts.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function(){
        // Admin Dashboard ready
    });
</script>
