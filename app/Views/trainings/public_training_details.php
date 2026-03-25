<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
    <title><?= MY_APP_NAME; ?> - <?= esc($training['training_name'] ?? 'Training Details') ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>
    <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
    <link href="<?= css('styles.css') ?>" rel="stylesheet" />
    <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .sb-nav-fixed #layoutSidenav_content {
            padding-top: 56px;
        }
        
        /* Override sidebar styles - force no sidebar */
        #layoutSidenav {
            display: block !important;
            width: 100% !important;
        }
        
        #layoutSidenav_content {
            margin-left: 0 !important;
            width: 100% !important;
        }
        
        .detail-card {
            background-color: white;
            border-radius: 0.375rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #4e73df 0%, #2e59d9 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        
        .card-body-custom {
            padding: 2rem;
        }
        
        .section-title {
            color: #4e73df;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 0;
        }
        
        .info-table td {
            padding: 0.5rem 0;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: 600;
            color: #5a5c69;
            width: 35%;
        }
        
        .info-value {
            color: #333;
        }
        
        .content-box {
            background-color: #f8f9fc;
            border-left: 4px solid #4e73df;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 0.25rem;
        }
        
        .content-box h5 {
            color: #4e73df;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .content-box p {
            color: #5a5c69;
            line-height: 1.6;
            margin-bottom: 0;
        }
        
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }
        
        .action-buttons {
            position: sticky;
            bottom: 20px;
            z-index: 100;
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
    </style>
</head>

<body class="sb-nav-fixed">
    
    <?php 
    // Use public navbar (no sidebar) for non-logged-in users
    echo $this->include('layout/navbar_public');
    ?>

    <div id="layoutSidenav_content">
            
            <div class="container-fluid px-4">
                
                <!-- Page Header -->
                <div class="row mt-4 mb-4" style="background-color: white; border-radius: 0.375rem; margin:1rem 0 1rem 0; padding: 1.5rem;">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="mt-2 mb-3">
                                    <i class="fas fa-graduation-cap"></i> Training Details
                                </h1>
                                <ol class="breadcrumb mb-3">
                                    <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Available Trainings</a></li>
                                    <li class="breadcrumb-item active"><?= esc($training['training_name']) ?></li>
                                </ol>
                            </div>
                            <div>
                                <ul class="page_title_button" style="list-style: none; margin: 0;">
                                    <li>
                                        <a href="<?= site_url('/') ?>" class="btn btn-light">
                                            <i class="fas fa-arrow-circle-left"></i>
                                            <div class="text-muted">Back</div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?= $this->include('layout/messages') ?>
                
                <!-- Training Details Card -->
                <div class="detail-card">
                    <div class="card-header-custom">
                        <h2 class="mb-0"><i class="fas fa-graduation-cap"></i> <?= esc($training['training_name']) ?></h2>
                    </div>
                    
                    <div class="card-body-custom">
                        
                        <!-- Description Section -->
                        <?php 
                        $descriptions = [];
                        if (isset($training['from_lib']) && $training['from_lib']) {
                            $db = \Config\Database::connect();
                            $descriptions = $db->table('lib_trainings_des')
                                ->where('training_id', $training['id_training'])
                                ->get()
                                ->getResultArray();
                        }
                        ?>
                        
                        <?php if (!empty($descriptions) || !empty($training['objective'])): ?>
                        <div class="content-box">
                            <h5><i class="fas fa-align-left"></i> Description</h5>
                            <?php if (!empty($descriptions)): ?>
                                <?php foreach($descriptions as $desc): ?>
                                    <p><?= nl2br(esc($desc['training_des'])) ?></p>
                                <?php endforeach; ?>
                            <?php elseif (!empty($training['objective'])): ?>
                                <p><?= nl2br(esc($training['objective'])) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Learnings Section -->
                        <?php 
                        $learnings = [];
                        if (isset($training['from_lib']) && $training['from_lib']) {
                            $db = \Config\Database::connect();
                            $learnings = $db->table('lib_trainings_learnings')
                                ->where('training_id', $training['id_training'])
                                ->get()
                                ->getResultArray();
                        }
                        ?>
                        
                        <?php if (!empty($learnings) || !empty($training['expertise'])): ?>
                        <div class="content-box">
                            <h5><i class="fas fa-lightbulb"></i> Learning Objectives</h5>
                            <?php if (!empty($learnings)): ?>
                                <?php foreach($learnings as $learning): ?>
                                    <p><?= nl2br(esc($learning['training_learning'])) ?></p>
                                <?php endforeach; ?>
                            <?php elseif (!empty($training['expertise'])): ?>
                                <p><?= nl2br(esc($training['expertise'])) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <hr style="margin: 2rem 0;">
                        
                        <!-- Training Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="section-title"><i class="fas fa-info-circle"></i> Training Information</h5>
                                <table class="info-table">
                                    <tr>
                                        <td class="info-label">Category:</td>
                                        <td class="info-value">
                                            <span class="badge bg-info badge-custom"><?= esc($training_type) ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Duration:</td>
                                        <td class="info-value">
                                            <strong><?= esc($training['training_hours'] ?? 'N/A') ?></strong> hours
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Date From:</td>
                                        <td class="info-value">
                                            <?= !empty($training['training_datefrom']) ? date('M j, Y', strtotime($training['training_datefrom'])) : 'TBA' ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Date To:</td>
                                        <td class="info-value">
                                            <?= !empty($training['training_dateto']) ? date('M j, Y', strtotime($training['training_dateto'])) : 'TBA' ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="section-title"><i class="fas fa-map-marker-alt"></i> Venue & Facilitator</h5>
                                <table class="info-table">
                                    <tr>
                                        <td class="info-label">Venue:</td>
                                        <td class="info-value"><?= esc($training['training_venue'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Facilitator:</td>
                                        <td class="info-value"><?= esc($training['training_facilitator'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Status:</td>
                                        <td class="info-value">
                                            <?php if (!empty($training['status_name'])): ?>
                                                <span class="badge bg-success badge-custom"><?= esc($training['status_name']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary badge-custom">Available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="<?= site_url('/') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                                
                                <?php if(session()->get('logged_in')): ?>
                                    <?php if(isset($training['is_enrolled']) && $training['is_enrolled']): ?>
                                        <button class="btn btn-secondary btn-lg" disabled>
                                            <i class="fas fa-check-circle"></i> Already Joined
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= site_url('trainings/enroll/' . $training['id_training']) ?>" class="btn btn-success btn-lg">
                                            <i class="fas fa-user-plus"></i> Join Now
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?= site_url('/login') ?>" class="btn btn-success btn-lg">
                                        <i class="fas fa-sign-in-alt"></i> Login to Join
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <?= $this->include('layout/footer') ?>
            
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="<?= assets('bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= assets('js/scripts.js') ?>"></script>
    
</body>
</html>
