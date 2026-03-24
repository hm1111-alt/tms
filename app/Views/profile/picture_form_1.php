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
        <title>Employee Portal v2</title>
        

        <!-- Bootstrap 5 CSS -->
        <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">-->
        
  
        <!-- jQuery -->
        <script src="<?= js('jquery-3.6.0.min.js'); ?>"></script>


        <link href="<?= assets('simple-datatables/style.min.css') ?>" rel="stylesheet" />
        <link href="<?= css('styles.css') ?>" rel="stylesheet" />
        <script src="<?= assets('fontawesome/all.js') ?>" crossorigin="anonymous"></script>
        
        
        <style>
            .form-group div.form-fields{
                padding-top: 5px;
            }
            .form-group div.form-fields:hover{
                background-color: #e1f1e7;
            }
            #profilepix {
                -webkit-border-radius: 50%;
                /*border: 10px solid #4cb9e0;*/
                display: inline-block;
                color: #6b6861;
            }
            #profilepix img {
                height: 200px;
                border-radius: 50%;
                -webkit-border-radius: 50%;
            }
            div#profilepix a div#change_dp_btn {
                position: relative;
            }
            div#profilepix a div#change_dp_btn div {
                font-size: 22px;
                position: absolute;
                float: right;
                height: 40px;
                width: 40px;
                padding: 3px;
                bottom: 25px;
                right: 25px;
                border-radius: 50%;
                color: #1d1f23;
                background-color: #CCCCCC;
                opacity: 0.8;
                transform: translate(50%, 50%);
                text-align: center;
            }
            
        </style>
        
</head>

