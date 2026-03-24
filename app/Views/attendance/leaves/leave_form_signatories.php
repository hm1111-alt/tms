<?php
// Get the request service
$request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?php echo site_url('/leaves'); ?>" class="btn btn-light text-secondary"  role="button" onclick="return confirm('There are unsaved changes. Are you sure you want to leave?')">
                            <i class="fas fa-arrow-circle-left" style=""></i>
                            <div style="color: #999;">Back to List</div>
                        </a>
                    </li>
                    <li style="">
                        <a href="<?php echo site_url('/leaves'); ?>" class="btn btn-light text-success"  role="button" onclick="return confirm('There are unsaved changes. Are you sure you want to leave?')">
                            <i class="fas fa-file-text" style=""></i>
                            <div style="color: #999;">My Leave Card</div>
                        </a>
                    </li>
                    <li style="">
                        <a href="<?php echo site_url('/leaves/cancel_draft'); ?>" class="btn btn-light text-danger"  role="button" onclick="return confirm('Are you sure you want to cancel this leave? This cannot be undone.')">
                            <i class="fas fa-ban" style=""></i>
                            <div style="color: #999;">Cancel</div>
                        </a>
                    </li>
                    
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>

<style>
    .input-group-addon {
        display: table-cell;
        width: 1%;
    white-space: nowrap;
    vertical-align: middle;
    border: 1px solid #ccc;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: normal;
    line-height: 1;
    text-align: center;
    }
    .input-group-addon:last-child {
        border-left: 0;
    }
    .input-group .input-group-addon {
        border-radius: 0;
        border-color: #d2d6de;
        background-color: #fff;
    }
    
    
   
</style>

<script type="text/javascript">
        $(function() {
            
        });
