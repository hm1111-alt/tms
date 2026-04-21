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
        <title><?= MY_APP_NAME; ?></title>
        

        <!-- Bootstrap 5 CSS -->
        <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">-->
        
  
        <!-- jQuery -->
        <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>


        <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
        <link href="<?= css('styles.css') ?>" rel="stylesheet" />
        <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
        
        
    <!-- Swiper CSS -->
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">-->
    
        
        <style>
            .form-group div.form-fields{
                padding-top: 5px;
            }
            .form-group div.form-fields:hover{
                background-color: #e1f1e7;
            }
            .swiper-container { 
                width: 100%; 
                height: 400px; 
                margin: auto; 
            }
            .swiper-slide { 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                font-size: 24px; 
                background: #f0f0f0; 
            }
    
            .memo_div:hover {
                background-color: #f5c850 !important;
                color: white;
            }
    
            .memo_div.active {
                background-color: var(--clsu-gold);
                color: #FFF;
            }
    
            .memo_div p {
                margin-bottom: 5px;
            }
    
            .memo_div {
                border-top: 1px solid #D8D8D8; 
                cursor: pointer;
                padding: 10px;
            }
            
            div.leave_credits div {
                font-size: 1.2rem;
                color: #009638;
                font-weight: 500;
            }
            
            div.leave_credits div div {
                border-radius: 5px;
                background-color: #007b3e;
                color: white;
                font-size: 2.3rem;
                margin-right:5px;
                width: 100%;
                font-weight: normal;
            }
            
            /* Add padding for fixed navbar */
            .sb-nav-fixed #layoutSidenav_content {
                padding-top: 56px;
            }
            
            /* Training Card Styles - Copied from Trainings Page */
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
            
            /* Quick Action Button Enhancements */
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
        </style>
        
</head>

<body <?php echo session()->get('password_reset')==1 ? 'class="sb-nav-fixed modal-open" style="overflow: hidden; padding-right: 15px;"' : 'class="sb-nav-fixed"';  ?>>
        
        <?php 
        // Determine which navbar to use based on user type
        $user_type = session()->get('user_type_name');
        if ($user_type === 'Admin'): 
            echo $this->include('layout/navbar_admin');
        elseif ($user_type === 'Employee'): 
            echo $this->include('layout/navbar_employee');
        elseif ($user_type === 'Guest'): 
            echo $this->include('layout/navbar_public');
        else: 
            echo $this->include('layout/navbar');
        endif; 
        ?>
        
        <div id="layoutSidenav">
            
    
                <?= $this->include('layout/sidebar') ?>


                <div id="layoutSidenav_content">

                        <div class="container-fluid px-4">
                                <!-- Header Section -->
                                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                                    <div class="col-xl-8">
                                        <h1 class="mt-4 mb-3">
                                            Dashboard
                                        </h1>
                                        <ol class="breadcrumb mb-3">
                                            <li class="breadcrumb-item active">
                                                Employee Portal
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
                                            Welcome, <?= session()->get('first_name'); ?>! Track your training progress and explore available learning opportunities.
                                        </p>
                                    </div>
                                </div>

                                <?= $this->include('layout/messages') ?>

                                <!-- Training Statistics Cards -->
                                <div class="row mb-4">
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #0d6efd;">
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
                                    
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #198754;">
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
                                    
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="training-card card h-100 shadow-sm border-0" style="border-left: 4px solid #0dcaf0;">
                                            <div class="card-header-custom" style="background: #f8f9fc; border-bottom: 2px solid #0dcaf0; min-height: 80px;">
                                                <h5 class="training-title mb-0" style="font-size: 1rem; color: #0dcaf0;">
                                                    <i class="fas fa-check-circle me-2"></i>Completed
                                                </h5>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-center justify-content-center">
                                                <h2 class="mb-0 fw-bold display-4" style="color: #0dcaf0;"><?= number_format($completed_trainings) ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-primary text-white">
                                                <i class="fas fa-bolt"></i> Quick Actions
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings') ?>" class="btn btn-outline-primary w-100 py-3">
                                                            <i class="fas fa-list fa-2x mb-2"></i>
                                                            <div>Browse All Trainings</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings/add') ?>" class="btn btn-outline-success w-100 py-3">
                                                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                                            <div>Add New Training</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings/categories') ?>" class="btn btn-outline-secondary w-100 py-3">
                                                            <i class="fas fa-tags fa-2x mb-2"></i>
                                                            <div>Categories</div>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <a href="<?= site_url('trainings/reports') ?>" class="btn btn-outline-dark w-100 py-3">
                                                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                                            <div>Reports</div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                <!--<div class="card mb-4"><div class="card-body">When scrolling, the navigation stays at the top of the page. This is the end of the static navigation demo.</div></div>-->
                        </div>

                        <footer class="py-4 bg-light mt-auto">
                                <div class="container-fluid px-4">
                                    <!--<div class="d-flex align-items-center justify-content-between small">-->
                                    <div class="align-items-center justify-content-between small">
                                        <div class="text-muted" style="text-align: right;">&copy; 2025 CLSU. All rights reserved. 
                                            <br>Powered by <span class="text-success">Management Information System Office (CLSU-MISO)</span>.
                                        </div>
                                        <!--<div>
                                            <a href="#">Privacy Policy</a>
                                            &middot;
                                            <a href="#">Terms &amp; Conditions</a>
                                        </div>-->
                                    </div>
                                </div>
                        </footer>
                </div>
        <!----end--of--layoutSidenav_content---->

        </div>
        <!----end--of--layoutSidenav---->
        
    </body>
</html>


<script src="<?= assets('bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
<script src="<?= js('scripts.js'); ?>"></script>

<!--<script src="<?php //assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>-->
<!--<script src="<?php // echo jquery('myscript/employee_form.js'); ?>"></script>-->


<!-- Swiper JS -->
<!--<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>-->

<?php if(session()->get('password_reset')==1){
    echo $this->include('layout/modal_change_password');
} ?>

<script type="text/javascript">
    $(document).ready(function(){
        // Dashboard ready
    });
</script>

