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
        
        <?= $this->include('layout/navbar_public') ?>
        
        <div id="layoutSidenav_content">
                <main>
                        <div class="container-fluid px-4" style="padding-top: 0px; padding-bottom: 60px;">
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
                        
                        <?= $this->include('layout/messages') ?>
                        
                        <?= $this->renderSection('content') ?>
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
