<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
        <title>Account Registration | Employee Portal</title>
        
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
                                        <h2 class="font-weight-light px-2 text-success-clsu" style="display: inline-block; text-align: center;">Employee Portal v2</h2>
                                </div>
                                <div class="card shadow-lg border-0 rounded-lg mt-2 mb-5">
                                    
                                    <div class="card-header bg-success text-white">
                                        <h4 class="font-weight-light mb-2 mt-2">Account Registration</h4>
                                        <!--<small class="">Enter your username & password to login</small>-->
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
                                        
                                        <form method="post" role="form" name="registration_form" action="<?php echo $request->getUri()->getPath();  ?>">
                                            
                                                <div class="form-floating mb-3">
                                                    <?php if (@$validation && $validation->hasError('employee_idno')){ ?>
                                                        <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_idno'); ?></label></span>
                                                    <?php } ?>
                                                    <input type="text" name="employee_idno" class="form-control" id="inputIdno" placeholder="ID no." value="<?= set_value('employee_idno') ?>" />
                                                    <label for="inputIdno" style="<?php echo (@$validation && $validation->hasError('employee_idno')) ? 'margin-top: 30px;' : ''; ?>">
                                                        Employee ID no.
                                                    </label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <?php if (@$validation && $validation->hasError('employee_fname')){ ?>
                                                        <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_fname'); ?></label></span>
                                                    <?php } ?>
                                                    <input type="text" name="employee_fname" class="form-control" id="inputfname" placeholder="First Name" value="<?= set_value('employee_fname') ?>"  />
                                                    <label for="inputfname" style="<?php echo (@$validation && $validation->hasError('employee_fname')) ? 'margin-top: 30px;' : ''; ?>">
                                                        First Name
                                                    </label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <?php if (@$validation && $validation->hasError('employee_lname')){ ?>
                                                        <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('employee_lname'); ?></label></span>
                                                    <?php } ?>
                                                    <input type="text" name="employee_lname" class="form-control" id="inputlname" placeholder="Last Name" value="<?= set_value('employee_lname') ?>"  />
                                                    <label for="inputlname" style="<?php echo (@$validation && $validation->hasError('employee_lname')) ? 'margin-top: 30px;' : ''; ?>">
                                                        Last Name
                                                    </label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <?php if (@$validation && $validation->hasError('email_address')){ ?>
                                                        <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('email_address'); ?></label></span>
                                                    <?php } ?>
                                                    <input type="email" name="email_address" class="form-control" id="inputEmail" placeholder="email@clsu.edu.ph" value="<?= set_value('email_address') ?>"  />
                                                    <label for="inputEmail" style="<?php echo (@$validation && $validation->hasError('email_address')) ? 'margin-top: 30px;' : ''; ?>">
                                                        E-mail Address (Official)
                                                    </label>
                                                </div>
                                            <br>
                                            
                                                <div class="form mb-3">
                                                    <label style="color: red; font-style: italic;">(For your password, please use a combination of <b>Letters</b> (uppercase and lowercase), <b>Number(s)</b> and <b>Special Character(s)</b> with a minimum of <b>12 characters</b> in length, no space(s) and in any order)</label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <?php if ((@$validation && $validation->hasError('password')) || @$pass_error!=''){ ?>
                                                        <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> 
                                                            <label class="control-label"><?= $validation->getError('password'); ?></label>
                                                            <?php if(@$pass_error!=''){ ?><label class="control-label"><?= @$pass_error; ?></label><?php } ?>
                                                            
                                                        </span>
                                                    <?php } ?>
                                                    <input type="password" name="password" class="form-control" id="inputpassword" value="<?= set_value('password') ?>"  placeholder="Password" />
                                                    <label for="inputpassword" style="<?php echo ((@$validation && $validation->hasError('password')) || @$pass_error!='' ) ? 'margin-top: 30px;' : ''; ?>">
                                                        Password
                                                    </label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <?php if (@$validation && $validation->hasError('password_confirm')){ ?>
                                                        <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('password_confirm'); ?></label></span>
                                                    <?php } ?>
                                                    <input type="password" name="password_confirm" class="form-control" id="input_confirm" value="<?= set_value('password_confirm') ?>"   placeholder="Password" />
                                                    <label for="input_confirm" style="<?php echo (@$validation && $validation->hasError('password_confirm')) ? 'margin-top: 30px;' : ''; ?>">
                                                        Confirm Password
                                                    </label>
                                                    
                                                </div>
                                            
                                                <br><hr><br>
                                                <div>
                                                    <h5>Data Privacy Notice</h5>
                                                    <blockquote>
                                                        <p align="justify">By clicking <b>"Submit"</b>, you are giving consent to the 
                                                            <b>Human Resources Management Office (HRMO)</b> and <b>Management Information System Office (MISO)</b> 
                                                            of Central Luzon State University (CLSU)
                                                            to collect, process, store and use your personal information. 
                                                            <!--for the purpose/s described in the--> 
                                                            <!--<a href="<?= site_url('data-privacy-policy') ?>" target="_blank"><b>Data Privacy Policy</b></a>.-->
                                                        </p>
                                                    </blockquote>
                                                </div>
                                            
                                                <!--<div class="form-check mb-3">
                                                    <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                    <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                                </div>-->
                                                <div class="align-items-center justify-content-between mt-4 mb-0 " style="text-align: right;">
                                                    <!--<a class="small" href="password.html">Forgot Password?</a>-->
                                                    <button type="submit" id="save" class="btn btn-success " role="button" style="width:40%; background-color:#007b3e; border-color: transparent;">
                                                        <i class="fas fa-paper-plane"></i> Submit
                                                    </button>
                                                    <a href="<?php echo site_url('login'); ?>">
                                                        <button type="button" class="btn btn-secondary " role="button" style="width:40%; border-color: transparent;">
                                                            <i class="fas fa-ban"></i> Cancel
                                                        </button>
                                                    </a>
                                                </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small mb-2"><a href="#" class="text-success">Need help? Contact CLSU-HRMO.</a></div>
                                        
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
    </body>
</html>