<body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
                <!-- Navbar Brand-->
                <a class="navbar-brand ps-3" href="index.html" style="height:100%;">
                    <img src="<?= images('clsu_logo.png'); ?>" style="height:100%;">
                    Employee Portal
                </a>
                <!-- Sidebar Toggle-->
                <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
                <!-- Navbar Search-->
                <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                    <!--<div class="input-group">
                        <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                        <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                    </div>-->
                </form>

                <?php //$request = \Config\Services::request(); ?>
                <!-- Navbar-->
                <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if(session()->get('profile_icon')){ ?>
                                <img src="<?= images('profiles/thumbs/'.session()->get('profile_icon')); ?>" height="30" >
                            <?php } else if(session()->get('profile_picture')){ ?>
                                <img src="<?= images('profiles/'.session()->get('profile_picture')); ?>" height="30" >
                            <?php } else {  ?>
                                <i class="fas fa-user fa-fw"></i>  
                            <?php } ?>

                            <?= session()->get('display_name');  ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">Settings</a></li>
                            <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="<?= site_url('logout'); ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
        </nav>
        
        <div id="layoutSidenav">
            
    
                <?= $this->include('layout/sidebar') ?>


                <div id="layoutSidenav_content">

                        <div class="container-fluid px-3">

                                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                                        <div class="col-xl-8">
                                            <h1 class="mt-4 mb-2">
                                                Hi <?= session()->get('first_name');  ?>!
                                            </h1>

                                            <ol class="breadcrumb mb-3">
                                                <li class="breadcrumb-item">
                                                        Welcome to Employee Portal version 2.
                                                </li>
                                            </ol>
                                        </div>

                                        <div class="col-xl-4">

                                                <div class="mt-4">
                                                        <ul class="page_title_button" style="list-style: none; float:right;">
                                                                <?php /*<li style="">
                                                                    <a href="<?php echo site_url('employees/view/'); ?>" class="btn btn-light text-danger"  role="button" style="" onclick="return confirm('All unsaved changes will be lost if you leave now. Are you sure you want to leave?')">
                                                                        <i class="fas fa-ban" style=""></i>
                                                                        <div style="color: #999;">Cancel</div>
                                                                    </a>
                                                                </li>
                                                                 */ ?>
                                                                <li style="">
                                                                </li>
                                                        </ul>
                                                </div>
                                        </div>

                                </div>

                                <?= $this->include('layout/messages') ?>



                                <div style="min-height: 50vh">

                                        <form method="post" role="form" name="first_update_form" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); //echo site_url('attendance/holidays/submit'); //  ?>">

                                                <div class="row">

                                                        <div class="col mb-4 col-lg-6">

                                                                <div class="card mb-4 col-lg-12">


                                                                        <div class="card-body">

                                                                                <h2 class="h2-class ">My Profile</h2>


                                                                                <div class="form-group mb-4 mt-4 px-4" >

                                                                                <!--------------------------------------------------------->

                                                                                        <div class="row">
                                                                                                <div class="col col-lg-4 col-md-6">
                                                                                                    <div id="profilepix">

                                                                                                            <?php if(@file_get_contents(images('profiles/'.@$basic[0]->profile_picture)) && @$basic[0]->profile_picture!=''){ ?>

                                                                                                                <img src="<?php echo images('profiles/'.@$basic[0]->profile_picture); ?>" alt="">

                                                                                                            <?php } else { ?>

                                                                                                                <img src="<?php echo @$basic[0]->emp_sex=='Male' ? images('profiles/profile-man.png') : images('profiles/profile-woman.png'); ?>" alt="">

                                                                                                            <?php } ?>
                                                                                                        
                                                                                                        
                                                                                                            <a title="Update Profile Picture" onclick="alert('Update your details first.')" style="cursor: pointer;">
                                                                                                                <div id="change_dp_btn">
                                                                                                                    <div class="">
                                                                                                                        <i class="fa-solid fa-camera"></i>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </a>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col col-lg-8 col-md-6" style="">
                                                                                                    
                                                                                                    <div class="row form-fields">
                                                                                                        <label class="col col-lg-4"><b>ID no.</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_idno; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row form-fields">
                                                                                                        <label class="col col-lg-4"><b>Last Name</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_lname; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row form-fields">
                                                                                                        <label class="col col-lg-4"><b>First Name</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_fname; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row form-fields">
                                                                                                        <label class="col col-lg-4"><b>Middle Name</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_mname; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row form-fields">
                                                                                                        <label class="col col-lg-4"><b> Name Extension <br>(e.g. Jr, Sr, III)</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_extname!='' ? @$basic[0]->emp_extname : '<em>n/a</em>'; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row form-fields mt-3">
                                                                                                        <label class="col col-lg-4"><b>Sex</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_sex; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row form-fields">
                                                                                                        <label class="col col-lg-4"><b>Status</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_status2; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="row form-fields">
                                                                                                        <label class="col col-lg-4"><b>Position</b></label>
                                                                                                        <div class="col col-lg-8 mb-2">
                                                                                                            <?php echo @$basic[0]->emp_position_name; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                            
                                                                <div class="card mb-4 col-lg-12">
                                                                        <div class="card-body">

                                                                                <div class="form-group mb-4 mt-4 px-4" >

                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">Middle Name</label>
                                                                                            <div class="col-lg-4 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_mname')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_mname'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                    <input type="text" class="form-control" name="emp_mname" id="emp_mname" value="<?php echo @$basic[0]->emp_mname; ?>" maxlength="50" readonly title="Contact HRMO to update your middle name.">
                                                                                                    <input type="hidden" name="emp_fname" id="emp_fname" value="<?php echo @$basic[0]->emp_fname; ?>" >
                                                                                                    <input type="hidden" name="emp_lname" id="emp_lname" value="<?php echo @$basic[0]->emp_lname; ?>" >
                                                                                                    <input type="hidden" name="emp_extname" id="emp_extname" value="<?php echo @$basic[0]->emp_extname; ?>" >
                                                                                            </div>

                                                                                            <div class="col col-lg-4 mb-2">
                                                                                                <div class="row">
                                                                                                    <label class="col-lg-6" style="padding-right: 0px;">Middle Initial</label>
                                                                                                    <div class="col col-lg-6 mb-2">
                                                                                                        <?php if(@$validation && $validation->hasError('emp_mi')){ ?>
                                                                                                            <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_mi'); ?></label></span>
                                                                                                        <?php } ?>
                                                                                                        <input type="text" class="form-control" name="emp_mi" id="emp_mi" value="<?php echo set_value('emp_mi') ? set_value('emp_mi') : @$basic[0]->emp_mi; ?>" maxlength="3" placeholder="" onkeypress="validateletters(event)">
                                                                                                    </div>
                                                                                                    <div class="col-lg-12">
                                                                                                        <em>Leave blank if no middle name.</em>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">Personal Email</label>
                                                                                            <div class="col col-lg-8 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_email_personal')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_email_personal'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <input type="email" class="form-control" name="emp_email_personal" id="emp_email_personal" value="<?php echo set_value('emp_email_personal') ? set_value('emp_email_personal') : @$basic[0]->emp_email_personal; ?>" maxlength="100" placeholder="email@domain.com">
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">*<b>Official Email</b><br>
                                                                                                <em>(clsu.edu.ph, clsu2.edu.ph)</em>
                                                                                            </label>
                                                                                            <div class="col col-lg-8 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_email_official')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_email_official'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                    <input type="email" class="form-control" name="emp_email_official" id="emp_email_official" value="<?php echo set_value('emp_email_official') ? set_value('emp_email_official') : @$basic[0]->emp_email_official; ?>" maxlength="100" placeholder="email@clsu.edu.ph" required>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">Contact no.</label>
                                                                                            <div class="col col-lg-8 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_cpno')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_cpno'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <input type="text" class="form-control" name="emp_cpno" id="emp_cpno" value="<?php echo set_value('emp_cpno') ? set_value('emp_cpno') : @$basic[0]->emp_cpno; ?>" maxlength="11" placeholder="09123xxxxxx"  onkeypress="validateamount(event)">
                                                                                            </div>
                                                                                        </div>
                                                                                                
                                                                                </div>

                                                                        </div>
                                                                </div>

                                                        </div>

                                                        <div class="col mb-4 col-lg-6">

                                                                <div class="card mb-4 col-lg-12">

                                                                        <div class="card-body">

                                                                                <div class="form-group  mb-4 mt-4 px-4">



                                                                                        <div class="row form-fields mt-2">
                                                                                            <label class="col col-lg-4">*<b>Program</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_office')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_office'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_office" id="emp_office" style="padding-left: 3px;" required>
                                                                                                    <option value=''>Select...</option>
                                                                                                    <?php
                                                                                                    $office_post = set_value('emp_office') ? set_value('emp_office') : @$basic[0]->emp_office;

                                                                                                    /*
                                                                                                    if(@$programs)
                                                                                                    foreach($programs as $stn){ ?>
                                                                                                        <option <?php echo $stn->id_program==$office_post ? 'selected' : FALSE; ?> value="<?php echo $stn->id_program; ?>">
                                                                                                            <?php echo $stn->office_name; ?>
                                                                                                        </option>
                                                                                                    <?php }*/ ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">*<b>College / Division / Office</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_division')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_division'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_division" id="emp_division" style="padding-left: 3px;" required>
                                                                                                    <option value=''>Select...</option>
                                                                                                    <?php $division_post = set_value('emp_division') ? set_value('emp_division') : @$basic[0]->emp_division; ?>
                                                                                                    <option value='no' <?php echo $division_post=='no' || (@$basic && $basic[0]->emp_division=='') ? 'selected' : ''; ?>>--no office</option>
                                                                                                    <?php /*
                                                                                                    if(@$offices)
                                                                                                    foreach($offices as $off){ ?>
                                                                                                        <option <?php echo $off->id_office==$division_post ? 'selected' : FALSE; ?> value="<?php echo $off->id_office; ?>">
                                                                                                            <?php echo $off->division_name; ?>
                                                                                                        </option>
                                                                                                    <?php }*/ ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">*<b>Department / Unit / Section</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_unit')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_unit'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_unit" id="emp_unit" style="padding-left: 3px;" required>
                                                                                                    <option value=''>Select...</option>
                                                                                                    <?php $unit_post = set_value('emp_unit') ? set_value('emp_unit') : @$basic[0]->emp_unit; ?>
                                                                                                    <option value='no' <?php echo $unit_post=='no' || (@$basic && $basic[0]->emp_unit==0) ? 'selected' : ''; ?>>--no division</option>
                                                                                                    <?php /*
                                                                                                    if(@$divisions)
                                                                                                    foreach($divisions as $div){ ?>
                                                                                                        <option <?php echo $div->id_division==$unit_post ? 'selected' : FALSE; ?> value="<?php echo $div->id_division; ?>">
                                                                                                            <?php echo $div->unit_name; ?>
                                                                                                        </option>
                                                                                                    <?php }*/ ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <?php /*<div class="row form-fields">
                                                                                            <label class="col col-lg-4">Unit</label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_subunit')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_subunit'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_subunit" style="padding-left: 3px;">
                                                                                                    <option value=''>Select...</option>
                                                                                                    <?php 
                                                                                                    $unit_post = set_value('emp_subunit') ? set_value('emp_subunit') : @$basic[0]->emp_subunit;
                                                                                                    if(@$units)
                                                                                                    foreach($units as $unit){ ?>
                                                                                                        <option <?php echo $unit->id_unit==$unit_post ? 'selected' : FALSE; ?> value="<?php echo $unit->id_unit; ?>">
                                                                                                            <?php echo $unit->unit_name; echo @$unit->unit_abbr ? ' ('.@$unit->unit_abbr.')' : ''; ?>
                                                                                                        </option>
                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                         
                                                                                    
                                                                                        <div class="row">
                                                                                            <label class="col col-lg-4"></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <em>Note: Select "no office" or "no unit"  </em>
                                                                                            </div>
                                                                                        </div>
                                                                                         */ ?>

                                                                                </div>


                                                                        </div>
                                                                </div>
                                                                <div class="card mb-4 col-lg-12">

                                                                        <div class="card-body">

                                                                                <h4 class="h4-class ">Leave module</h4>
                                                                                
                                                                                <div class="form-group  mb-4 mt-4 px-4">


                                                                                        <div class="row">
                                                                                            <label class="col col-lg-4"><b>E-signature</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_subunit')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_subunit'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                    
                                                                                                    <div class="row form-group preview_div upload_div" style="min-height: 130px; display: none;">
                                                                                                        <label class="col-form-label label-align" for="preview_photo">Preview
                                                                                                        </label>
                                                                                                        <div class="">
                                                                                                            <img id="preview_photo" src="#" alt="preview file" style="" />
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div>
                                                                                                        <input id="file_id" name="file_id" type="hidden" value="<?php echo @$basic[0]->id_file; ?>">
                                                                                                        <input id="file_folder" name="file_folder" type="hidden" value="<?php echo @$basic[0]->file_folder; ?>">
                                                                                                        <input id="employee_id" name="employee_id" type="hidden" value="<?php echo @$basic[0]->id_employee; ?>">
                                                                                                        <input id="emp_idno" name="emp_idno" type="hidden" value="<?php echo @$basic[0]->emp_idno; ?>">

                                                                                                        <input type="file" name="file_upload" id="file_upload" class="form-control "  onchange="readURL1(this)" />
                                                                                                        <span style="font-style: italic; font-size: 11px; color: #7f7f7f;  top: 5px; left: 140px;">Format: JPG, JPEG, PNG.<br/>Filesize: 3.00 MB max.</span>
                                                                                                    </div> 
                                                                                            </div>
                                                                                        </div>

                                                                                </div>
                                                                        </div>
                                                                </div>
                                                            
                                                                <div class="form-group" style="padding-bottom: 30px;">

                                                                        <div class="col-lg-12" style="text-align: center;">
                                                                            <?php /*<a href="<?php echo site_url('employees/view/'.$request->uri->getSegment(3)); ?>" onclick="return confirm('All unsaved changes will be lost if you leave now. Are you sure you want to leave?')" class="ban-circle">
                                                                                <button type="button" id="cancel" class="btn-light btn cancel_btn" style="margin-left:15px;float: right; background-color: #e9ecef;">Cancel</button>
                                                                            </a>
                                                                             */ ?>
                                                                            <button type="button" class="btn btn-success btn-lg save_button1" id="submit_btn" style="display: inline-block; padding-left: 50px; padding-right: 50px;">Update</button>
                                                                        </div>

                                                                </div>

                                                        </div>


                                                </div>



                                        </form>
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

