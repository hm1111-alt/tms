<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
        <title>Account Registration | Training Management System</title>
        
        <link href="<?= base_url('public/assets/css/styles.css') ?>" rel="stylesheet" />
        <script src="<?= base_url('public/assets/fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    </head>
    <body class="bg-default">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="align-items-center mt-5" style="height: 100px; text-align: center;">
                                        <img class="" src="<?= base_url('public/assets/images/clsu_logo250.png') ?>" style="max-width: 150px; max-height: 80px; display: inline-block;"  alt="Company Logo">
                                        <h2 class="font-weight-light px-2 text-success-clsu" style="display: inline-block; text-align: center;">Training Management System</h2>
                                </div>
                                <div class="card shadow-lg border-0 rounded-lg mt-2 mb-5">
                                    
                                    <div class="card-header bg-success text-white">
                                        <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Account Registration</h4>
                                    </div>
                                  
                                    <div class="card-body mb-3">
                                        <?php if (session()->getFlashdata('error') || @$error){ ?>
                                            <div class="card mb-4 bg-danger text-white">
                                                <div class="card-body">
                                                    <p class="mb-0"><i class="fas fa-triangle-exclamation"></i>
                                                        <?php echo session()->getFlashdata('error');
                                                        echo session()->getFlashdata('error') && @$error!='' ? '<br>' : '';
                                                        echo @$error;
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        
                                        <?php $request = \Config\Services::request(); ?>
                                        
                                        <!-- Toggle Button -->
                                        <div class="mb-4">
                                            <button type="button" id="toggleRegistrationBtn" class="btn btn-outline-primary" onclick="toggleRegistration()">
                                                <i class="fas fa-user-tie me-2"></i><span id="toggleBtnText">Register as Guest</span>
                                            </button>
                                        </div>
                                        
                                        <!-- Employee Registration Form -->
                                        <form method="post" role="form" name="employee_registration_form" action="<?php echo $request->getUri()->getPath(); ?>" id="employeeForm">
                                            <input type="hidden" name="registration_type" value="employee" />
                                            
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('employee_idno')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_idno'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" name="employee_idno" class="form-control" id="inputIdno" placeholder="Employee ID" value="<?= set_value('employee_idno') ?>" required />
                                                <label for="inputIdno" style="<?php echo (@$validation && $validation->hasError('employee_idno')) ? 'margin-top: 30px;' : ''; ?>">
                                                    Employee ID no. <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('employee_fname')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_fname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" name="employee_fname" class="form-control" id="inputFname" placeholder="First Name" value="<?= set_value('employee_fname') ?>" required />
                                                <label for="inputFname" style="<?php echo (@$validation && $validation->hasError('employee_fname')) ? 'margin-top: 30px;' : ''; ?>">
                                                    First Name <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('employee_lname')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_lname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" name="employee_lname" class="form-control" id="inputLname" placeholder="Last Name" value="<?= set_value('employee_lname') ?>" required />
                                                <label for="inputLname" style="<?php echo (@$validation && $validation->hasError('employee_lname')) ? 'margin-top: 30px;' : ''; ?>">
                                                    Last Name <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <?php if (@$validation && $validation->hasError('employee_mi')){ ?>
                                                            <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_mi'); ?></label></span>
                                                        <?php } ?>
                                                        <input type="text" name="employee_mi" class="form-control" id="inputMi" placeholder="Middle Initial" value="<?= set_value('employee_mi') ?>" maxlength="2" />
                                                        <label for="inputMi" style="<?php echo (@$validation && $validation->hasError('employee_mi')) ? 'margin-top: 30px;' : ''; ?>">
                                                            Middle Initial <span class="text-muted">(Optional)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <?php if (@$validation && $validation->hasError('employee_extname')){ ?>
                                                            <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_extname'); ?></label></span>
                                                        <?php } ?>
                                                        <input type="text" name="employee_extname" class="form-control" id="inputExtname" placeholder="Extension Name" value="<?= set_value('employee_extname') ?>" />
                                                        <label for="inputExtname" style="<?php echo (@$validation && $validation->hasError('employee_extname')) ? 'margin-top: 30px;' : ''; ?>">
                                                            Extension Name <span class="text-muted">(Optional)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('email_address')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('email_address'); ?></label></span>
                                                <?php } ?>
                                                <input type="email" name="email_address" class="form-control" id="inputEmail" placeholder="email@clsu.edu.ph" value="<?= set_value('email_address') ?>" required />
                                                <label for="inputEmail" style="<?php echo (@$validation && $validation->hasError('email_address')) ? 'margin-top: 30px;' : ''; ?>">
                                                    Official E-mail Address <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <div class="form mb-3">
                                                <label style="color: red; font-style: italic;">
                                                    <i class="fas fa-lock me-1"></i>
                                                    Password must be at least <b>12 characters</b> with uppercase, lowercase, numbers, and special characters.
                                                </label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <?php if ((@$validation && $validation->hasError('password')) || @$pass_error!=''){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> 
                                                        <label class="control-label"><?= $validation->getError('password'); ?></label>
                                                        <?php if(@$pass_error!=''){ ?><label class="control-label"><?= @$pass_error; ?></label><?php } ?>
                                                    </span>
                                                <?php } ?>
                                                <input type="password" name="password" class="form-control" id="inputPassword" value="<?= set_value('password') ?>" required placeholder="Password" />
                                                <label for="inputPassword" style="<?php echo ((@$validation && $validation->hasError('password')) || @$pass_error!='' ) ? 'margin-top: 30px;' : ''; ?>">
                                                    Password <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('password_confirm')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('password_confirm'); ?></label></span>
                                                <?php } ?>
                                                <input type="password" name="password_confirm" class="form-control" id="inputConfirm" value="<?= set_value('password_confirm') ?>" required placeholder="Password" />
                                                <label for="inputConfirm" style="<?php echo (@$validation && $validation->hasError('password_confirm')) ? 'margin-top: 30px;' : ''; ?>">
                                                    Confirm Password <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <br><hr><br>
                                            <div class="alert alert-warning">
                                                <strong>Data Privacy Notice:</strong><br>
                                                By submitting, you consent to <b>HRMO</b> and <b>MISO</b> to collect and process your personal information in accordance with CLSU's Data Privacy Policy.
                                            </div>
                                            
                                            <div class="align-items-center justify-content-between mt-4 mb-0 " style="text-align: right;">
                                                <button type="submit" id="save_employee" class="btn btn-success" role="button" style="width:40%; background-color:#007b3e; border-color: transparent;">
                                                    <i class="fas fa-paper-plane"></i> Register as Employee
                                                </button>
                                                <a href="<?php echo site_url('login'); ?>">
                                                    <button type="button" class="btn btn-secondary" role="button" style="width:40%; border-color: transparent;">
                                                        <i class="fas fa-ban"></i> Cancel
                                                    </button>
                                                </a>
                                            </div>
                                        </form>
                                        
                                        <!-- Guest Registration Form -->
                                        <form method="post" role="form" name="guest_registration_form" action="<?php echo $request->getUri()->getPath(); ?>" id="guestForm" style="display: none;">
                                            <input type="hidden" name="registration_type" value="guest" />
                                            
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('guest_fname')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('guest_fname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" name="guest_fname" class="form-control" id="inputGuestFname" placeholder="First Name" value="<?= set_value('guest_fname') ?>" required />
                                                <label for="inputGuestFname" style="<?php echo (@$validation && $validation->hasError('guest_fname')) ? 'margin-top: 30px;' : ''; ?>">
                                                    First Name <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('guest_lname')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('guest_lname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" name="guest_lname" class="form-control" id="inputGuestLname" placeholder="Last Name" value="<?= set_value('guest_lname') ?>" required />
                                                <label for="inputGuestLname" style="<?php echo (@$validation && $validation->hasError('guest_lname')) ? 'margin-top: 30px;' : ''; ?>">
                                                    Last Name <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <?php if (@$validation && $validation->hasError('guest_mi')){ ?>
                                                            <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('guest_mi'); ?></label></span>
                                                        <?php } ?>
                                                        <input type="text" name="guest_mi" class="form-control" id="inputGuestMi" placeholder="Middle Initial" value="<?= set_value('guest_mi') ?>" maxlength="2" />
                                                        <label for="inputGuestMi" style="<?php echo (@$validation && $validation->hasError('guest_mi')) ? 'margin-top: 30px;' : ''; ?>">
                                                            Middle Initial <span class="text-muted">(Optional)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3">
                                                        <?php if (@$validation && $validation->hasError('guest_extname')){ ?>
                                                            <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('guest_extname'); ?></label></span>
                                                        <?php } ?>
                                                        <input type="text" name="guest_extname" class="form-control" id="inputGuestExtname" placeholder="Extension Name" value="<?= set_value('guest_extname') ?>" />
                                                        <label for="inputGuestExtname" style="<?php echo (@$validation && $validation->hasError('guest_extname')) ? 'margin-top: 30px;' : ''; ?>">
                                                            Extension Name <span class="text-muted">(Optional)</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('guest_email')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('guest_email'); ?></label></span>
                                                <?php } ?>
                                                <input type="email" name="guest_email" class="form-control" id="inputGuestEmail" placeholder="your@email.com" value="<?= set_value('guest_email') ?>" required />
                                                <label for="inputGuestEmail" style="<?php echo (@$validation && $validation->hasError('guest_email')) ? 'margin-top: 30px;' : ''; ?>">
                                                    Personal E-mail Address <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <div class="form mb-3">
                                                <label style="color: red; font-style: italic;">
                                                    <i class="fas fa-lock me-1"></i>
                                                    Password must be at least <b>8 characters</b> with uppercase, lowercase, numbers, and special characters.
                                                </label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <?php if ((@$validation && $validation->hasError('guest_password')) || @$pass_error!=''){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> 
                                                        <label class="control-label"><?= $validation->getError('guest_password'); ?></label>
                                                        <?php if(@$pass_error!=''){ ?><label class="control-label"><?= @$pass_error; ?></label><?php } ?>
                                                    </span>
                                                <?php } ?>
                                                <input type="password" name="guest_password" class="form-control" id="inputGuestPassword" value="<?= set_value('guest_password') ?>" required placeholder="Password" />
                                                <label for="inputGuestPassword" style="<?php echo ((@$validation && $validation->hasError('guest_password')) || @$pass_error!='' ) ? 'margin-top: 30px;' : ''; ?>">
                                                    Password <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <?php if (@$validation && $validation->hasError('guest_password_confirm')){ ?>
                                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('guest_password_confirm'); ?></label></span>
                                                <?php } ?>
                                                <input type="password" name="guest_password_confirm" class="form-control" id="inputGuestConfirm" value="<?= set_value('guest_password_confirm') ?>" required placeholder="Password" />
                                                <label for="inputGuestConfirm" style="<?php echo (@$validation && $validation->hasError('guest_password_confirm')) ? 'margin-top: 30px;' : ''; ?>">
                                                    Confirm Password <span class="text-danger">*</span>
                                                </label>
                                            </div>
                                            
                                            <br><hr><br>
                                            <div class="alert alert-warning">
                                                <strong>Data Privacy Notice:</strong><br>
                                                By submitting, you consent to CLSU-TMS to collect and process your personal information in accordance with our Data Privacy Policy.
                                            </div>
                                            
                                            <div class="align-items-center justify-content-between mt-4 mb-0 " style="text-align: right;">
                                                <button type="submit" id="save_guest" class="btn btn-primary" role="button" style="width:40%; border-color: transparent;">
                                                    <i class="fas fa-user-plus"></i> Register as Guest
                                                </button>
                                                <a href="<?php echo site_url('login'); ?>">
                                                    <button type="button" class="btn btn-secondary" role="button" style="width:40%; border-color: transparent;">
                                                        <i class="fas fa-ban"></i> Cancel
                                                    </button>
                                                </a>
                                            </div>
                                        </form>
                                    <div class="card-footer text-center py-3">
                                        <div class="small mb-2"><a href="mailto:miso@clsu.edu.ph" class="text-success">Need help? Contact CLSU-HRMO.</a></div>
                                        
                                        <!--<div class="small">&copy; 2024 CLSU-HRMO. All rights reserved.
                                            <br>Powered by <span class="text-success">Management Information System Office (CLSU-MISO)</span>.
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer" style="padding-top: 20px;">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">&copy; Copyright 2025. All rights reserved.
                                <br><span class="text-success">Management Information System Office (MISO)</span>.
                                <br><?php echo strtoupper('Central Luzon State University'); ?>
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
        </div>
        <script src="<?= base_url('public/assets/bootstrap/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous"></script>
        <script src="<?= base_url('public/assets/js/scripts.js'); ?>"></script>
        
        <script>
            let isEmployeeForm = true;
            
            function toggleRegistration() {
                const employeeForm = document.getElementById('employeeForm');
                const guestForm = document.getElementById('guestForm');
                const toggleBtnText = document.getElementById('toggleBtnText');
                
                if (isEmployeeForm) {
                    // Switch to Guest form
                    employeeForm.style.display = 'none';
                    guestForm.style.display = 'block';
                    toggleBtnText.textContent = 'Register as Employee';
                    
                    // Toggle required attributes
                    employeeForm.querySelectorAll('[required]').forEach(input => input.removeAttribute('required'));
                    guestForm.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]').forEach(input => {
                        if (input.name !== 'registration_type' && input.name !== 'guest_mi' && input.name !== 'guest_extname') {
                            input.setAttribute('required', 'required');
                        }
                    });
                } else {
                    // Switch to Employee form
                    employeeForm.style.display = 'block';
                    guestForm.style.display = 'none';
                    toggleBtnText.textContent = 'Register as Guest';
                    
                    // Toggle required attributes
                    guestForm.querySelectorAll('[required]').forEach(input => input.removeAttribute('required'));
                    employeeForm.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]').forEach(input => {
                        if (input.name !== 'registration_type' && input.name !== 'employee_mi' && input.name !== 'employee_extname') {
                            input.setAttribute('required', 'required');
                        }
                    });
                }
                
                isEmployeeForm = !isEmployeeForm;
            }
        </script>
    </body>
</html>
