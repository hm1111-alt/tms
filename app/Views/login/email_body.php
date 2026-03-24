<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>CLSU E-portal | Account Registration</title>
        
        <style>
            body {
                font-family: "Helvetica Neue", Arial, sans-serif;
                /*background-color: #f1f2f7;*/
            }
        </style>
        
    </head>
    <body style=" color: #212529;">
        
            <div style="display: flex; flex-wrap: wrap; justify-content: center !important;">
                <div style="max-width: 600px;  margin-top:20px; ">
                        <div style="box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important; border-radius: 5px;">

                            <div style="background-color: #007b3e; color: white; border-top-left-radius: 5px; border-top-right-radius: 5px; padding-top: 10px; padding-bottom: 10px;">
                                <img class="" src="<?= base_url('public/assets/images/seal-monogram-white.png') ?>" style="max-height: 100px; display: inline-block; margin-left:20px;"  alt="CLSU Logo">
                                <h2 style="display: inline-block;color: white !important; margin-left:20px; font-size: 2rem; 
                                        vertical-align: text-bottom;
                                        font-weight: 500;
                                        line-height: 1.2;">
                                    Employee Portal v2
                                    <br>
                                    <span style="font-size: 1.2rem;"><?= @$email_title ?></span>
                                </h2>
                            </div>

                            <div style="margin-bottom: 3em; padding: 20px;">
                                <?= @$email_body ?>
                            </div>
                            
                            <div style="font-size: small; background-color: #eeeeee; padding: 20px;">
                                <div class="small ">
                                    If you think you received this email incorrectly, please contact <b style="color: blue;">miso@clsu.edu.ph</b>.
                                </div>

                                <!--<div class="small">&copy; 2024 CLSU-HRMO. All rights reserved.
                                    <br>Powered by <span class="text-success">Management Information System Office (CLSU-MISO)</span>.
                                </div>-->
                            </div>
                        </div>
                </div>
            </div>

    </body>
</html>
