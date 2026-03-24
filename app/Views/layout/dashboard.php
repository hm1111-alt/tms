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
                /*background-color: #007B2E !important;*/
                background-color: #f5c850 !important;
                color: white;
            }
    
            .memo_div.active {
/*                background-color: #009638;*/
/*                background-color: #ffd700;*/
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
                /*color: #009638;*/
                color: #009638;
                font-weight: 500;
            }
            
            div.leave_credits div div {
                border-radius: 5px;
                /*background-color: #8D5116;*/
                background-color: #007b3e;
                color: white;
                font-size: 2.3rem;
                margin-right:5px;
                width: 100%;
                font-weight: normal;
            }
        
        </style>
        
</head>

<body <?php echo session()->get('password_reset')==1 ? 'class="sb-nav-fixed modal-open" style="overflow: hidden; padding-right: 15px;"' : 'class="sb-nav-fixed"';  ?>>
        
        <?= $this->include('layout/navbar') ?>
        
        <div id="layoutSidenav">
            
    
                <?= $this->include('layout/sidebar') ?>


                <div id="layoutSidenav_content">

                        <div class="container-fluid px-3">

                                <div class="row " style="background-color: white; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                                        <div class="col-xl-8">
                                            <h1 class="mt-4 mb-2">
                                                Dashboard
                                            </h1>

                                            <ol class="breadcrumb mb-3">
                                                <li class="breadcrumb-item">
                                                    &nbsp;Hi <?= session()->get('first_name'); ?>! Welcome to Employee Portal version 2.
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

                                    
                                        <div class="row">

                                                <div class="col col-lg-3 col-md-4 mb-4">

                                                        <div class="card col-lg-12 mb-4">

                                                                <div class="card-body">

                                                                        <!--<h2 class="h2-class ">My Profile</h2>-->

                                                                        <div class="form-group mb-4 mt-2 px-4"  style="text-align: center;">

                                                                        <!--------------------------------------------------------->

                                                                                <div id="profilepix">


                                                                                        <?php 
                                                                                        if($_SERVER['REMOTE_ADDR']=='::1'){
                                                                                                if(@$basic[0]->profile_picture_loc=='portal' || @$basic[0]->profile_picture_loc==''){
                                                                                                        $filedir = 'http://localhost/employeeportal/public/assets/images/profiles/'.@$basic[0]->profile_picture;
                                                                                                } else {
                                                                                                        $filedir = 'http://localhost/hrmisv2/public/assets/images/profiles/'.@$basic[0]->profile_picture;
                                                                                                }
                                                                                        } else {
                                                                                                if(@$basic[0]->profile_picture_loc=='portal' || @$basic[0]->profile_picture_loc==''){
                                                                                                        $filedir = 'https://e-portal2.clsu.edu.ph/public/assets/images/profiles/'.@$basic[0]->profile_picture; 
                                                                                                } else {
                                                                                                        $filedir = 'https://hrmis2.clsu.edu.ph/public/assets/images/profiles/'.@$basic[0]->profile_picture; 
                                                                                                }
                                                                                        } ?>

                                                                                        <?php if(@file_get_contents($filedir) && @$basic[0]->profile_picture!=''){ ?>

                                                                                                <img src="<?php echo $filedir; ?>" alt="">

                                                                                        <?php } else { ?>

                                                                                                <img src="<?php echo @$basic[0]->emp_sex=='Male' ? images('profiles/profile-man.png') : images('profiles/profile-woman.png'); ?>" alt="">

                                                                                        <?php } ?>


                                                                                        <a title="Update Profile Picture" style="cursor: pointer;"  data-bs-toggle="modal" data-bs-target="#picture_modal">
                                                                                            <div id="change_dp_btn">
                                                                                                <div class="">
                                                                                                    <i class="fa-solid fa-camera"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </a>
                                                                                </div>
                                                                        

                                                                                <div class="row">
                                                                                    <label class=" col col-lg-12" style="font-size: larger; font-weight: bold;">
                                                                                        <?php echo @$basic[0]->emp_fullname; ?>
                                                                                    </label>
                                                                                </div>
                                                                        
                                                                                <div class="row">
                                                                                    <label class=" col col-lg-12">
                                                                                        <?php //echo @$basic[0]->emp_idno; ?>
                                                                                        <?php echo strtoupper(@$basic[0]->emp_position_name); ?>
                                                                                    </label>
                                                                                </div>
                                                                        
                                                                                <div class="row">
                                                                                    <label class=" col col-lg-12">
                                                                                        <?php //echo @$basic[0]->emp_idno; ?>
                                                                                        <?php echo @$basic[0]->emp_class_name; ?>
                                                                                        <input type="hidden" id="class_name" value="<?php echo @$basic[0]->emp_class_name; ?>">
                                                                                    </label>
                                                                                </div>
                                                                        
                                                                                <div class="row">
                                                                                    <label class=" col col-lg-12">
                                                                                        <?php echo @$basic[0]->emp_email_official; ?>
                                                                                    </label>
                                                                                </div>
                                                                        
                                                                                <?php if(@$basic[0]->emp_email_personal){ ?>
                                                                                    <div class="row">
                                                                                        <label class=" col col-lg-12">
                                                                                            <?php echo @$basic[0]->emp_email_personal; ?>
                                                                                        </label>
                                                                                    </div>
                                                                                <?php } ?>
                                                                                    
                                                                                
                                                                        </div>
                                                                </div>
                                                        </div>


                                                        <div class="card mb-4 col-lg-12">
                                                                <div class="card-header bg-success text-white" style="background-color: #8D5116;">
                                                                    <i class="fas fa-briefcase"></i> Employment
                                                                </div>

                                                                <div class="card-body " style="overflow: hidden;">

                                                                        <div class="form-group mb-4 px-1"  style="">
                                                                        
                                                                                <div class="row mt-1">
                                                                                    <label class=" col col-lg-4">ID no.</label>
                                                                                    <label class=" col col-lg-8">
                                                                                        <b><?php echo @$basic[0]->emp_idno; ?></b>
                                                                                    </label>
                                                                                </div>
                                                                        
                                                                                <div class="row mt-1">
                                                                                    <label class=" col col-lg-4">Original date of appt.</label>
                                                                                    <label class=" col col-lg-8">
                                                                                        <b><?php echo @$basic[0]->emp_date_hired!='' ? date('F j, Y',strtotime(@$basic[0]->emp_date_hired)) : '---'; ?></b>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="row mt-4">
                                                                                    <label class=" col col-lg-4">Program</label>
                                                                                    <label class=" col col-lg-8">
                                                                                        <b><?php echo @$basic[0]->office_name; 
                                                                                            echo @$basic[0]->office_abbr!='' ? ' ('.@$basic[0]->office_abbr.')' : ''; 
                                                                                            ?>
                                                                                        </b>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="row mt-2">
                                                                                    <label class=" col col-lg-4">College / Division / Office</label>
                                                                                    <label class=" col col-lg-8">
                                                                                        <b><?php echo @$basic[0]->division_name; 
                                                                                            echo @$basic[0]->division_abbr!='' ? ' ('.@$basic[0]->division_abbr.')' : ''; 
                                                                                            ?>
                                                                                        </b>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="row mt-2">
                                                                                    <label class=" col col-lg-4">Department / Unit / Section</label>
                                                                                    <label class=" col col-lg-8">
                                                                                        <b><?php echo @$basic[0]->unit_name; 
                                                                                            echo @$basic[0]->unit_abbr!='' ? ' ('.@$basic[0]->unit_abbr.')' : ''; 
                                                                                            ?>
                                                                                        </b>
                                                                                    </label>
                                                                                </div>

                                                                        </div>
                                                                    
                                                                    <hr>
                                                                    
                                                                        <table class="table w-100" style="font-weight: 500;">
                                                                            <tr>
                                                                                <td style="width:65%;">Personal Data Sheet</td>
                                                                                <td>
                                                                                    <!--<a href="https://e-portal.clsu.edu.ph/"  target="_blank">-->
                                                                                    <?php $essp_url = $_SERVER['REMOTE_ADDR']=='::1' ? 'http://localhost/ESSP/index_1.php' : 'https://e-portal.clsu.edu.ph/index_1.php'; ?>
                                                                                    <form action="<?= $essp_url ?>" method="post" target="_blank">
                                                                                        <input type="hidden" name="to_access" value="pds">
                                                                                        <input type="hidden" name="empid" value="<?= $empidno; ?>">
                                                                                        <?php
                                                                                        $sessionConfig = config('Session');
                                                                                        $cookieName = $sessionConfig->cookieName;
                                                                                        ?>
                                                                                        <input type="hidden" name="sessid" value="<?= $cookieName.':'.session_id(); ?>">
                                                                                        <button class="btn btn-xs w-100 btn-warning-gold text-white" name="portal" type="submit" style="/*background-color: #8D5116;*/ text-align: center; display: inline-block">
                                                                                            View
                                                                                        </button>
                                                                                    </form>
                                                                                    
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="width:65%;">Payslip</td>
                                                                                <td>
                                                                                    <a onclick="alert('This feature is under development.')">
                                                                                        <button class="btn btn-xs w-100 btn-warning-gold text-white" type="button" disabled style="/*background-color: #8D5116;*/ text-align: center; display: inline-block">
                                                                                            View
                                                                                        </button>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                </div>
                                                        </div>
                                                </div>

                                                <div class="col col-lg-4 mb-4">
                                                    

                                                        <?= $this->include('layout/dashboard/leave_credits'); ?>
                                                    
                                                        <?= $this->include('layout/dashboard/quick_links'); ?>



                                                        <div class="card mb-4 col-lg-12">
                                                                <div class="card-header bg-success text-white" style="">
                                                                    <i class="fas fa-download"></i> Downloads
                                                                        <!--<button class="btn btn-xs btn-light " type="button" style="float: right;">
                                                                            <i class="fas fa-right-to-bracket"></i> View all systems
                                                                        </button>-->
                                                                </div>

                                                                <div class="card-body " style="overflow: hidden;">
                                        
                                                                    <table class="table w-100" style="font-size: 1.2rem; font-weight: 500;">
                                                                        <tr>
                                                                            <td style="width:100%;">
                                                                                <a href="https://drive.google.com/drive/folders/1KcVi_vrK7LE2_4FEBdOtDibyPSE_zGE9?usp=drive_link" target="_blank">
                                                                                    Google drive
                                                                                </a> for @clsu.edu.ph account
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="width:100%;">
                                                                                <a href="https://drive.google.com/drive/folders/1Ao3UcRC00jPEfcDGOKZ-BcfYyD1WQgLU?usp=drive_link" target="_blank">
                                                                                    Google drive
                                                                                </a> for @clsu2.edu.ph account
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                        </div>
                                                    
                                                        <!--<div class="card mb-4 col-lg-12">

                                                                <div class="card-header bg-success text-white">
                                                                    <i class="fas fa-bullhorn"></i> Bulletin
                                                                </div>
                                                            
                                                                <div class="card-body">

                                                                        <div class="form-group  mb-4 mt-4 px-4" style="overflow: hidden;">
                                                                        </div>

                                                                </div>
                                                            
                                                        </div>-->
                                                </div>

                                                <div class="col col-lg-5 mb-4">
                                                        <div class="card mb-4 col-lg-12">
                                                            <?= $this->include('layout/dashboard/memos'); ?>
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

