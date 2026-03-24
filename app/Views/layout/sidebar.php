<?php

// Get the request service
$request = \Config\Services::request();

?>

            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    
                    <table border="0" cellpadding="0" cellspacing="0" style="">
                        <tr>
                            <td style="width:32%;">

                                    <div class="profile_pic">
                                        <?php 
                                        if($_SERVER['REMOTE_ADDR']=='::1'){
                                            if(session()->get('profile_loc')=='portal' || session()->get('profile_loc')==''){
                                                $filedir = 'http://localhost/employeeportal/public/assets/images/profiles/thumbs/'.session()->get('profile_icon');
                                            } else {
                                                $filedir = 'http://localhost/hrmisv2/public/assets/images/profiles/thumbs/'.session()->get('profile_icon');
                                            }
                                        } else {
                                            if(session()->get('profile_loc')=='portal' || session()->get('profile_loc')==''){
                                                $filedir = 'https://e-portal2.clsu.edu.ph/public/assets/images/profiles/thumbs/'.session()->get('profile_icon'); 
                                            } else {
                                                $filedir = 'https://hrmis2.clsu.edu.ph/public/assets/images/profiles/thumbs/'.session()->get('profile_icon'); 
                                            }
                                        } ?>

                                        <?php if(@file_get_contents($filedir) && session()->get('profile_icon')!=''){ ?>

                                            <img src="<?php echo $filedir; ?>" alt="Profile"class="img-circle profile_img">

                                        <?php } ?>

                                    </div>
                                
                            </td>
                            <td style="width:65%;">

                                    <div class="profile_info">
                                        <span>Welcome,</span>
                                        <h2><?php if(session()->get('nickname')!=''){
                                                echo session()->get('nickname');
                                            } else if(session()->get('first_name')==''){
                                                echo session()->get('username');
                                            } else {
                                                echo session()->get('first_name').' '.session()->get('last_name');
                                                echo session()->get('ext_name')!='' ? ' '. session()->get('ext_name') : '';
                                            } ?>
                                        </h2>
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                
                                    <?php if(session()->get('earned_monet')){ ?>
                                        <div id="sidebar_monet_div" style="padding-left:15px; padding-right: 10px; margin-top:10px; display:block;">
                                            <span class="text-light" style="font-size: 18px; font-weight: bold;"> <!-- yellow #FFC330 #FFB600 --->
                                                &#8369; <span id="sidebar_monet_amount"><?php echo session()->get('earned_monet'); ?></span>
                                            </span>
                                            <br>
                                            <span class="text-warning" style=" font-size: 11px; font-weight: normal;">
                                                <!--Your earned balance monetary equivalent as of this day.-->
                                                Your earned leave balance, converted to monetary value, as of today.
                                            </span>
                                        </div>
                                    <?php } ?>
                            </td>
                        </tr>
                    </table>
                    
                    
                    <div class="sb-sidenav-menu pt-4">
                        <div class="nav">
                            <!--<div class="sb-sidenav-menu-heading">Core</div>-->
                            <a class="nav-link <?= $request->uri->getSegment(1)=='dashboard' ? 'active' : ''; ?>" href="<?= site_url('dashboard') ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            
                            <a class="nav-link <?= $request->uri->getSegment(1)=='myprofile' ? 'active' : ''; ?>" href="<?= site_url('myprofile') ?>" >
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                My Profile 
                            </a>
                                
                            <?php $essp_url = $_SERVER['REMOTE_ADDR']=='::1' ? 'http://localhost/ESSP/index_1.php' : 'https://e-portal.clsu.edu.ph/index_1.php'; ?>
                            <!--<form action="https://e-portal.clsu.edu.ph/index_1.php" method="post" target="_blank">-->
                            <form action="<?= $essp_url ?>" method="post" target="_blank">
                                <input type="hidden" name="to_access" value="profile">
                                <input type="hidden" name="empid" value="<?= session()->get('portalid'); ?>">
                                <?php $sessionConfig = config('Session');
                                $cookieName = $sessionConfig->cookieName; ?>
                                <input type="hidden" name="sessid" value="<?= $cookieName.':'.session_id(); ?>">
                                <button class="nav-link " type="submit" style="background: none; border: none;" title="Go to e-Portal v1">
                                        <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                                        e-Portal v1
                                </button>
                            </form>
                            
                            <a class="nav-link <?= $request->uri->getSegment(1)=='executive' ? 'active' : ''; ?>" href="<?= site_url('executive') ?>" >
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Executive
                            </a>
                            
                            <?php /*
                            <a class="nav-link <?= $request->uri->getSegment(1)=='portal' ? 'active' : ''; ?>" href="<?= site_url('myprofile') ?>" >
                                <div class="sb-nav-link-icon"><i class="fas fa-right-to-bracket"></i></div>
                                All systems
                            </a>
                             */ ?>
                            
                            <?php if(session()->get('access_level')==1){  ?>
                                    <div class="sb-sidenav-menu-heading">Admin access</div>
                                    <a class="nav-link <?= $request->uri->getSegment(1)=='records' ? 'active' : ''; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="true" aria-controls="collapseLayouts">
                                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                        HR Records
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                        <div class="collapse show" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                            <nav class="sb-sidenav-menu-nested nav">
                                                <a class="nav-link <?= $request->uri->getSegment(2)=='employees' || $request->uri->getSegment(1)=='employees' ? 'active' : ''; ?>" href="<?= site_url('employees'); ?>">Employees</a>
                                            </nav>
                                        </div>
                            <?php } ?>
                            
                            
                            
                            <div class="sb-sidenav-menu-heading">Human Resource</div>
                                <?php $set_attendance = 0;
                                if($request->uri->getSegment(1)=='attendance'
                                        || $request->uri->getSegment(2)=='holidays' || $request->uri->getSegment(1)=='holidays'
                                        || $request->uri->getSegment(2)=='leaves' || $request->uri->getSegment(1)=='leaves'
                                        || $request->uri->getSegment(2)=='credits' || $request->uri->getSegment(1)=='credits'
                                        || $request->uri->getSegment(2)=='dtr' || $request->uri->getSegment(1)=='dtr'
                                        || $request->uri->getSegment(2)=='travelorders' || $request->uri->getSegment(1)=='travelorders'
                                        ){
                                    $set_attendance = 1;
                                }
                                ?>

                                <a class="nav-link <?= $set_attendance==1 ? 'active' : ''; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="true" aria-controls="collapseLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-days"></i></div>
                                    Attendance
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                    <div class="collapse <?= $set_attendance==1 ? 'show' : ''; ?>" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link <?= $request->uri->getSegment(2)=='holidays' || $request->uri->getSegment(1)=='holidays' ? 'active' : ''; ?>" href="<?= site_url('holidays'); ?>">Holidays</a>
                                            <a class="nav-link <?= $request->uri->getSegment(2)=='travelorders' || $request->uri->getSegment(1)=='travelorders' ? 'active' : ''; ?>" href="<?= site_url('travelorders'); ?>">Travel Order</a>
                                            <a class="nav-link <?= $request->uri->getSegment(2)=='dtr' || $request->uri->getSegment(1)=='dtr' ? 'active' : ''; ?>" href="<?= site_url('dtr'); ?>">DTR</a>
                                        </nav>
                                    </div>
                            
                                <a class="nav-link <?= $request->uri->getSegment(1)=='payroll' ? 'active' : ''; ?>" href="<?= site_url('payroll') ?>" >
                                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                    Payroll
                                </a>
                            
                            
                            
                            <div class="sb-sidenav-menu-heading">Research & Extension</div>
                                <a class="nav-link <?= $request->uri->getSegment(1)=='research' ? 'active' : ''; ?>" href="<?= site_url('research') ?>" >
                                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                    RADIIS
                                </a>
                            
                            <div class="sb-sidenav-menu-heading">Service Requests</div>
                                <a class="nav-link <?= $request->uri->getSegment(1)=='services' ? 'active' : ''; ?>" href="<?= site_url('services') ?>" >
                                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                    Services
                                </a>
                            
                            <!--<div class="sb-sidenav-menu-heading">Settings</div>
                            <a class="nav-link" href="charts.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Preferences
                            </a>
                            <a class="nav-link" href="tables.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Change Password
                            </a>-->
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?= session()->get('user_type_name');  ?>
                    </div>
                </nav>
            </div>
			