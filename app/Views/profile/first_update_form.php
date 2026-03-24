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
                width: 100%;
                height: auto;
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


                                        <div class="row form-fields mt-4">
                                            <label class="col col-lg-4"><b>Item Class</b></label>
                                            <div class="col col-lg-8 mb-2 mb-2">
                                                <?php $emp_class = @$basic[0]->emp_class_name=='FACULTY' ? 2 : 1; ?>
                                                <input type="text" value="<?php echo @$basic[0]->emp_class_name; ?>" class="form-control" readonly>
                                                <input type="hidden" value="<?php echo $emp_class; ?>" name="emp_class">
                                            </div>
                                        </div>
                                        
                                        <?php if( $emp_class==2){ ?>
                                    
                                                        <div class="row form-fields mt-3">
                                                            <label class="col col-lg-4">*<b>College</b></label>
                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                <?php if(@$validation && $validation->hasError('faculty_division')){ ?>
                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('faculty_division'); ?></label></span>
                                                                <?php } ?>
                                                                <select class="form-control m-bot15" name="faculty_division" id="faculty_division" style="padding-left: 3px;" required>
                                                                    <option value=''>Select...</option>
                                                                    <?php $faculty_division_post = set_value('faculty_division') ? set_value('faculty_division') : @$basic[0]->faculty_division; ?>
                                                                    <option value='no' <?php echo $faculty_division_post=='no' || (@$basic && $basic[0]->faculty_division=='') ? 'selected' : ''; ?>>--no college</option>
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
                                                            <label class="col col-lg-4">*<b>Department</b></label>
                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                <?php if(@$validation && $validation->hasError('faculty_unit')){ ?>
                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('faculty_unit'); ?></label></span>
                                                                <?php } ?>
                                                                <select class="form-control m-bot15" name="faculty_unit" id="faculty_unit" style="padding-left: 3px;" required>
                                                                    <option value=''>Select...</option>
                                                                    <?php $faculty_unit_post = set_value('faculty_unit') ? set_value('faculty_unit') : @$basic[0]->faculty_unit; ?>
                                                                    <option value='no' <?php echo $faculty_unit_post=='no' || (@$basic && $basic[0]->faculty_unit==0) ? 'selected' : ''; ?>>--no department</option>
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

                                                        <div class="row form-fields" id="faculty_subunit_div">
                                                            <label class="col col-lg-4">*<b>Sub-Unit</b></label>
                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                <?php if(@$validation && $validation->hasError('faculty_subunit')){ ?>
                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('faculty_subunit'); ?></label></span>
                                                                <?php } ?>
                                                                <select class="form-control m-bot15" name="faculty_subunit" id="faculty_subunit" style="padding-left: 3px;" required>
                                                                    <option value=''>Select...</option>
                                                                    <?php $faculty_subunit_post = set_value('faculty_subunit') ? set_value('faculty_subunit') : @$basic[0]->faculty_subunit; ?>
                                                                    <option value='no' <?php echo $faculty_subunit_post=='no' || (@$basic && $basic[0]->faculty_subunit==0) ? 'selected' : ''; ?>>--no subunit</option>
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

                                                <div class="row mb-4 mt-3">
                                                    <div class="col-lg-4"></div>
                                                    <div class="col-lg-8 <?php echo @$validation && $validation->hasError('with_assignment') ? 'has-error has-feedback' : ''; ?>">
                                                        <div class="form-check">
                                                                <?php if($request->getPost()){ 
                                                                    $with_assign_post = set_checkbox('with_assignment', '1')==true ? 1 : 0;
                                                                } else if(@$basic[0]->with_assignment!=null){ 
                                                                    $with_assign_post = @$basic[0]->with_assignment;
                                                                } else { 
                                                                    $with_assign_post = 0;
                                                                } ?>
                                                                <input class="form-check-input form-check-input-lg" type="checkbox" name="with_assignment" value="1" id="with_assignment" <?= $with_assign_post==1 ? 'checked' : ''; ?>>
                                                                <label class="form-check-label w-100" for="with_assignment">
                                                                    With Office assignment/designation
                                                                </label>
                                                        </div>

                                                    </div>
                                                </div>
                                    
                                        <?php } ?>

                                        
                                        <div id="assignment_div" class="mt-5" style="<?php echo (@$basic[0]->emp_class==2 && $with_assign_post==1) || @$basic[0]->emp_class==1 ? '' : 'display:none'; ?>">
                                            
                                            <?php if( $emp_class==2){ ?>
                                                    <h4 class="mb-4">Office designation</h4>
                                            <?php } ?>
                                        
                                                                                    <!------program----->
                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">*<b>Program</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <input type="hidden" id="emp_program" value="<?php echo @$basic[0]->emp_office; ?>">
                                                                                                <?php if(@$validation && $validation->hasError('emp_office')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_office'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_office" id="emp_office" style="padding-left: 3px;" <?= @$basic[0]->emp_class_name=='STAFF' ? 'required' : ''; ?>>
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

                                                                                    <!------division----->
                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">*<b>College / Division / Office</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_division')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_division'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_division" id="emp_division" style="padding-left: 3px;"  <?= @$basic[0]->emp_class_name=='STAFF' ? 'required' : ''; ?>>
                                                                                                    <option value=''>Select...</option>
                                                                                                    <?php $division_post = set_value('emp_division') ? set_value('emp_division') : @$basic[0]->emp_division; ?>
                                                                                                    <option value='no' <?php echo $division_post=='no' || (@$basic && $basic[0]->emp_division=='') ? 'selected' : ''; ?>>--no division</option>
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

                                                                                    
                                                                                    <!------unit----->
                                                                                        <div class="row form-fields">
                                                                                            <label class="col col-lg-4">*<b>Department / Unit / Section</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_unit')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_unit'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_unit" id="emp_unit" style="padding-left: 3px;"  <?= @$basic[0]->emp_class_name=='STAFF' ? 'required' : ''; ?>>
                                                                                                    <option value=''>Select...</option>
                                                                                                    <?php $unit_post = set_value('emp_unit') ? set_value('emp_unit') : @$basic[0]->emp_unit; ?>
                                                                                                    <option value='no' <?php echo $unit_post=='no' || (@$basic && $basic[0]->emp_unit==0) ? 'selected' : ''; ?>>--no unit</option>
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

                                                                                    <!------sub-unit----->
                                                                                        <div class="row form-fields" id="emp_subunit_div">
                                                                                            <label class="col col-lg-4">*<b>Sub-Unit</b></label>
                                                                                            <div class="col col-lg-8 mb-2 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('emp_subunit')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_subunit'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <select class="form-control m-bot15" name="emp_subunit" id="emp_subunit" style="padding-left: 3px;"  <?= @$basic[0]->emp_class_name=='STAFF' ? 'required' : ''; ?>>
                                                                                                    <option value=''>Select...</option>
                                                                                                    <?php $subunit_post = set_value('emp_subunit') ? set_value('emp_subunit') : @$basic[0]->emp_subunit; ?>
                                                                                                    <option value='no' <?php echo $subunit_post=='no' || (@$basic && $basic[0]->emp_subunit==0) ? 'selected' : ''; ?>>--no subunit</option>
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
                                                                                                <?php if(@$validation && $validation->hasError('file_upload')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('file_upload'); ?></label></span>
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
                            abbr : "<?php echo @$off->office_abbr!='' ? ' ('.@$off->office_abbr.')' : ''; ?>",
                            <?php if(@$off->divisions){ ?>
                                subcategories: {
                                    <?php foreach($off->divisions as $div){ ?>
                                        '<?php echo $div->id_division; ?>' : {
                                            label : "<?php echo $div->division_name; ?>",
                                            abbr : "<?php echo @$div->division_abbr!='' ? ' ('.$div->division_abbr.')' : ''; ?>",
                                            <?php if(@$div->units){ ?>
                                                items: {
                                                
                                                    <?php foreach($div->units as $unit){ ?>
                                                        '<?php echo $unit->id_unit; ?>' : {
                                                            
                                                            label : "<?php echo $unit->unit_name; ?>", 
                                                            abbr : "<?php echo @$unit->unit_abbr!='' ? ' ('.$unit->unit_abbr.')' : ''; ?>",
                                                            <?php if(@$unit->subunits){ ?>
                                                
                                                                    subitems: [

                                                                        <?php foreach(@$unit->subunits as $sub){ ?>
                                                                            { 
                                                                                value: '<?php echo $sub->id_subunit; ?>', 
                                                                                label : "<?php echo $sub->subunit_name; ?>", 
                                                                                abbr : "<?php echo @$sub->subunit_abbr!='' ? ' ('.$sub->subunit_abbr.')' : ''; ?>",
                                                                            },
                                                                        <?php } ?>,

                                                                    ]
                                                
                                                            <?php } ?>
                                                        },
                                                    <?php } ?>
                                                
                                                }
                                            <?php } ?>
                                        },
                                    <?php } ?>
                                
                                }
                            <?php } ?>
                        },

                    <?php } ?>
                };
                
                
                    console.log(data);

                // Populate first dropdown
                $.each(data, function(categoryKey, categoryData) {
                    $("#emp_office").append(`<option value="${categoryKey}">${categoryData.label}${categoryData.abbr}</option>`);
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
                            emp_division.append(`<option value="${subcategoryKey}">${subcategoryData.label}${subcategoryData.abbr}</option>`);
                        });
                    }
                    emp_division.val('no');
                    emp_unit.val('no');
                    $("#emp_subunit_div").val('no');
                    $("#emp_subunit_div").slideUp();
                });

                // Populate third dropdown based on second dropdown selection
                $("#emp_division").change(function() {

                    console.log(data);


                    const selectedCategory = $("#emp_office").val();
                    const selectedSubcategory = $(this).val();
                    const emp_unit = $("#emp_unit");

                    emp_unit.empty().append('<option value="">--Select--</option>'); // Reset
                    emp_unit.append('<option value="no" selected>(no unit)</option>'); 

                    if (selectedCategory && selectedSubcategory && data[selectedCategory].subcategories[selectedSubcategory]) {
                        if(data[selectedCategory].subcategories[selectedSubcategory].items){
                            //alert(data[selectedCategory].subcategories[selectedSubcategory].items)
                            $.each(data[selectedCategory].subcategories[selectedSubcategory].items, function(index, item) {
                                try {
                                    emp_unit.append(`<option value="${index}">${item.label}${item.abbr}</option>`);
                                    
                                } catch(error){}
                            });
                        }
                    }

                    //if($(this).val()=='no'){
                    //    emp_unit.val('no')
                    //}
                    $("#emp_subunit_div").slideUp();

                });
                
                
                // Populate fourth dropdown based on second dropdown selection
                $("#emp_unit").change(function() {
                    const selectedCategory = $("#emp_office").val();
                    const selectedSubcategory = $("#emp_division").val();
                    const selectedUnit = $(this).val();
                    const emp_subunit = $("#emp_subunit");
                    

                    emp_subunit.empty().append('<option value="">--Select--</option>'); // Reset
                    emp_subunit.append('<option value="no" selected>(no unit)</option>'); 


                    //var subcats = data[selectedCategory].subcategories[selectedSubcategory].items;
                            
                            //alert('a')
                    if (selectedCategory && selectedSubcategory && selectedUnit && data[selectedCategory].subcategories[selectedSubcategory].items) {
                        
                            //alert('b')
                        try {
                            if(data[selectedCategory].subcategories[selectedSubcategory].items[selectedUnit].subitems){

                                //alert('c')
                                //alert(data[selectedSubcategory].items[selectedSubcategory].subitems)
                                $("#emp_subunit_div").slideDown();

                                $.each(data[selectedCategory].subcategories[selectedSubcategory].items[selectedUnit].subitems, function(index, item) {
                                    try {
                                        emp_subunit.append(`<option value="${item.value}">${item.label}${item.abbr}</option>`);

                                    } catch(error){}
                                });
                            } else {
                                $("#emp_subunit_div").slideUp();
                            }
                        } catch(error){}
                    }

                    //if($(this).val()=='no'){
                    //    emp_unit.val('no')
                    //}

                });
                
                
                init_load_offices();
                
                function init_load_offices()
                {
                    var emp_program = $('#emp_program').val();
                    if(emp_program!=''){
                            $("#emp_office").val(emp_program).trigger("change");
                            
                            var set_division = '<?php echo @$basic[0]->emp_division; ?>';
                                
                            if(set_division!=''){
                                
                                $("#emp_division").val(set_division).trigger("change");
                                var set_unit = '<?php echo @$basic[0]->emp_unit; ?>';
                                $("#emp_unit").val(set_unit).trigger("change");
                                var set_subunit = '<?php echo @$basic[0]->emp_subunit; ?>';
                                
                                $("#emp_unit").val(set_unit).trigger("change");
                                if(set_subunit!=''){
                                    $("#emp_subunit_div").slideDown();
                                } else {
                                    $("#emp_subunit_div").slideUp();
                                }
                                
                                
                            }
                            
                    
                    }
                }

                