</script>



        <div style="min-height: 50vh">

                <div class="card mb-4 col-lg-12">
                    <?= $this->include('attendance/leaves/leave_form_progress') ?>
                </div>
            
                <div class="row">
                        <div class="col mb-4 col-lg-5">
                                <div class="card mb-4">
                                        <!--<div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            DataTable Example
                                        </div>-->

                                        <div class="card-body">
                                                <h2 class="mt-2 mb-4">Application for Leave</h2>

                                                <!--<input type="hidden" id="access_level" value="<?php echo session()->get('access_level'); ?>">-->
                                                <!--<input type="hidden" id="user_type" value="<?php echo session()->get('user_type_id'); ?>">-->


                                                <div class="col-lg-12 mb-2">
                                                    <?= $this->include('attendance/leaves/form_details_employee') ?>
                                                </div>


                                                <hr>
                                                

                                                <div class="container mb-2" id="leave_credits_div" style="">

                                                        <h4 class="boldtext" style="padding-left:20px;">Leave Credits</h4>

                                                        <div class="row">
                                                            <label class="col col-lg-3" style="text-align: right; padding-top:0px; font-size: 13px;">As of </label>
                                                            <div class="col col-lg-9">
                                                                <span style="text-decoration: underline; font-weight: bold;" id="asofdate"><?php echo @$credits[0]->last_updated!='' ? date('F j, Y',strtotime(@$credits[0]->last_updated)) : ''; ?></span>
                                                                <span style="margin-left: 20px; font-style: italic; font-weight: bold;" >
                                                                    <a href="<?php echo site_url('attendance/leave/view_credits/'.@$credits[0]->employee_id.'/'.date('Y')); ?>" title="Click here to view Leave Card" target="_blank" class="text-danger">
                                                                        (View <?php echo @$credits[0]->employee_id==session()->get('empid') ? 'My' : ''; ?> Leave Card)
                                                                    </a>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="row" style="margin-left:30px">
                                                                <input type="hidden" name="credit_id" class="credit_id" value="<?php echo @$credits[0]->id_credit; ?>">
                                                                <input type="hidden" name="new_credit_id" class="new_credit_id" value="">
                                                                <input type="hidden" name="asof_total_credits" id="asof_total_credits" value="<?php echo @$credits[0]->vl + @$credits[0]->sl; ?>">
                                                                <input type="hidden" name="factor" id="factor" value="<?php echo @$factor; ?>">

                                                                <?= $this->include('attendance/leaves/form_credits_table') ?>
                                                                
                                                                <br><em>Note: To be updated by HRMO.</em>
                                                        </div>

                                                </div>
                                                
                                                <div class="container mb-2 mt-4" id="" style="">

                                                        <h4 class="boldtext" style="padding-left:20px;">Details of Application</h4>



                                                        <?php if(@$details){ ?>

                                                                <div class="col col-sm-12" style="margin-left:30px;">
                                                                        <?= $this->include('attendance/leaves/form_details_table') ?>
                                                                </div>

                                                                <div class="col col-sm-12" id="submit_btns_div" style="<?php echo  @$validation && $validation->listErrors() ? 'display:none;' : 'display: inline-block;'; ?> text-align: right; padding-top:10px;">


                                                                                <a href="<?php echo site_url('leaves/add_signatories'); ?>" onclick="return confirm('Are you sure you want to proceed?')" title="Go to next step">
                                                                                    <button class="btn btn-primary btn-lg " id="<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="background-color: #3ACF93; border-color: #3ACF93; margin-right:10px;">
                                                                                        <i class="glyphicon glyphicon-arrow-right" style="padding-right:7px;"></i> Next
                                                                                    </button>
                                                                                </a>

                                                                </div>

                                                        <?php } ?>
                                                        
                                                </div>
                                        </div>
                                        
                                </div>
                                        
                        </div>
                        
                        <div class="col mb-4 col-lg-7">

                                <div class="card mb-4">
                                        <div class="card-body">

                                                <div class="container mb-2" id="" style="">


                                                        <h4 class="boldtext" style="">Signatories:
                                                                <?php /*if(@$signatories[0]->approving_name){ ?>
                                                                    <button class="btn btn-primary btn-xs" title="Edit signatories" id="edit_btn" style="background-color: #3ACF93; border-color: #3ACF93;">
                                                                        <i class="icon-pencil"></i> Edit
                                                                    </button>
                                                                <?php }*/ ?>
                                                        </h4>
                                                    
                                                    
                                                        <div id="signatories_div"  class="col-lg-12" style="<?php echo (@$signatories[0]->approving_name && !@$validation) ? 'display: inline-block;' : 'display:none;'; ?>">
                                                                <div class="container">
                                                                    <div class="row" style="">
                                                                        <table style="width: 90%;">
                                                                            <tr>
                                                                                <td valign="top" style="padding-right:20px; width: 35%;">Certification of Leave Credits:</td>
                                                                                <td><?php if(@$signatories[0]->certifiedby!=''){
                                                                                        echo '<b>'.@$signatories[0]->cert_name.'</b>';
                                                                                        echo @$signatories[0]->cert_designation_name!='' ? '<br><em>'.@$signatories[0]->cert_designation_name.'</em>' : '<br><em>'.@$signatories[0]->cert_position_name.'</em>';
                                                                                    } else echo 'Jonathan T. Gurion'; ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" style="padding-top:10px;">Recommended By:</td>
                                                                                <td style="padding-top:10px;"><?php if(@$signatories[0]->recommending_name!=''){
                                                                                        echo '<b>'.@$signatories[0]->recommending_name.'</b>';
                                                                                        echo '<br><em>'.@$signatories[0]->recommending_designation.'</em>';
                                                                                    } else echo '---'; ?>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="top" style="padding-top:10px;">Approved By:</td>
                                                                                <td style="padding-top:10px;"><?php if(@$signatories[0]->approving_name!=''){
                                                                                        echo '<b>'.@$signatories[0]->approving_name.'</b>';
                                                                                        echo '<br><em>'.@$signatories[0]->approving_designation.'</em>';
                                                                                    } ?>
                                                                                </td>
                                                                            </tr>
                                                                        </table>

                                                                    </div>
                                                                </div>

                                                                <div class="col col-lg-12" id="submit_btns_div" style="<?php echo @$validation ? 'display:none;' : 'display: inline-block;'; ?> text-align: right;">


                                                                        <a href="<?php echo site_url('leaves/add_details'); ?>" onclick="return confirm('Are you sure you want to go back?')" title="Go back to details">
                                                                            <button class="btn btn-default " id="<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="margin-right:10px;">
                                                                                <i class="glyphicon glyphicon-arrow-left" style="padding-right:7px;"></i> Back
                                                                            </button>
                                                                        </a>

                                                                        <a href="<?php echo site_url('leaves/add_confirm'); ?>" onclick="return confirm('Are you sure you want to proceed?')" title="Go to next step">
                                                                            <button class="btn btn-primary btn-lg " id="<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="background-color: #3ACF93; border-color: #3ACF93; margin-right:10px;">
                                                                                <i class="glyphicon glyphicon-arrow-right" style="padding-right:7px;"></i> Next
                                                                            </button>
                                                                        </a>
                                                                </div>

                                                        </div>


                                                        <form method="post" name="signatories_form" id="signatories_form" action="<?php echo $request->getUri()->getPath(); ?>" style="<?php echo (@$signatories[0]->approving_name && !@$validation) ? 'display:none;' : ''; ?>">

                                                                <input type="hidden" name="leave_signatory_id" value="<?php echo @$signatories[0]->id_leave_signatory; ?>">
                                                                <input type="hidden" name="leave_id" class="leave_id" value="<?php echo @$leave[0]->id_leave; ?>">

                                                                <div class="col col-lg-12 col-md-12" style="padding-left:30px; padding-right: 30px;">

                                                                        <div class="row mb-3">
                                                                                <label class="col col-lg-3" for="">Leave credits certified by</label>
                                                                                <div class="col col-lg-9">
                                                                                    <?php if (@$validation && $validation->hasError('certifiedby')){ ?>
                                                                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('certifiedby'); ?></label></span>
                                                                                    <?php } ?>
                                                                                    <input type="text" name="certifiedby" id="certifiedby" class="form-control" value="Jonathan T. Gurion" readonly>
                                                                                </div>
                                                                        </div>

                                                                        <div class="row mb-1">
                                                                                <label class="col col-lg-3" for=""><b>*Recommended By</b></label>
                                                                                <div class="col col-lg-9">
                                                                                    <?php if (@$validation && $validation->hasError('recommending_name')){ ?>
                                                                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('recommending_name'); ?></label></span>
                                                                                    <?php }
                                                                                    
