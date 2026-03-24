<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
        <title>Login | Employee Portal</title>
        
        <link href="<?= base_url('public/assets/css/styles.css') ?>" rel="stylesheet" />
        <script src="<?= base_url('public/assets/fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    </head>
    <body class="bg-default">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-4">
                                <div class="align-items-center mt-5" style="height: 200px; text-align: center;">
                                        <img class="" src="<?= images('seal-monogram-green.png') ?>" style="max-height: 200px; display: inline-block;"  alt="Company Logo">
                                        <h2 class="font-weight-light px-2 text-success-clsu" style="display: inline-block; text-align: center;"></h2>
                                </div>
                                <div class="card shadow-lg border-0 rounded-lg mt-2">
                                    
                                    <div class="card-header text-center bg-success text-white" style="">
                                        <!--<h4 class="text-center font-weight-light mb-4 mt-2" style=" color: #078b09;">Online Leave Application</h4>-->
                                        <h4 class="text-center font-weight-light mb-3 mt-2" style="">Employee Portal v2.0</h4>
                                        <!--<small class="">Enter your username & password to login</small>-->
                                    </div>
                                  
                                    <div class="card-body mb-5">
                                          
                                        <?php $has_error = 0;
                                        $with_error = 0;
                                        $validation_errors = '';
                                        if (@$validation && $validation->listErrors()){ 
                                            $has_error = 1;
                                            $with_error = 1;
                                            $validation_errors .= $validation->listErrors();
                                        } if(session()->getFlashdata('error')){
                                            $with_error = 1;
                                            $validation_errors .= $has_error==1 ? '<br>'.session()->getFlashdata('error') : session()->getFlashdata('error');
                                        }
                                        if ($with_error==1){ ?>
                                            <div class="card mb-4 bg-danger text-white">
                                                <div class="card-body">
                                                    <p class="mb-0"><i class="fas fa-triangle-exclamation"></i>
                                                        <?= $validation_errors; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php }
                                        
                                        if(session()->getFlashdata('success')){ ?>
                                            <div class="card mb-4 bg-success text-white">
                                                <div class="card-body">
                                                    <p class="mb-0"><i class="fas fa-check-circle"></i>
                                                        <?= session()->getFlashdata('success'); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            
                                        <?php } ?>
                                        
                                        <?php $request = \Config\Services::request(); ?>
                                        
                                        <form method="post" role="form" name="login_form" action="<?php echo $request->getUri()->getPath();  ?>">
                                                <div class="form-floating mb-3">

                                                    <input type="text" name="username" class="form-control" id="inputEmail" placeholder="name@mail.com" required />
                                                    <label for="inputEmail">ID no. or E-mail</label>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" required />
                                                    <label for="inputPassword">Password</label>
                                                </div>
                                                <!--<div class="form-check mb-3">
                                                    <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                    <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                                </div>-->
                                                <div class="align-items-center justify-content-between mt-4 mb-0 text-center" style="">
                                                    
                                                    <a class="small d-block mb-3 text-success" href="<?= site_url('forgot_password'); ?>">Forgot Password?</a>
                                                    
                                                    <button type="submit" id="save" class="btn btn-success" role="button" style="width:40%;  border-color: transparent;">
                                                        <i class="fas fa-arrow-right-to-bracket"></i> Login
                                                    </button>
                                                    <a href="<?php echo site_url('register'); ?>">
                                                        <button type="button" class="btn btn-warning text-white" role="button" style="width:40%; border-color: transparent;">
                                                            <i class="fas fa-pencil"></i> Register
                                                        </button>
                                                    </a>
                                                </div>
                                        </form>
                                    </div>
                                    <!--<div class="card-footer text-center py-3">-->
                                        <!--<div class="small "><a href="register.html" class="text-success">Need an account? Sign up!</a></div>-->
                                        
                                        <!--<div class="small">&copy; 2024 CLSU-HRMO. All rights reserved.
                                            <br>Powered by <span class="text-success">Management Information System Office (CLSU-MISO)</span>.
                                        </div>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer" class="">
                <footer class="py-4 bg-light mt-auto text-center">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted w-100"><b>Employee Portal v2.0</b>
                                <br>&copy; Copyright 2025. All rights reserved.
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