//-----------------------------------------------------------------
//-----------------------------------------------------------------


                
                // Data structure: Categories -> Subcategories -> Items
                const data2 = {
                    <?php foreach($ovpaa_offices as $ovpaa){ ?>
                        '1' : {
                            label : "<?php echo $ovpaa->office_name; ?>",
                            abbr : "<?php echo @$ovpaa->office_abbr!='' ? ' ('.@$ovpaa->office_abbr.')' : ''; ?>",
                            <?php if(@$ovpaa->divisions){ ?>
                                subcategories: {
                                    <?php foreach($ovpaa->divisions as $col){ ?>
                                        '<?php echo $col->id_division; ?>' : {
                                            label : "<?php echo $col->division_name; ?>",
                                            abbr : "<?php echo @$col->division_abbr!='' ? ' ('.$col->division_abbr.')' : ''; ?>",
                                            <?php if(@$col->units){ ?>
                                                items: {
                                                
                                                    <?php foreach($col->units as $dept){ ?>
                                                        '<?php echo $dept->id_unit; ?>' : {
                                                            
                                                            label : "<?php echo $dept->unit_name; ?>", 
                                                            abbr : "<?php echo @$dept->unit_abbr!='' ? ' ('.$dept->unit_abbr.')' : ''; ?>",
                                                            <?php if(@$dept->subunits){ ?>
                                                
                                                                    subitems: [

                                                                        <?php foreach(@$dept->subunits as $sub2){ ?>
                                                                            { 
                                                                                value: '<?php echo $sub2->id_subunit; ?>', 
                                                                                label : "<?php echo $sub2->subunit_name; ?>", 
                                                                                abbr : "<?php echo @$sub2->subunit_abbr!='' ? ' ('.$sub2->subunit_abbr.')' : ''; ?>",
                                                                            },
                                                                        <?php } ?>,

                                                                    ]
                                                
                                                            <?php } ?>
                                                        },
                                                    <?php } ?>
                                                
                                                }
                                            <?php } ?>
                                        },
                                    <?php } ?>
                                
                                }
                            <?php } ?>
                        },

                    <?php } ?>
                };
                
                // Populate first dropdown
                $.each(data2[1].subcategories, function(subcategoryKey, subcategoryData) {
                    $("#faculty_division").append(`<option value="${subcategoryKey}">${subcategoryData.label}${subcategoryData.abbr}</option>`);
                });


                // Populate third dropdown based on second dropdown selection
                $("#faculty_division").change(function() {

                    const selectedCategory = 1;
                    const selectedSubcategory = $(this).val();
                    const faculty_unit = $("#faculty_unit");

                    faculty_unit.empty().append('<option value="">--Select unit--</option>'); // Reset
                    faculty_unit.append('<option value="no" selected>(no unit)</option>'); 

                    if (selectedCategory && selectedSubcategory && data2[selectedCategory].subcategories[selectedSubcategory]) {
                        if(data2[selectedCategory].subcategories[selectedSubcategory].items){
                            //alert(data2[selectedCategory].subcategories[selectedSubcategory].items)
                            $.each(data2[selectedCategory].subcategories[selectedSubcategory].items, function(index, item) {
                                try {
                                    faculty_unit.append(`<option value="${index}">${item.label}${item.abbr}</option>`);
                                    
                                } catch(error){}
                            });
                        }
                    }
                    
                    $("#faculty_subunit_div").slideUp();

                });
                
                
                // Populate fourth dropdown based on second dropdown selection
                $("#faculty_unit").change(function() {
                    const selectedCategory = 1;
                    const selectedSubcategory = $("#faculty_division").val();
                    const selectedUnit = $(this).val();
                    const faculty_subunit = $("#faculty_subunit");
                    
                    faculty_subunit.empty().append('<option value="">--Select sub-unit--</option>'); // Reset
                    faculty_subunit.append('<option value="no" selected>(no sub-unit)</option>'); 

                            //alert('a')
                    if (selectedCategory && selectedSubcategory && selectedUnit && data2[selectedCategory].subcategories[selectedSubcategory].items) {
                        
                            //alert('b')
                        try {
                            if(data2[selectedCategory].subcategories[selectedSubcategory].items[selectedUnit].subitems){

                                //alert('c')
                                //alert(data2[selectedSubcategory].items[selectedSubcategory].subitems)
                                $("#faculty_subunit_div").slideDown();

                                $.each(data2[selectedCategory].subcategories[selectedSubcategory].items[selectedUnit].subitems, function(index, item) {
                                    try {
                                        faculty_subunit.append(`<option value="${item.value}">${item.label}${item.abbr}</option>`);

                                    } catch(error){}
                                });
                            } else {
                                $("#faculty_subunit_div").slideUp();
                            }
                        } catch(error){}
                    }

                    //if($(this).val()=='no'){
                    //    faculty_unit.val('no')
                    //}

                });
                
                
                init_vpaa_offices();
                
                function init_vpaa_offices()
                {
                    var set_faculty_division = '<?php echo set_value('faculty_division') ? set_value('faculty_division') : @$basic[0]->faculty_division; ?>';

                    if(set_faculty_division!=''){

                        $("#faculty_division").val(set_faculty_division).trigger("change");
                        var set_faculty_unit = '<?php echo set_value('faculty_unit') ? set_value('faculty_unit') : @$basic[0]->faculty_unit; ?>';
                        $("#faculty_unit").val(set_faculty_unit).trigger("change");
                        var set_faculty_subunit = '<?php echo set_value('faculty_subunit') ? set_value('faculty_subunit') : @$basic[0]->faculty_subunit; ?>';

                        $("#faculty_unit").val(set_faculty_unit).trigger("change");
                        if(set_faculty_subunit!=''){
                            $("#faculty_subunit_div").slideDown();
                        } else {
                            $("#faculty_subunit_div").slideUp();
                        }

                    }
                            
                }
                
                $('#with_assignment').on('change',function(){
                    if($(this).is(':checked')){
                        //alert('checked')
                        $('#assignment_div').slideDown();
                        
                        $("#emp_office").prop('required',true);
                        $("#emp_division").prop('required',true);
                        $("#emp_unit").prop('required',true);
                        $("#emp_subunit").prop('required',true);
                    } else {
                        
                        
                        $('#assignment_div').slideUp();
                        $("#emp_office").removeAttr('required');
                        $("#emp_division").removeAttr('required');
                        $("#emp_unit").removeAttr('required');
                        $("#emp_subunit").removeAttr('required');
                        
                        
                    }
                });
                
//-----------------------------------------------------------------
//-----------------------------------------------------------------

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

