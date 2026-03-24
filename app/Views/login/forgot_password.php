<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url('public/favicon.ico'); ?>">
        <title>Forgot Password | Employee Portal</title>
        
        <link href="<?= base_url('public/assets/css/styles.css') ?>" rel="stylesheet" />
        <script src="<?= base_url('public/assets/fontawesome/all.js') ?>" crossorigin="anonymous"></script>
    </head>
    <body class="bg-default">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="align-items-center mt-5" style="height: 100px; text-align: center;">
                                        <img class="" src="<?= base_url('public/assets/images/clsu_logo250.png') ?>" style="max-width: 150px; max-height: 80px; display: inline-block;"  alt="Company Logo">
                                        <h2 class="font-weight-light px-2 text-success-clsu" style="display: inline-block; text-align: center;">Employee Portal v2</h2>
                                </div>
                                <div class="card shadow-lg border-0 rounded-lg mt-2 mb-5">
                                    
                                    <div class="card-header bg-success text-white">
                                        <h4 class="font-weight-light mb-2 mt-2"><i class="fas fa-key"></i> Forgot Password?</h4>
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
                                        
                                        <form method="post" role="form" name="forgotpassword_form" action="<?php echo $request->getUri()->getPath();  ?>">
                                            
                                                <small class="mb-2">Enter your ID no. or email address of your account:</small>
                                                
                                                <div class="form-floating mb-3 mt-3">

                                                    <input type="text" name="username" class="form-control" id="inputEmail" placeholder="name@mail.com" required value="<?= set_value('username') ?>" />
                                                    <label for="inputEmail">ID no. or E-mail</label>
                                                </div>
                                            
                                                <div class="align-items-center text-center justify-content-between mt-4 mb-0 " style="">
                                                    <!--<a class="small" href="password.html">Forgot Password?</a>-->
                                                    <button type="submit" id="save" class="btn btn-success " role="button" style="width:40%; background-color:#007b3e; border-color: transparent;">
                                                        <i class="fas fa-envelope"></i> Request reset link
                                                    </button>
                                                    <a class="small d-block mt-3" href="<?php echo site_url('login'); ?>">Back to login</a>
                                                </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small mb-2"><a href="mailto: miso@clsu.edu.ph" class="text-success">Need help? Contact CLSU-MISO.</a></div>
                                        
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
