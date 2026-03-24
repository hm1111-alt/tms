
<!-- Bootstrap Modal -->
<div class="modal fade show" id="password_modal" tabindex="-1" aria-modal="true" role="dialog"  style="display: block;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Password</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
            </div>
            
            <form method="post" role="form" name="password_form" enctype="multipart/form-data" action="<?php echo site_url('profile/update_password'); //  ?>">
                    <div class="modal-body">
                        
                                <?php if (session()->getFlashdata('update_error')){ ?>
                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> 
                                        <label class="control-label"><?= session()->getFlashdata('update_error'); ?></label>
                                    </span>
                                <?php } ?>
                            <!-- Content Goes Here -->
                            <div class="form mb-3">
                                <label style="font-style: italic; font-size: smaller;">Please use a combination of <b>Letters</b> (uppercase and lowercase), <b>Number(s)</b> and <b>Special Character(s)</b> with a minimum of <b>8 characters</b> in length, no space(s) and in any order.</label>
                            </div>
                            <div class="form-floating mb-3">
                                <?php if (session()->getFlashdata('pass_error')){ ?>
                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> 
                                        <label class="control-label"><?= session()->getFlashdata('pass_error'); ?></label>
                                    </span>
                                <?php } ?>
                                <input type="password" name="password" class="form-control" id="inputpassword" value="" placeholder="Password" required />
                                <label for="inputpassword" style="<?php echo session()->getFlashdata('pass_error') ? 'margin-top: 30px;' : '';echo session()->getFlashdata('pass_error2') ? 'margin-top: 60px;' : ''; ?>">
                                    Password
                                </label>
                            </div>
                            <div class="form-floating mb-3">
                                <?php if (session()->getFlashdata('pass_confirm_error')){ ?>
                                    <span class="error-message mb-3"><i class="fas fa-triangle-exclamation"></i> 
                                        <label class="control-label"><?= session()->getFlashdata('pass_confirm_error'); ?></label>
                                    </span>
                                <?php } ?>
                                <input type="password" name="password_confirm" class="form-control" id="input_confirm" value="" placeholder="Password" required  />
                                <label for="input_confirm" style="<?php echo session()->getFlashdata('pass_confirm_error') ? 'margin-top: 30px;' : ''; ?>">
                                    Confirm Password
                                </label>

                            </div>
                                            


                    </div>
                    <div class="modal-footer">
                        <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
                        <button type="submit" class="btn btn-primary" id="submit_pic_btn">Update</button>
                    </div>
            </form>
            
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
