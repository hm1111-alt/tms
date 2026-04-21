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

        <!-- Bootstrap CSS (CDN) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
        
        <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
        <link href="<?= css('styles.css') ?>" rel="stylesheet" />
        <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
        
        <style>
            body.sb-nav-fixed #layoutSidenav_content {
                padding-top: 56px;
            }
            
            .dashboard-header-section {
                margin-top: 1rem !important;
                padding-top: 1rem;
            }
            
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
        
        <div id="layoutSidenav_content">
                        <main>
                        <div class="container-fluid px-4">
                                <!-- Header Section - Hide on manage trainings page -->
<?php if (!isset($hide_dashboard_stats) || !$hide_dashboard_stats): ?>
                                <div class="row dashboard-header-section" style="background-color: #FFF; border-radius: 0.375rem; margin-top: 1rem; margin-bottom: 1rem;">
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

                                <!-- Welcome Message Card - Exact same structure as header -->
                                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin-top: 0rem; margin-bottom: 1rem; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
                                    <div class="col-12">
                                        <p class="mb-0 text-muted">
                                            Welcome, <?= session()->get('first_name'); ?>! Manage and monitor all training programs across the organization.
                                        </p>
                                    </div>
                                </div>
<?php endif; ?>

<?php if (!isset($hide_messages) || !$hide_messages): ?>
                                <?= $this->include('layout/messages') ?>
<?php endif; ?>

                                <!-- Admin Statistics Cards - Only show if not hidden -->
<?php if (!isset($hide_dashboard_stats) || !$hide_dashboard_stats): ?>
                                <div class="row" style="margin-top: 0rem; margin-bottom: 1rem;">
                                    <div class="col-12">
                                        <div class="card shadow-sm" style="border: none; border-radius: 0.375rem; background-color: #FFF; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);">
                                            <div class="row">
                                                <div class="col-xl-4 col-md-6 mb-3">
                                                    <div class="training-card card h-100 shadow-sm border-0 stat-card" style="border-left-color: #0d6efd;">
                                                        <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #0d6efd; min-height: 80px;">
                                                            <h5 class="training-title mb-0" style="font-size: 1rem; color: #0d6efd;">
                                                                Total Trainings
                                                            </h5>
                                                        </div>
                                                        <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                            <h2 class="mb-0 fw-bold display-4" style="color: #0d6efd;"><?= number_format($total_trainings) ?></h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-xl-4 col-md-6 mb-3">
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
                                                
                                                <div class="col-xl-4 col-md-6 mb-3">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>

<!-- Quick Actions for Admin -->
<?php if (!isset($hide_quick_actions) || !$hide_quick_actions): ?>
                                <div class="row" style="margin-top: 0rem; margin-bottom: 1rem;">
                                    <div class="col-12">
                                        <div class="card shadow-sm border-0" style="background: transparent;">
                                            <div class="card-header bg-primary text-white">
                                                <i class="fas fa-cogs"></i> Admin Quick Actions
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <a href="<?= site_url('trainings') ?>" class="btn btn-outline-primary w-100 py-3">
                                                            <i class="fas fa-list fa-2x mb-2"></i>
                                                            <div>Manage All Trainings</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <a href="<?= site_url('trainings/add') ?>" class="btn btn-outline-success w-100 py-3">
                                                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                                            <div>Add New Training</div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<?php endif; ?>
<?php endif; ?>

                        <!-- Render content from child views -->
                        <?= $this->renderSection('content') ?>

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
        
        <!-- Scripts should be inside body tag -->
        <script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
        <script src="<?= js('scripts.js'); ?>"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                // Admin Dashboard ready
            });
        </script>
        
    </body>
</html>

<?= $this->renderSection('scripts') ?>
