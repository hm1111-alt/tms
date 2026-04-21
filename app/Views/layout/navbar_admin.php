        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <div class="navbar-brand ps-3" style="height: 50px; display: flex; align-items: center;">
                <img src="<?= images('clsu_logo.png'); ?>" style="height: 35px; margin-right: 10px;">
                TRAINING MANAGEMENT SYSTEM
            </div>
            
            <!-- Navbar Search (Empty for spacing) -->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <!-- Empty placeholder -->
            </form>
            
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <?php if(session()->get('logged_in')): ?>
                    <!-- Admin User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php 
                            $profile_icon = session()->get('profile_icon') ?? 'default.png';
                            $filedir = base_url('public/assets/images/profiles/thumbs/' . $profile_icon);
                            ?>
                            
                            <?php if(file_exists(FCPATH . 'public/assets/images/profiles/thumbs/' . $profile_icon) && $profile_icon != 'default.png'): ?>
                                <img src="<?= $filedir ?>" height="30" alt="">
                            <?php else: ?>
                                <i class="fas fa-user fa-fw"></i>  
                            <?php endif; ?>
                            
                            <?= session()->get('display_name') ?? session()->get('emp_fname') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?= site_url('dashboard') ?>">Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('myprofile') ?>">Profile</a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="<?= site_url('trainings') ?>">Training Management</a></li>
                            <li><a class="dropdown-item" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#password_modal">Change Password</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
