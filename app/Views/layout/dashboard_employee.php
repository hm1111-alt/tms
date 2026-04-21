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
        <title>Training Management System - Dashboard</title>
        
        <!-- jQuery -->
        <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>

        <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
        <link href="<?= css('styles.css') ?>" rel="stylesheet" />
        <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
        
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
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
            
            /* Add top padding to prevent navbar overlap */
            .sb-nav-fixed #layoutSidenav_content {
                padding-top: 56px;
            }
            
            main {
                min-height: calc(100vh - 56px);
            }
            
            .container-fluid.px-4 {
                padding-top: 0px !important;
                padding-bottom: 60px !important;
            }
        </style>
        
</head>

<body>
        
        <?= $this->include('layout/navbar_employee') ?>
        
        <div id="layoutSidenav_content">
                <main>
                        <div class="container-fluid px-4" style="padding-top: 0px; padding-bottom: 60px;">
                                <!-- Header Section -->
                                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                                    <div class="col-xl-8">
                                        <h1 class="mt-4 mb-3">
                                            <?= $page_title ?? 'My Dashboard' ?>
                                        </h1>
                                        <ol class="breadcrumb mb-3">
                                            <li class="breadcrumb-item">
                                                <a href="<?= site_url('dashboard') ?>" style="text-decoration: none;">Training Management System</a>
                                            </li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                <?= $page_title ?? 'My Trainings' ?>
                                            </li>
                                        </ol>
                                    </div>
                                    <!-- Show count on pages that don't hide it -->
                                    <?php if (!isset($hide_header_count) || !$hide_header_count): ?>
                                    <div class="col-xl-4">
                                        <div class="text-end mt-4">
                                            <div class="bg-white p-3 rounded shadow-sm d-inline-block">
                                                <h2 class="h4 mb-1 text-success fw-bold"><?= number_format($my_trainings_count) ?></h2>
                                                <small class="text-muted">My Trainings</small>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Welcome Message - Hide on My Trainings page -->
                                <?php if (!isset($hide_welcome_message) || !$hide_welcome_message): ?>
                                <div class="card mb-4 shadow-sm border-0">
                                    <div class="card-body">
                                        <p class="mb-0 text-muted">
                                            Welcome, <?= session()->get('first_name'); ?>! 
                                            Track your training progress and explore new learning opportunities.
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?= $this->include('layout/messages') ?>

<!-- Employee Statistics Cards -->
<?php if (!isset($hide_quick_actions) || !$hide_quick_actions): ?>
                                <div class="row mb-4">
                                    <div class="col-xl-4 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #198754;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #198754; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #198754;">
                                                    <i class="fas fa-book-reader me-2"></i>My Trainings
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #198754;"><?= number_format(isset($my_trainings_count) ? $my_trainings_count : 0) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #0dcaf0;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #0dcaf0; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #0dcaf0;">
                                                    <i class="fas fa-check-circle me-2"></i>Completed
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #0dcaf0;"><?= number_format(isset($completed_trainings) ? $completed_trainings : 0) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-xl-4 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #0d6efd;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #0d6efd; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #0d6efd;">
                                                    <i class="fas fa-calendar-alt me-2"></i>Upcoming
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #0d6efd;"><?= number_format(isset($upcoming_trainings) ? $upcoming_trainings : 0) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<?php endif; ?>

<!-- Quick Actions for Employee -->
<?php if (!isset($hide_quick_actions) || !$hide_quick_actions): ?>
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-success text-white">
                                                <i class="fas fa-bolt"></i> My Training Actions
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <a href="<?= site_url('trainings') ?>" class="btn btn-outline-success w-100 py-3">
                                                            <i class="fas fa-search fa-2x mb-2"></i>
                                                            <div>Browse & Join Trainings</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <a href="<?= site_url('mytrainings') ?>" class="btn btn-outline-primary w-100 py-3">
                                                            <i class="fas fa-book-open fa-2x mb-2"></i>
                                                            <div>My Trainings</div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<?php endif; ?>

                        </div>
                </main>

                <?= $this->renderSection('content') ?>

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
        
    </body>
</html>

<script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
<script src="<?= js('scripts.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function(){
        // Employee Dashboard ready
    });
</script>