<?= $this->include('profile/modal_picture_form') ?>


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


                //$("#grid_GridHeader:first")

                $('.memo_div').on('click',function(){
                    $('.memo_div').removeClass( "active" )
                    $(this).addClass( "active" )
                    var thisid = $(this).attr('id');
                    load_memo(thisid);
                    
                });
                
                load_memo($(".memo_div")[0].id);
                
                function load_memo(memo_id)
                {
                    var post_data={};
                    post_data['memo_id'] = memo_id;
                    $.ajax({
                            url: '<?php echo site_url('get_memo') ?>',
                            type: 'POST',
                            data: post_data,
                            success:function(result){
                                    $("#memo_preview_div").attr("src",result);
                            }
                    });
                }
                
                load_leave_credits();
                
                function load_leave_credits()
                {
                    var post_data={};
                    $.ajax({
                            url: '<?php echo site_url('get_my_credits') ?>',
                            type: 'POST',
                            data: post_data,
                            success:function(result){
                                var json = $.parseJSON(result);
                                $(json).each(function(i,val){
                                    if(parseFloat(val.vl)>0){
                                        $('#credit_vl').html(val.vl);
                                    } else {
                                        $('#credit_vl').html('-');
                                    }
                                    if(parseFloat(val.sl)>0){
                                        $('#credit_sl').html(val.sl);
                                    } else {
                                        $('#credit_sl').html('-');
                                    }
                                    
                                    if($('#class_name').val()=='FACULTY'){
                                        $('#credit_spl_div').hide();
                                        $(".leave_credits_div").removeClass("col-lg-3");
                                        $(".leave_credits_div").addClass("col-lg-4");
                                    } 
                                        
                                    if(parseFloat(val.slp)>0){
                                        var spl = parseFloat(val.slp);
                                        var spl2 = spl.toFixed(1);
                                        $('#credit_spl').html(spl2);
                                    } else {
                                        $('#credit_spl').html('-');
                                    }
                                    
                                    
                                    if(parseFloat(val.service)>0){
                                        $('#credit_service').html(val.service);
                                    } else if($('#class_name').val()=='STAFF') {
                                        $('#credit_service').html('-');
                                        $('#credit_service_div').hide();
                                        $(".leave_credits_div").removeClass("col-lg-3");
                                        $(".leave_credits_div").addClass("col-lg-4");
                                    } else {
                                        $('#credit_service').html('-');
                                    }
                                    $('#asof').html('as of ' + val.asof_date);
                                });
                            }
                    });
                }
                
                
                
                
        });

    </script>

