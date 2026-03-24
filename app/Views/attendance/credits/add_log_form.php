<?php
// Get the request service
$request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?php echo site_url('/credits'); ?>" class="btn btn-light text-secondary"  role="button" onclick="return confirm('There are unsaved changes. Are you sure you want to leave?')">
                            <i class="fas fa-arrow-circle-left" style=""></i>
                            <div style="color: #999;">Back to List</div>
                        </a>
                    </li>
                    
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>

        <link rel="stylesheet" href="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.css'); ?>">

<script type="text/javascript">
        $(document).ready(function(){

                $('.datepicker').datepicker({
                    format: 'mm/dd/yyyy',
                    autoclose: true,
                    todayHighlight: true,

                });
        
        });
</script>

<div style="min-height: 50vh">
    
		<div class="row">
                    
                    
                        <div class="col mb-4 col-lg-6" style="">

                                <div class="card mb-4">
                                        <!--<div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            DataTable Example
                                        </div>-->

                                        <div class="card-body">

                                                <section class="panel">

                                                        <div class="panel-body" style="">

                                                                <h3 class="boldtext" id="new_form">Add Log to Leave Card</h3>    

                                                                <div class="form-horizontal tasi-form">

                                                                        <form method="post" role="form" name="edit_credit_log" id="edit_credit_log" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); ?>">



                                                                                <div class="form-group px-4">


                                                                                        <div class="col-sm-12" style="display: inline-block; clear:both;">
                                                                                            <label class="col-sm-3 control-label">Employee</label>
                                                                                            <div class="col-sm-9 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                                                                                                <?php echo @$details[0]->emp_fname.' ';
                                                                                                echo @$details[0]->emp_mi!='' ? @$details[0]->emp_mi.'. ' : '';
                                                                                                echo @$details[0]->emp_lname.' '.@$details[0]->emp_extname; ?>
                                                                                                <input type="hidden" name="emp_idno" value="<?php echo @$details[0]->emp_idno; ?>" >
                                                                                                <input type="hidden" name="employee_id" value="<?php echo @$details[0]->employee_id; ?>" >
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-12" style="clear:both;">
                                                                                            <label class="col-sm-3 control-label">*<b>Log Type</b></label>
                                                                                            <div class="col-sm-9 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('log_type')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('log_type'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <?php $set_log_type = set_value('log_type') ? set_value('log_type') : @$details[0]->log_type_id; ?>
                                                                                                <select name="log_type" id="log_type" class="form-control">
                                                                                                    <?php foreach($log_types as $log){ ?>
                                                                                                        <option value="<?php echo $log->id_log_type; ?>" <?php echo $set_log_type==$log->id_log_type ? 'selected': ''; ?>>
                                                                                                            <?php echo $log->log_type_name; ?>
                                                                                                        </option>
                                                                                                    <?php } ?>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-12" style="clear:both;">
                                                                                            <label class="col-sm-3 control-label">Vacation Leave</label>
                                                                                            <div class="col-sm-9 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('vl')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('vl'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <?php $set_vl = set_value('vl') ? set_value('vl') : @$details[0]->vl; ?>
                                                                                                <input type="text" name="vl" id="vl" class=" form-control" value="<?php echo $set_vl!='' ? $set_vl : '0'; ?>" onkeypress="validateamount(event)" maxlength="7" >
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-12" style="clear:both;">
                                                                                            <label class="col-sm-3 control-label">Sick Leave</label>
                                                                                            <div class="col-sm-9 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('sl')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('sl'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <?php $set_sl = set_value('sl') ? set_value('sl') : @$details[0]->sl; ?>
                                                                                                <input type="text" name="sl" id="sl" class=" form-control" value="<?php echo $set_sl!='' ? $set_sl : '0'; ?>" onkeypress="validateamount(event)" maxlength="7" >
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-12" style="clear:both;">
                                                                                            <label class="col-sm-3 control-label">*<b>Log date</b></label>
                                                                                            <div class="col-sm-9 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('log_date')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('log_date'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <input type="text" name="log_date" id="log_date" class="datepicker form-control" value="<?php echo set_value('log_date') ? set_value('log_date') : date('m/d/Y',strtotime(@$details[0]->credit_log_date)); ?>" placeholder="YYYY-mm-dd" >

                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-sm-12" style="clear:both;">
                                                                                            <label class="col-sm-3 control-label">*<b>Leave Card Remarks</b></label>
                                                                                            <div class="col-sm-9 mb-2">
                                                                                                <?php if(@$validation && $validation->hasError('remarks')){ ?>
                                                                                                    <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('remarks'); ?></label></span>
                                                                                                <?php } ?>
                                                                                                <textarea name="remarks" id="remarks" class="form-control" maxlength="150"><?php echo set_value('remarks') ? set_value('remarks') : @$details[0]->log_remarks; ?></textarea>
                                                                                                <blockquote>
                                                                                                    <b>e.g.</b>
                                                                                                    <ul style="margin-left:30px;">
                                                                                                        <li style="list-style: disc;">2VL=Forced Leave</li>
                                                                                                        <li style="list-style: disc;">1VL=13(AWOL)</li>
                                                                                                        <li style="list-style: disc;">0.65VL=26(undertime)</li>
                                                                                                        <li style="list-style: disc;">2sl=1,4(excused absent)</li>
                                                                                                    </ul>
                                                                                                </blockquote>
                                                                                            </div>
                                                                                        </div>
                                                                                </div>

                                                                                <span style="font-style: italic; float:right;">Updating this will recompute the '<b>Leave Credits</b>' of the employee.</span>

                                                                                <br>
                                                                                <br>
                                                                                <button type="submit" class="btn btn-primary btn-lg" name="importsubmit" id="" style="display: inline-block; margin-top: -10px; float: right;">Save</button>


                                                                        </form>
                                                                </div>
                                                        </div>
                                                </section>

                                        </div>
                                </div>
                        </div>

                        <div class="col mb-4 col-lg-6" id="div_reference" style="<?php echo $set_log_type!=4 ? 'display:none;' : ''; ?>">

                                <div class="card mb-4">
                                        <!--<div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            DataTable Example
                                        </div>-->

                                        <div class="card-body">

                                                <section class="panel">

                                                        <div class="panel-body" style="">

                                                                <div class="form-horizontal tasi-form">
                                                                    <h3 class="boldtext">For reference:</h4>

                                                                    <h4>Conversion of Working Minutes into Fractions of a Day</h4>
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th style="text-align:center">Minutes</th>
                                                                                <th style="text-align:center">Equivalent Day</th>
                                                                                <th style="text-align:center">Minutes</th>
                                                                                <th style="text-align:center">Equivalent Day</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if(@$minutes){
                                                                            for($min1=0,$min2=30;$min1<30;$min1++,$min2++){ ?>
                                                                            <tr>
                                                                                    <td align="center"><?php echo $minutes[$min1]->minute_num; ?></td>
                                                                                    <td align="center"><?php echo $minutes[$min1]->equivalent_day; ?></td>
                                                                                    <td align="center"><?php echo $minutes[$min2]->minute_num; ?></td>
                                                                                    <td align="center"><?php echo $minutes[$min2]->equivalent_day; ?></td>
                                                                            </tr>
                                                                            <?php }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>

                                                                    <br>
                                                                    <h4>Conversion of Working Hours into Fractions of a Day</h4>
                                                                    <table class="table table-hover" style="">
                                                                        <thead>
                                                                            <tr>
                                                                                <th colspan="2" style="text-align:center">Based on 8-hour Workday</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="text-align:center">Hours</th>
                                                                                <th style="text-align:center">Equivalent Day</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php if(@$hours){
                                                                            foreach($hours as $hr){ ?>
                                                                                <tr>
                                                                                    <td align="center"><?php echo $hr->hour_num; ?></td>
                                                                                    <td align="center"><?php echo $hr->equivalent_day; ?></td>
                                                                                </tr>
                                                                            <?php }
                                                                            } ?>
                                                                        </tbody>
                                                                    </table>
                                                                    <br>

                                                                </div>

                                                        </div>
                                                </section>

                                        </div>
                                </div>
                        </div>

                        
                    
                </div>
</div>


<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>

<!-- Bootstrap Datepicker JS -->
<script src="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>

<script type="text/javascript">
        $(document).ready(function(){
            
                $('#log_type').on('change',function(){
                    if($(this).val()==4){
                        $('#div_reference').slideDown();
                    } else {
                        $('#div_reference').slideUp();
                    }
                });
    
        });
        
                function validateamount(evt) {
                        var theEvent = evt || window.event;
                        var key = theEvent.keyCode || theEvent.which;
                        key = String.fromCharCode( key );
                        var regex = /[0-9]|[\b]|[\.]|[\t]/;
                        if( !regex.test(key) ) {
                              theEvent.returnValue = false;
                              if(theEvent.preventDefault) theEvent.preventDefault();
                        }
                }   
</script>

<?= $this->endSection('footer_jscript') ?>