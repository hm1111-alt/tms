<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
        <title>Training Management System - Available Trainings</title>

        <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
        
        <link href="<?= assets('bootstrap/bootstrap.min.css') ?>" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        
        <link href="<?= css('styles.css') ?>" rel="stylesheet">
        <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
        
        <style>
            body {
                background-color: #f8f9fa;
            }
            
            .sb-nav-fixed #layoutSidenav_content {
                padding-top: 56px;
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
                min-height: 100px;
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
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                max-height: 3.2em;
                word-wrap: break-word;
            }
            
            .category-badge {
                background: #0d6efd;
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: 1rem;
                font-size: 0.75rem;
                font-weight: 600;
                display: inline-block;
                margin-bottom: 0.5rem;
                flex-shrink: 0;
            }
            
            .status-badge {
                padding: 0.25rem 0.65rem;
                border-radius: 0.75rem;
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                flex-shrink: 0;
            }
            
            .status-active { background-color: #d1e7dd; color: #0f5132; }
            .status-completed { background-color: #cfe2ff; color: #084298; }
            .status-cancelled { background-color: #f8d7da; color: #842029; }
            .status-upcoming { background-color: #fff3cd; color: #664d03; }
            
            .info-item {
                display: flex;
                align-items: flex-start;
                margin-bottom: 0.75rem;
                font-size: 0.875rem;
            }
            
            .info-icon {
                color: #198754;
                font-size: 1rem;
                margin-right: 0.5rem;
                min-width: 1.25rem;
            }
            
            .info-label {
                font-weight: 600;
                color: #5a5c69;
                margin-right: 0.35rem;
            }
            
            .deadline-alert {
                background: #fff3cd;
                border-left: 3px solid #ffc107;
                padding: 0.5rem 0.75rem;
                border-radius: 0.25rem;
                margin-top: 1rem;
                font-size: 0.85rem;
            }
            
            .deadline-icon {
                color: #856404;
                margin-right: 0.5rem;
            }
            
            .attendee-count {
                background: #e9ecef;
                padding: 0.5rem 0.75rem;
                border-radius: 0.5rem;
                font-size: 0.8rem;
                color: #5a5c69;
                display: inline-block;
                flex-shrink: 0;
            }
            
            .card-body {
                flex: 1;
                overflow-y: auto;
                max-height: 400px;
            }
            
            .card-footer {
                margin-top: auto;
                flex-shrink: 0;
            }
            
            @media (max-width: 768px) {
                .training-card {
                    margin-bottom: 1.5rem;
                }
            }
        </style>
    </head>
    
    <body class="sb-nav-fixed">
        <?= $this->include('layout/navbar_public') ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
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
                                <h2 class="h4 mb-1 text-success fw-bold"><?= $total_trainings ?></h2>
                                <small class="text-muted">Total Trainings Available</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-0">
                    <div class="card-body">
                        <p class="mb-0 text-muted">
                            Browse our comprehensive list of training programs designed to enhance your skills and professional development.
                        </p>
                    </div>
                </div>
                
                <?= $this->include('layout/messages') ?>

    <div class="container-fluid px-4">
        <?php if(empty($trainings)): ?>
            <div class="alert alert-info shadow-sm" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                No trainings available at the moment. Please check back later!
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach($trainings as $training): ?>
                    <div class="col-lg-6 col-xl-4">
                        <div class="training-card card h-100">
                            <div class="card-header-custom">
                                <?php if(!empty($training['training_category_name'])): ?>
                                    <span class="category-badge">
                                        <i class="bi bi-tag-fill me-1"></i>
                                        <?= esc($training['training_category_name']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if(!empty($training['status_name'])): ?>
                                    <span class="status-badge status-<?= strtolower($training['status_name']) ?> float-end">
                                        <?= esc($training['status_name']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <h5 class="training-title mb-0">
                                    <?= esc($training['training_name']) ?>
                                </h5>
                            </div>
                            
                            <div class="card-body p-3">
                                <?php if(!empty($training['training_datefrom']) || !empty($training['training_dateto'])): ?>
                                    <div class="info-item">
                                        <i class="bi bi-calendar-event info-icon"></i>
                                        <div>
                                            <span class="info-label">Schedule:</span>
                                            <?php if(!empty($training['training_datefrom']) && !empty($training['training_dateto'])): ?>
                                                <?= date('M d, Y', strtotime($training['training_datefrom'])) ?> - 
                                                <?= date('M d, Y', strtotime($training['training_dateto'])) ?>
                                            <?php elseif(!empty($training['training_datefrom'])): ?>
                                                <?= date('M d, Y', strtotime($training['training_datefrom'])) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($training['joining_deadline'])): ?>
                                    <div class="deadline-alert">
                                        <i class="bi bi-clock-history deadline-icon"></i>
                                        <strong>Registration Deadline:</strong>
                                        <?= date('F d, Y', strtotime($training['joining_deadline'])) ?>
                                        <?php 
                                        $deadline = strtotime($training['joining_deadline']);
                                        $today = time();
                                        $days_left = ceil(($deadline - $today) / (60 * 60 * 24));
                                        if($days_left > 0): ?>
                                            <span class="badge bg-warning text-dark ms-2">
                                                <?= $days_left ?> day(s) left
                                            </span>
                                        <?php elseif($days_left == 0): ?>
                                            <span class="badge bg-danger ms-2">Today</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary ms-2">Closed</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($training['facilitator'])): ?>
                                    <div class="info-item">
                                        <i class="bi bi-person-video info-icon"></i>
                                        <div>
                                            <span class="info-label">Facilitator:</span>
                                            <?= esc($training['facilitator']) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($training['venue'])): ?>
                                    <div class="info-item">
                                        <i class="bi bi-geo-alt info-icon"></i>
                                        <div>
                                            <span class="info-label">Venue:</span>
                                            <?= esc($training['venue']) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($training['training_hours'])): ?>
                                    <div class="info-item">
                                        <i class="bi bi-hourglass-split info-icon"></i>
                                        <div>
                                            <span class="info-label">Duration:</span>
                                            <?= esc($training['training_hours']) ?> hour(s)
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if(!empty($training['objective'])): ?>
                                    <div class="info-item">
                                        <i class="bi bi-bullseye info-icon"></i>
                                        <div>
                                            <span class="info-label">Objective:</span>
                                            <p class="mb-0 small text-muted" style="font-size: 0.8rem;"><?= esc(substr($training['objective'], 0, 150)) ?><?= strlen($training['objective']) > 150 ? '...' : '' ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mt-2">
                                    <div class="attendee-count">
                                        <i class="bi bi-people-fill me-1"></i>
                                        <strong><?= $training['attendee_count'] ?? 0 ?></strong> attendee(s) registered
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer border-0 pb-3 px-3 pt-3">
                                <?php if(session()->get('logged_in')): ?>
                                    <div class="d-grid gap-2 d-md-block">
                                        <a href="<?= site_url('trainings/enroll/' . $training['id_training']) ?>" 
                                           class="btn btn-success btn-sm me-md-2 w-100 w-md-auto">
                                            <i class="fas fa-user-plus me-1"></i> Join Now
                                        </a>
                                        <a href="<?= site_url('trainings/view/' . $training['id_training']) ?>" 
                                           class="btn btn-outline-primary btn-sm w-100 w-md-auto">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="d-grid gap-2">
                                        <a href="<?= site_url('/login') ?>" 
                                           class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-sign-in-alt me-1"></i> Login to Enroll
                                        </a>
                                        <a href="<?= site_url('trainings/view/' . $training['id_training']) ?>" 
                                           class="btn btn-outline-primary btn-sm w-100">
                                            <i class="fas fa-info-circle me-1"></i> View Training Info
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
            </main>
        </div>
        
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">
                    &copy; <?= date('Y') ?> CLSU. All rights reserved.
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

<?= $this->include('layout/footer') ?>

<?= $this->renderSection('footer_jscript') ?>