//                                                                                    $set_recommending = set_value('recommending_name') ? set_value('recommending_name') : @$head[0]->emp_fullname;
//                                                                                    $set_reco_designation = set_value('recommending_designation') ? set_value('recommending_designation') : @$head[0]->head_title.', '.@$head[0]->head_office_abbr;
//                                                                                    $set_reco_desigid = set_value('recommending_designation_id') ? set_value('recommending_designation_id') : @$head[0]->id_head;
                                                                                    
                                                                                    if(set_value('recommending_name')){
                                                                                        $set_reco_id = set_value('recommending_id');
                                                                                        $set_recommending = set_value('recommending_name');
                                                                                        $set_reco_designation = set_value('recommending_designation');
                                                                                        $set_reco_desigid = set_value('recommending_designation_id');
                                                                                    } else if(@$signatories[0]->id_leave_signatory!=''){
                                                                                        $set_reco_id = @$signatories[0]->recommending_id;
                                                                                        $set_recommending = @$signatories[0]->recommending_name;
                                                                                        $set_reco_designation = @$signatories[0]->recommending_designation;
                                                                                        $set_reco_desigid = @$signatories[0]->recommending_designation_id;
                                                                                    } else if(@$head[0]->emp_fullname!='') {
                                                                                        $set_reco_id = @$head[0]->id_employee;
                                                                                        $set_recommending = @$head[0]->emp_fullname;
                                                                                        $set_reco_designation = @$head[0]->head_title.', '.@$head[0]->head_office_abbr;
                                                                                        $set_reco_desigid = @$head[0]->id_head;
                                                                                    } else {
                                                                                        $set_reco_id = '';
                                                                                        $set_recommending = '';
                                                                                        $set_reco_designation = 'Supervisor';
                                                                                        $set_reco_desigid = '';
                                                                                    } ?>
                                                                                        
                                                                                    <input type="hidden" name="recommending_id" id="recommending_id" class="form-control" value="<?php echo $set_reco_id; ?>" maxlength="30">
                                                                                    <input type="text" name="recommending_name" id="recommending_name" class="form-control" value="<?php echo $set_recommending; ?>" maxlength="150" readonly>
                                                                                </div>
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                                <label class="col col-lg-3" for="" style="text-align: right; font-style: italic;">Designation</label>
                                                                                <div class="col col-lg-9">
                                                                                    <?php if (@$validation && $validation->hasError('recommending_designation')){ ?>
                                                                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('recommending_designation'); ?></label></span>
                                                                                    <?php }  ?>
                                                                                        
                                                                                    <input type="text" name="recommending_designation" id="recommending_designation" class="form-control" value="<?php echo $set_reco_designation; ?>" maxlength="200" readonly>
                                                                                    <input type="hidden" name="recommending_designation_id" id="recommending_designation_id" class="form-control" value="<?php echo $set_reco_desigid; ?>" maxlength="3">
                                                                                </div>
                                                                                
                                                                                <?php /*
                                                                                <select type="text" class="form-control list-signatory" name="recommending_id">
                                                                                    <option value="">Select...</option>
                                                                                    <?php 
                                                                                    $set_recommending = set_value('recommending_id') ? set_value('recommending_id') : @$signatories[0]->recommending_id.'-'.@$signatories[0]->recommending_level.'-'.@$signatories[0]->recommending_designation_id;
                                                                                    $head_level = 0;
                                                                                    foreach(@$authorized as $auth){ ?>
                                                                                        <?php if($head_level!=$auth->head_level){
                                                                                                $head_level=$auth->head_level; ?>
                                                                                                <optgroup label="<?php 
                                                                                                    if($auth->head_level==1){
                                                                                                        echo 'Executive Director level';
                                                                                                    } else if($auth->head_level==2){
                                                                                                        echo 'Branch Manager level';
                                                                                                    } else if($auth->head_level==3){
                                                                                                        echo 'Deputy Director level';
                                                                                                    } else if($auth->head_level==4){
                                                                                                        echo 'Division Head level';
                                                                                                    } else if($auth->head_level==5){
                                                                                                        echo 'Unit Head level';
                                                                                                    }
                                                                                                ?>" style="font-weight:700;">
                                                                                        <?php }
                                                                                        $auth_value = $auth->employee_id.'-'.$auth->head_level.'-'.$auth->id_designation;
                                                                                        ?>

                                                                                        <option value="<?php echo $auth_value; ?>" <?php echo $set_recommending==$auth_value ? 'selected' : ''; ?>>
                                                                                            <?php echo $auth->emp_fname.' ';
                                                                                            echo @$auth->emp_mname ? $auth->emp_mname[0].'. ' : '';
                                                                                            echo $auth->emp_lname.' ';
                                                                                            echo ' ('.$auth->designation_abbr.')'; ?>
                                                                                        </option>

                                                                                        <?php if($head_level!=$auth->head_level){ ?>
                                                                                            </optgroup>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                 * 
                                                                                 */ ?>
                                                                        </div>
                                                                        <div class="row mb-3 mt-4">
                                                                                <label class="col col-lg-3" for="">*Approved By</label>
                                                                                <div class="col col-lg-9">
                                                                                    <?php if (@$validation && $validation->hasError('approving_name')){ ?>
                                                                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('approving_name'); ?></label></span>
                                                                                    <?php } ?>
                                                                                    <input type="text" name="approving_name" id="approving_name" class="form-control" value="Evaristo A. Abella" readonly>
                                                                                </div>
                                                                                
                                                                                <?php /*<select type="text" class="form-control list-signatory" name="approving_id">
                                                                                        <optgroup label="Executive Director level">
                                                                                            <option value="<?php echo $auth2_value; ?>" <?php echo $set_approving==$auth2_value ? 'selected' : ''; ?>>
                                                                                                <?php echo $auth2->emp_fname.' ';
                                                                                                echo @$auth2->emp_mname ? $auth2->emp_mname[0].'. ' : '';
                                                                                                echo $auth2->emp_lname.' ';
                                                                                                echo ' ('.$auth2->designation_abbr.')'; ?>
                                                                                            </option>
                                                                                        </optgroup>
                                                                                </select>
                                                                                 * 
                                                                                 */ ?>
                                                                        </div>
                                                                    
                                                                </div>

                                                                <div class="col col-lg-12" id="submit_btns_div" style="display: inline-block;<?php //echo @$validation ? 'display: inline-block;' : 'display: none; '; ?> text-align: right;">

                                                                        <?php if(!@$signatories){ ?>
                                                                            <a href="<?php echo site_url('attendance/leave/add_details'); ?>" onclick="return confirm('Are you sure you want to go back?')" title="Go back to details">
                                                                                <button class="btn btn-default " id="<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="margin-right:10px;">
                                                                                    <i class="glyphicon glyphicon-arrow-left" style="padding-right:7px;"></i> Back
                                                                                </button>
                                                                            </a>
                                                                        <?php } ?>
                                                                    
                                                                        <button type="submit" class="btn btn-primary btn-lg " id="save_signatory_btn<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>">
                                                                            <i class="glyphicon glyphicon-floppy-disk" style="padding-right:7px;"></i> Save
                                                                        </button>

                                                                        <?php if(@$signatories){ ?>
                                                                            <button type="button" id="cancel_btn" class="btn-default btn " style="margin-left:15px;">Cancel</button>
                                                                        <?php } ?>
                                                                </div>

                                                        </form>

                                                    

                                                </div>
                                        </div>
                                        
                                </div>
                                        
                        </div>
                    
                    
                    
                </div>
            
        </div>


<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>

<script type="text/javascript">
        $(document).ready(function(){
            
                $('#edit_btn').on('click',function(){
                    
                        $('#edit_btn').hide();
                        $('#signatories_form').slideDown();
                        $('#signatories_div').slideUp();
                });
            
                $('#save_signatory_btn').on('click',function(){
                        //$('#signatories_form').mask('Saving... Please wait. Do not close this window.');
                });
            
                $('#cancel_btn').on('click',function(){
                    
                        $('#edit_btn').slideDown();
                        $('#signatories_form').slideUp();
                        $('#signatories_div').slideDown();
                });
                
                $('#memo_click').on('click',function(){
                    
                        $('#memo_div').slideToggle();
                });
                
                




                
        });



</script>



<?= $this->endSection('footer_jscript') ?>