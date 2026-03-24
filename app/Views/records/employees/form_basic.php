<?php $request = \Config\Services::request(); ?>

<div class="row">

        <div class="col mb-4 col-lg-6">

                <div class="card mb-4 col-lg-12">

                        
                        <div class="card-body">

                                <h2 class="h2-class ">Basic Information</h2>


                                <div class="form-group mb-4 mt-4 px-4" >

                                <!--------------------------------------------------------->


                                        <div class="row">
                                            <label class="col col-lg-3">*<b>Last Name</b></label>
                                            <div class="col col-lg-9 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_lname')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_lname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" class="form-control" name="emp_lname" id="emp_lname" value="<?php echo set_value('emp_lname') ? set_value('emp_lname') : @$basic[0]->emp_lname; ?>" maxlength="50">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3">*<b>First name</b></label>
                                            <div class="col col-lg-9 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_fname')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_fname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" class="form-control" name="emp_fname" id="emp_fname" value="<?php echo set_value('emp_fname') ? set_value('emp_fname') : @$basic[0]->emp_fname; ?>" maxlength="50">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3">Middle Name</label>
                                            <div class="col-lg-4 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_mname')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_mname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" class="form-control" name="emp_mname" id="emp_mname" value="<?php echo set_value('emp_mname') ? set_value('emp_mname') : @$basic[0]->emp_mname; ?>" maxlength="50">
                                            </div>

                                            <label class="col-lg-2" style="padding-right: 0px;">Middle Initial</label>
                                            <div class="col col-lg-3 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_mi')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_mi'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" class="form-control" name="emp_mi" value="<?php echo set_value('emp_mi') ? set_value('emp_mi') : @$basic[0]->emp_mi; ?>" maxlength="3" placeholder="" onkeypress="validateletters(event)">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3" style="padding-right: 0px;">Name Extension</label>
                                            <div class="col-lg-4 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_extname')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_extname'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" class="form-control" name="emp_extname" value="<?php echo set_value('emp_extname') ? set_value('emp_extname') : @$basic[0]->emp_extname; ?>" maxlength="10" placeholder="e.g. Jr., Sr.">
                                            </div>
                                            
                                            <label class="col-lg-2">*<b>Sex</b></label>
                                            <div class="col-lg-3 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_sex')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_sex'); ?></label></span>
                                                <?php } ?>
                                                <select class="form-control m-bot15" name="emp_sex" style="padding-left: 3px;">
                                                    <?php $sex_post = set_value('emp_sex') ? set_value('emp_sex') : @$basic[0]->emp_sex; ?>
                                                    <option value=''>Select...</option>
                                                    <option value="Male" <?php echo 'Male'==$sex_post ? 'selected' : FALSE; ?>>Male</option>
                                                    <option value="Female" <?php echo 'Female'==$sex_post ? 'selected' : FALSE; ?>>Female</option>
                                                </select>
                                            </div>
                                            
                                            <!--<label class="col-lg-2">Nickname</label>
                                            <div class="col col-lg-3 <?php echo @$validation && $validation->hasError('emp_nickname') ? 'has-error has-feedback' : ''; ?> mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_nickname')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('emp_nickname'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                <input type="text" class="form-control" name="emp_nickname" value="<?php echo set_value('emp_nickname') ? set_value('emp_nickname') : @$basic[0]->emp_nickname; ?>" maxlength="15">
                                            </div>-->
                                        </div>

                                        <!--<div class="row">
                                            <label class="col col-lg-3">Contact No.</label>
                                            <div class="col col-lg-9 <?php echo @$validation && $validation->hasError('emp_cpno') ? 'has-error has-feedback' : ''; ?> mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_cpno')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('emp_cpno'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                <input type="text" class="form-control" name="emp_cpno" value="<?php echo set_value('emp_cpno') ? set_value('emp_cpno') : @$basic[0]->emp_cpno; ?>" maxlength="11">
                                            </div>
                                        </div>-->

                                        <div class="row">
                                            <label class="col col-lg-3">Personal Email</label>
                                            <div class="col col-lg-9 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_email_personal')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_email_personal'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" class="form-control" name="emp_email_personal" value="<?php echo set_value('emp_email_personal') ? set_value('emp_email_personal') : @$basic[0]->emp_email_personal; ?>" maxlength="100" placeholder="email@domain.com">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3">*<b>Official Email</b></label>
                                            <div class="col col-lg-9 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_email_official')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_email_official'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" class="form-control" name="emp_email_official" value="<?php echo set_value('emp_email_official') ? set_value('emp_email_official') : @$basic[0]->emp_email_official; ?>" maxlength="100" placeholder="email@clsu.edu.ph">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3">*<b>Employee ID</b></label>
                                            <div class="col col-lg-9 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_idno')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_idno'); ?></label></span>
                                                <?php } ?>
                                                <?php if($request->uri->getSegment(2)=='add'){ ?>
                                                    <input type="text" class="form-control" name="emp_idno" value="<?php echo set_value('emp_idno') ? set_value('emp_idno') : @$basic[0]->emp_idno; ?>" maxlength="7" placeholder="PhilRice ID no.">
                                                <?php } else { ?>
                                                    <input type="text" class="form-control" disabled name="emp_idno2" value="<?php echo set_value('emp_idno') ? set_value('emp_idno') : @$basic[0]->emp_idno; ?>" maxlength="7" placeholder="PhilRice ID no.">
                                                    <input type="hidden" name="emp_idno" value="<?php echo set_value('emp_idno') ? set_value('emp_idno') : @$basic[0]->emp_idno; ?>">
                                                <?php } ?>
                                            </div>
                                        </div>

                                        <!--<div class="row">
                                            <label class="col col-lg-3">*<b>TIN</b></label>
                                            <div class="col col-lg-9 <?php echo @$validation && $validation->hasError('emp_tin') ? 'has-error has-feedback' : ''; ?> mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_tin')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('emp_tin'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                <input type="text" class="form-control" name="emp_tin" id="emp_tin" value="<?php echo set_value('emp_tin') ? set_value('emp_tin') : @$basic[0]->emp_tin; ?>" maxlength="15" placeholder="000-000-000" onkeypress="validateamount(event)">
                                            </div>
                                        </div>-->



                                        <!--<div class="row">
                                            <label class="col col-lg-3">*<b>Date of Birth</b></label>
                                            <div class="col-lg-4 <?php echo @$validation && $validation->hasError('emp_birthdate') ? 'has-error has-feedback' : ''; ?> mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_birthdate')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('emp_birthdate'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                <input type="text" name="emp_birthdate" class="datepicker form-control" value="<?php echo set_value('emp_birthdate') ? set_value('emp_birthdate') : (@$basic[0]->emp_birthdate!='' ? date('Y-m-d',strtotime(@$basic[0]->emp_birthdate)) : ''); ?>" placeholder="YYYY-mm-dd" maxlength="10" />
                                            </div>

                                        </div>-->

                                </div>

                        </div>

                </div>

        </div>

        <div class="col mb-4 col-lg-6">

                <div class="card mb-4 col-lg-12">

                        <div class="card-body">

                                <div class="form-group  mb-4 mt-4 px-4">


                                        <div class="row">

                                            <label class="col col-lg-3">*<b>Status of Appointment</b></label>
                                            <div class="col col-lg-3 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_status')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_status'); ?></label></span>
                                                <?php } ?>
                                                <select class="form-control m-bot15"  name="emp_status" id="emp_status" style="padding-left: 3px;">
                                                    <option value="">Select...</option>
                                                    <?php
                                                    $status_post = set_value('emp_status') ? set_value('emp_status') : @$basic[0]->emp_status;
                                                    if(@$status)foreach($status as $stat){ ?>
                                                        <option <?php echo $stat->id_status==$status_post ? 'selected' : FALSE; ?> value="<?php echo $stat->id_status; ?>">
                                                            <?php echo $stat->status_name; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <!--<input type="hidden" name="emp_status" value="<?php //echo @$basic[0]->emp_status; ?>" >-->
                                            </div>
                                            <label class="col col-lg-3">*<b>Date of Appointment</b></label>
                                            <div class="col col-lg-3 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_date_hired')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_date_hired'); ?></label></span>
                                                <?php } ?>
                                                <input type="text" name="emp_date_hired" class="datepicker form-control" value="<?php echo set_value('emp_date_hired') ? set_value('emp_date_hired') : (@$basic[0]->emp_date_hired!='' ? date('m/d/Y',strtotime(@$basic[0]->emp_date_hired)) : null); ?>" placeholder="YYYY-mm-dd" maxlength="10" />
                                            </div>
                                        </div>



                                        <div class="row mt-2">
                                            <label class="col col-lg-3">*<b>Position</b></label>
                                            <div class="col col-lg-9 mb-2 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_position')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_position'); ?></label></span>
                                                <?php } ?>
                                                <select class="form-control m-bot15" name="emp_position" style="padding-left: 3px;">
                                                    <option value=''>Select...</option>
                                                    <?php
                                                    $position_post = set_value('emp_position') ? set_value('emp_position') : @$basic[0]->emp_position;

                                                    if(@$positions)
                                                    foreach($positions as $posit){ ?>
                                                        <option <?php echo $posit->id_position==$position_post ? 'selected' : FALSE; ?> value="<?php echo $posit->id_position; ?>">
                                                            <?php echo $posit->position_name.' (SG-'.$posit->salary_grade.')'; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row mt-2">
                                            <label class="col col-lg-3">*<b>Program</b></label>
                                            <div class="col col-lg-9 mb-2 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_program')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_program'); ?></label></span>
                                                <?php } ?>
                                                <select class="form-control m-bot15" name="emp_program" style="padding-left: 3px;">
                                                    <option value=''>Select...</option>
                                                    <?php
                                                    $program_post = set_value('emp_program') ? set_value('emp_program') : @$basic[0]->emp_program;

                                                    if(@$programs)
                                                    foreach($programs as $stn){ ?>
                                                        <option <?php echo $stn->id_program==$program_post ? 'selected' : FALSE; ?> value="<?php echo $stn->id_program; ?>">
                                                            <?php echo $stn->program_name; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3">*<b>Office</b></label>
                                            <div class="col col-lg-9 mb-2 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_office')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_office'); ?></label></span>
                                                <?php } ?>
                                                <select class="form-control m-bot15" name="emp_office" style="padding-left: 3px;">
                                                    <option value=''>Select...</option>
                                                    <?php $office_post = set_value('emp_office') ? set_value('emp_office') : @$basic[0]->emp_office; ?>
                                                    <option value='---' <?php echo $office_post=='---' || (@$basic && $basic[0]->emp_office=='') ? 'selected' : ''; ?>>--no office</option>
                                                    <?php 
                                                    if(@$offices)
                                                    foreach($offices as $off){ ?>
                                                        <option <?php echo $off->id_office==$office_post ? 'selected' : FALSE; ?> value="<?php echo $off->id_office; ?>">
                                                            <?php echo $off->office_name; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3">*<b>Division</b></label>
                                            <div class="col col-lg-9 mb-2 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_division')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_division'); ?></label></span>
                                                <?php } ?>
                                                <select class="form-control m-bot15" name="emp_division" style="padding-left: 3px;">
                                                    <option value=''>Select...</option>
                                                    <?php $division_post = set_value('emp_division') ? set_value('emp_division') : @$basic[0]->emp_division; ?>
                                                    <option value='---' <?php echo $division_post=='---' || (@$basic && $basic[0]->emp_division==0) ? 'selected' : ''; ?>>--no division</option>
                                                    <?php 
                                                    if(@$divisions)
                                                    foreach($divisions as $div){ ?>
                                                        <option <?php echo $div->id_division==$division_post ? 'selected' : FALSE; ?> value="<?php echo $div->id_division; ?>">
                                                            <?php echo $div->division_name; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <label class="col col-lg-3">Unit</label>
                                            <div class="col col-lg-9 mb-2 mb-2">
                                                <?php if(@$validation && $validation->hasError('emp_unit')){ ?>
                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('emp_unit'); ?></label></span>
                                                <?php } ?>
                                                <select class="form-control m-bot15" name="emp_unit" style="padding-left: 3px;">
                                                    <option value=''>Select...</option>
                                                    <?php 
                                                    $unit_post = set_value('emp_unit') ? set_value('emp_unit') : @$basic[0]->emp_unit;
                                                    if(@$units)
                                                    foreach($units as $unit){ ?>
                                                        <option <?php echo $unit->id_unit==$unit_post ? 'selected' : FALSE; ?> value="<?php echo $unit->id_unit; ?>">
                                                            <?php echo $unit->unit_name; echo @$unit->unit_abbr ? ' ('.@$unit->unit_abbr.')' : ''; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <label class="col col-lg-3"></label>
                                            <div class="col-lg-4 <?php echo @$validation && $validation->hasError('emp_is_active') ? 'has-error has-feedback' : ''; ?>">
                                                <div class="checkbox">
                                                    <label>
                                                        <?php /*/*echo set_checkbox('emp_is_active', '1')!='' ? 'yes' : 'no';
                                                        if(set_checkbox('emp_is_active', '1')!=''){
                                                            $active_post1 = set_value('emp_is_active');echo 'a';
                                                        } else if(@$basic[0]->emp_is_active!=null){ echo 'b';
                                                            $active_post1 = @$basic[0]->emp_is_active;
                                                        } else { echo 'c';
                                                            $active_post1 = 1;
                                                        }*/
                                                        $active_post = set_value('emp_is_active') ? set_value('emp_is_active') : @$basic[0]->emp_is_active; ?>
                                                        <input type="checkbox" name="emp_is_active" value="1" class="checkboxx scaled-checkbox" <?php echo @$active_post ? 'checked' : ''; ?>>
                                                        <span class="">Active Employee</span>
                                                    </label>
                                                </div>

                                            </div>
                                        </div>

                                </div>

                                <div class="form-group" style="padding-bottom: 30px;">

                                        <div class="col-sm-12">
                                            <a href="<?php //echo site_url('employees/view/'.$request->uri->getSegment(3)); ?>" onclick="return confirm('All unsaved changes will be lost if you leave now. Are you sure you want to leave?')" class="ban-circle">
                                                <button type="button" id="cancel" class="btn-light btn cancel_btn" style="margin-left:15px;float: right;">Cancel</button>
                                            </a>
                                            <button type="submit" class="btn btn-success btn-lg save_button1" id="submit_btn" style="display: inline-block; float: right;">Update</button>
                                        </div>

                                </div>
                                    
                        </div>
                </div>

        </div>


</div>


<script type="text/javascript">
        $(document).ready(function(){

                $('#emp_tin').keyup(function () { 
                    var foo = $(this).val().split("-").join(""); // remove hyphens
                    if (foo.length > 0) {
                        foo = foo.match(new RegExp('.{1,3}', 'g')).join("-");
                    }
                    $(this).val(foo);
                });
                
        });
        
        function validateamount(evt) {
                var theEvent = evt || window.event;
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode( key );
                var regex = /[0-9]|[\b]|[\t]/;
                if( !regex.test(key) ) {
                      theEvent.returnValue = false;
                      if(theEvent.preventDefault) theEvent.preventDefault();
                }
        } 
        
        function validateletters(evt) {
                var theEvent = evt || window.event;
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode( key );
                var regex = /[a-z]|[A-Z]|[ñ]|[Ñ]|[\b]|[\t]/;
                if( !regex.test(key) ) {
                      theEvent.returnValue = false;
                      if(theEvent.preventDefault) theEvent.preventDefault();
                }
        } 
        
</script>