<script type="text/javascript">
    

        $(document).ready(function(){


                $('#submit_btn').on('click',function(){
                    
                        if($('#emp_email_official').val()=='' || $('#emp_office').val()=='' || $('#emp_division').val()=='' || $('#emp_unit').val()==''){
                            alert('Please fillup all required fields.');
                        } else if($('#emp_mname').val()!='' && $('#emp_mi').val()==''){
                            alert('Middle Initial is required.');
                        } /*else if($('#file_upload').val()==''){
                            alert('Please upload your e-signature.');
                        }*/ else {
                            $("form[name='first_update_form']").submit();
                        }
                        //$("form[name='first_update_form']").submit();
                });
                
                
                
                // Data structure: Categories -> Subcategories -> Items
                const data = {
                    <?php foreach($lib_offices as $off){ ?>
                        '<?php echo $off->id_office; ?>' : {
                            label : "<?php echo $off->office_name; ?>",
                            subcategories: {
                                <?php if(@$off->divisions){
                                    foreach($off->divisions as $div){ ?>
                                        '<?php echo $div->id_division; ?>' : {
                                            label : "<?php echo $div->division_name; ?>",
                                            items: [
                                                <?php if(@$div->units){
                                                    foreach($div->units as $unit){ ?>
                                                        { value: "<?php echo $unit->id_unit; ?>", label : "<?php echo $unit->unit_name; ?>" },
                                                    <?php } ?>,
                                                <?php } ?>
                                            ]
                                        },
                                    <?php } ?>
                                <?php } ?>
                            }
                        },

                    <?php } ?>
                };

                // Populate first dropdown
                $.each(data, function(categoryKey, categoryData) {
                    $("#emp_office").append(`<option value="${categoryKey}">${categoryData.label}</option>`);
                });

                // Populate second dropdown based on first dropdown selection
                $("#emp_office").change(function() {
                    const selectedCategory = $(this).val();
                    const emp_division = $("#emp_division");
                    const emp_unit = $("#emp_unit");

                    emp_division.empty().append('<option value="">--Select--</option>'); // Reset
                    emp_division.append('<option value="no">(no office)</option>'); 

                    emp_unit.empty().append('<option value="">--Select college/division/office first--</option>'); // Reset
                    emp_unit.append('<option value="no">(no unit)</option>'); 


                    if (selectedCategory && data[selectedCategory]) {
                        $.each(data[selectedCategory].subcategories, function(subcategoryKey, subcategoryData) {
                            emp_division.append(`<option value="${subcategoryKey}">${subcategoryData.label}</option>`);
                        });
                    }
                    emp_division.val('no');
                    emp_unit.val('no');

                });

                // Populate third dropdown based on second dropdown selection
                $("#emp_division").change(function() {
                    const selectedCategory = $("#emp_office").val();
                    const selectedSubcategory = $(this).val();
                    const emp_unit = $("#emp_unit");

                    emp_unit.empty().append('<option value="">--Select--</option>'); // Reset
                    emp_unit.append('<option value="no" selected>(no unit)</option>'); 

                    if (selectedCategory && selectedSubcategory && data[selectedCategory].subcategories[selectedSubcategory]) {
                        $.each(data[selectedCategory].subcategories[selectedSubcategory].items, function(index, item) {
                            emp_unit.append(`<option value="${item.value}">${item.label}</option>`);
                        });
                    }

                    //if($(this).val()=='no'){
                    //    emp_unit.val('no')
                    //}

                });

        });


        function validateamount(evt) {
                var theEvent = evt || window.event;
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode( key );
                var regex = /[0-9]|[\b]|[\t]/;
                if( !regex.test(key) ) {
                      theEvent.returnValue = false;
                      if(theEvent.preventDefault) theEvent.preventDefault();
                }
        } 

        function validateletters(evt) {
                var theEvent = evt || window.event;
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode( key );
                var regex = /[a-z]|[A-Z]|[ñ]|[Ñ]|[\b]|[\t]/;
                if( !regex.test(key) ) {
                      theEvent.returnValue = false;
                      if(theEvent.preventDefault) theEvent.preventDefault();
                }
        } 
                        
        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                var filsize = parseFloat(input.files[0].size);
                var maxsize = parseFloat(3145728) ; // 3mb = 3145728 , 5mb = 5242880

                if(filsize>maxsize){
                    alert('The file selected is larger than max size allowed.');
                    $('#file_upload').val('');
                    $('#preview_photo').html('');
                    $('.preview_div').slideUp();
                    $('#preview_photo').slideUp();
                } else {

                    var filenam = input.files[0].name;
                    var res = filenam.split('.');
                    var len = parseInt(res.length);
                    var len2 = len-1;
                    var extension = res[len2];

                    if(extension=='jpg' || extension=='jpeg' || extension=='png' || extension=='JPG' || extension=='JPEG' || extension=='PNG'){
                        reader.onload = function (e) {

                            $('.preview_div').slideDown();
                            $('#preview_photo').slideDown();
                            $('#preview_photo')
                                .attr('src', e.target.result)
                                .width(200)
                                .height(auto);
                        };
                    } else {
                        alert('The file type of selected file is invalid.');
                        $('#file_upload').val('');
                        $('#preview_photo').html('');
                        $('.preview_div').slideUp();
                        $('#preview_photo').slideUp();
                    }

                    reader.readAsDataURL(input.files[0]);
                }
           }
        }
    </script>

