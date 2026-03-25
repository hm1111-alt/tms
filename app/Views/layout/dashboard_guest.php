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
        <title>Guest Portal - Dashboard</title>
        
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
            
            .guest-banner {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 2rem;
                border-radius: 0.5rem;
                margin-bottom: 2rem;
            }
        </style>
        
</head>

<body class="sb-nav-fixed">
        
        <?= $this->include('layout/navbar_public') ?>
        
        <div id="layoutSidenav">
                <?= $this->include('layout/sidebar') ?>
                <div id="layoutSidenav_content">
                        <main>
                <div class="container-fluid px-4">
                        <!-- Guest Banner -->
                        <div class="guest-banner">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="mb-2"><i class="fas fa-user-clock me-2"></i>Welcome, Guest!</h2>
                                    <p class="mb-0">Browse available trainings. Register or login to enroll and track your progress.</p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="d-grid gap-2">
                                        <a href="<?= site_url('/register') ?>" class="btn btn-light btn-lg text-primary">
                                            <i class="fas fa-user-plus me-2"></i>Register
                                        </a>
                                        <a href="<?= site_url('/login') ?>" class="btn btn-outline-light btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Header Section -->
                        <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                            <div class="col-xl-8">
                                <h1 class="mt-4 mb-3">
                                    Available Trainings
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
                                        <small class="text-muted">Total Trainings Available</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-body">
                                <p class="mb-0 text-muted">
                                    Browse our comprehensive list of training programs designed to enhance your skills and professional development.
                                    <strong>Login required to enroll.</strong>
                                </p>
                            </div>
                        </div>

                        <?= $this->include('layout/messages') ?>

                        <!-- Guest Statistics Cards -->
                        <div class="row mb-4">
                            <div class="col-xl-4 col-md-6 mb-3">
                                <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #198754;">
                                    <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #198754; min-height: 80px;">
                                        <h5 class="training-title mb-0" style="font-size: 1rem; color: #198754;">
                                            <i class="fas fa-graduation-cap me-2"></i>Total Trainings
                                        </h5>
                                    </div>
                                    <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                        <h2 class="mb-0 fw-bold display-4" style="color: #198754;"><?= number_format($total_trainings) ?></h2>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-md-6 mb-3">
                                <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #0d6efd;">
                                    <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #0d6efd; min-height: 80px;">
                                        <h5 class="training-title mb-0" style="font-size: 1rem; color: #0d6efd;">
                                            <i class="fas fa-calendar-check me-2"></i>Active Trainings
                                        </h5>
                                    </div>
                                    <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                        <h2 class="mb-0 fw-bold display-4" style="color: #0d6efd;"><?= number_format($trainings_this_month) ?></h2>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-xl-4 col-md-6 mb-3">
                                <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #ffc107;">
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

                        <!-- Guest Information Card -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-info text-white">
                                        <i class="fas fa-info-circle"></i> Guest Access Information
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <h6><i class="fas fa-check-circle text-success me-2"></i>What You Can Do:</h6>
                                                <ul class="mb-0">
                                                    <li>Browse available training programs</li>
                                                    <li>View training details and schedules</li>
                                                    <li>See training categories and descriptions</li>
                                                    <li>Learn about facilitators and venues</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><i class="fas fa-user-plus text-primary me-2"></i>Account Benefits:</h6>
                                                <ul class="mb-0">
                                                    <li><strong>View & Join Trainings</strong></li>
                                                    <li><strong>Submit Attendance & Feedback</strong></li>
                                                    <li><strong>Track My Trainings</strong></li>
                                                    <li><strong>View Completed Trainings</strong></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <a href="<?= site_url('/register') ?>" class="btn btn-primary w-100">
                                                    <i class="fas fa-user-plus me-2"></i>Register Now - It's Free!
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="<?= site_url('/login') ?>" class="btn btn-outline-success w-100">
                                                    <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Training Categories -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white">
                                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Training Categories</h5>
                                    </div>
                                    <div class="card-body">
                                        <?php if(!empty($category_summary)): ?>
                                            <div class="row g-3">
                                                <?php foreach($category_summary as $category): ?>
                                                    <div class="col-md-4">
                                                        <div class="d-flex align-items-center p-3 border rounded hover-shadow">
                                                            <i class="fas fa-folder-open text-primary fa-2x me-3"></i>
                                                            <div>
                                                                <strong><?= esc($category['training_category_name']) ?></strong>
                                                                <br>
                                                                <small class="text-muted"><?= $category['count'] ?> training(s)</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-muted text-center">No categories available.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Trainings Preview -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Recent Trainings</h5>
                                        <a href="<?= site_url('trainings') ?>" class="btn btn-sm btn-outline-primary">View All</a>
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
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach(array_slice($recent_trainings, 0, 5) as $training): ?>
                                                            <tr>
                                                                <td><?= esc($training['training_name']) ?></td>
                                                                <td><?= esc($training['training_category_name'] ?? 'N/A') ?></td>
                                                                <td><?= date('M d, Y', strtotime($training['training_datefrom'])) ?></td>
                                                                <td><?= date('M d, Y', strtotime($training['training_dateto'])) ?></td>
                                                                <td>
                                                                    <a href="<?= site_url('trainings/view/' . $training['id_training']) ?>" class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-eye"></i> View Details
                                                                    </a>
                                                                </td>
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
        
    </body>
</html>

<script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
<script src="<?= js('scripts.js'); ?>"></script>

<script type="text/javascript">
    $(document).ready(function(){
        // Guest Dashboard ready
    });
</script>
