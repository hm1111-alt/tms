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
    </head>
    
    <body class="sb-nav-fixed">
        <?= $this->include('layout/navbar') ?>
        
        <div id="layoutSidenav">
            