
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html" style="height:100%;">
                <img src="<?= images('clsu_logo.png'); ?>" style="height:100%;">
                <?= MY_APP_NAME; ?>
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

                                                                            <img src="<?php echo $filedir; ?>" height="30" alt="">

                                                                        <?php } else { ?>

                                                                            <i class="fas fa-user fa-fw"></i>  

                                                                        <?php } ?>
																		
                        
                        <?= session()->get('display_name');  ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">Settings</a></li>
                        <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item"  style="cursor: pointer;"  data-bs-toggle="modal" data-bs-target="#password_modal">Change Password</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('logout'); ?>">Logout</a></li>
                    </ul>
                </li>
            </ul>
            
        </nav>