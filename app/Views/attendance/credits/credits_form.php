<?php

// Get the request service
$request = \Config\Services::request();

?>

<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?= site_url('holidays'); ?>" class="btn btn-light"  role="button" style="">
                            <i class="fas fa-arrow-circle-left" style=""></i>
                            <div style="color: #999;">Back</div>
                        </a>
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>
  
  <style>
  </style>
        
        <!-- Bootstrap Datepicker CSS -->
        <link rel="stylesheet" href="<?= base_url('public/assets/bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.css'); ?>">
        
        <script type="text/javascript">
                $(document).ready(function(){
                        $('.datepicker').datepicker({
                            format: 'mm/dd/yyyy',
                            autoclose: true,
                            todayHighlight: true,
                            beforeShowDay: function(date) {
                                
                            },
                        });
                    

                });
        </script>
        
<div style="min-height: 50vh">

        <div class="card mb-4 col-lg-6">

                <div class="card-body">
                    
                        <h2 class="mt-2 mb-4">Add Leave Credits</h2>
                    
                        <form method="post" role="form" name="holiday_form" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); //echo site_url('attendance/holidays/submit'); //  ?>">

                                <div class=" col-lg-12 col-md-12">

                                        <div class="container">


                                                <div class="row">
                                                    <label class="col col-lg-3">*<b>Employee</b></label>
                                                    <div class="col col-lg-9 <?php echo @$validation && $validation->hasError('emp_idno') ? 'has-error has-feedback' : ''; ?>" id="employee_form_div" style="padding-bottom: 10px;">
                                                            <?php if(@$validation && $validation->hasError('emp_idno')){ ?><label id="employee_error_label" class="control-label"><?php echo @$validation && $validation->hasError('emp_idno'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                            <select class="form-control" name="emp_idno" id="emp_idno" >
                                                                <option value="">Select employee...</option>
                                                                <?php foreach($employees as $emp){ ?>
                                                                    <option value="<?php echo $emp->emp_idno; ?>" <?php echo set_value('emp_idno')==$emp->emp_idno ? 'selected' : ''; ?>>
                                                                        <?php echo strtoupper($emp->emp_lname).', '.$emp->emp_fname.' '.@$emp->emp_mname; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                    </div>
                                                </div>

                                                <div class="row" style="clear:both;">
                                                    <label class="col col-lg-4">*<b>Vacation Leave</b></label>
                                                    <div class="col col-lg-8 <?php echo @$validation && $validation->hasError('vl') ? 'has-error has-feedback' : ''; ?>" id="" style="padding-bottom: 10px;">
                                                        <?php if(@$validation && $validation->hasError('vl')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('vl'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                        <input type="text" name="vl" id="vl" class=" form-control" value="<?php echo set_value('vl') ? set_value('vl') : @$details[0]->vl; ?>" onkeypress="validateamount(event)" maxlength="7" >
                                                    </div>
                                                </div>

                                                <div class="row" style="clear:both;">
                                                    <label class="col col-lg-4">*<b>Sick Leave</b></label>
                                                    <div class="col col-lg-8 <?php echo @$validation && $validation->hasError('sl') ? 'has-error has-feedback' : ''; ?>" id="" style="padding-bottom: 10px;">
                                                        <?php if(@$validation && $validation->hasError('sl')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('sl'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                        <input type="text" name="sl" id="sl" class=" form-control" value="<?php echo set_value('sl') ? set_value('sl') : @$details[0]->sl; ?>" onkeypress="validateamount(event)" maxlength="7" >
                                                    </div>
                                                </div>

                                                <div class="row" style="clear:both;">
                                                    <label class="col col-lg-4">*<b>Special Leave Privilege</b></label>
                                                    <div class="col col-lg-8 <?php echo @$validation && $validation->hasError('slp') ? 'has-error has-feedback' : ''; ?>" id="" style="padding-bottom: 10px;">
                                                        <?php if(@$validation && $validation->hasError('slp')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('slp'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                        <input type="text" name="slp" id="slp" class=" form-control" value="<?php echo set_value('slp') ? set_value('slp') : @$details[0]->slp; ?>" onkeypress="validateamount(event)" maxlength="6" >
                                                    </div>
                                                </div>

                                                <div class="row" style="clear:both;">
                                                    <label class="col col-lg-4">*<b>Leave Credits as of date</b></label>
                                                    <?php if (@$validation && $validation->hasError('forward_date')){ ?>
                                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('holiday_date'); ?></label></span>
                                                    <?php } ?>
                                                    <div class="col col-lg-8 <?php echo @$validation && $validation->hasError('forward_date') ? 'has-error has-feedback' : ''; ?>" id="" style="padding-bottom: 10px;">
                                                        <?php $forward_date = set_value('forward_date') ? set_value('forward_date') : @$details[0]->last_updated; ?>

                                                        <div class="datepicker-wrapper <?php echo (@$validation && $validation->hasError('forward_date')) ? 'has-error' : '' ?> ">
                                                            <input type="text" name="forward_date" id="forward_date" class="datepicker form-control" value="<?= $forward_date!='' ? date('m/d/Y',strtotime($forward_date)) : ''; ?>" autocomplete="off" readonly placeholder="m/d/Y" >
                                                            <span class="calendar-icon"><i class="fa fa-regular fa-calendar-days"></i></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="clear:both;">
                                                    <label class="col col-lg-4">Remarks</label>
                                                    <div class="col col-lg-8" id="" style="padding-bottom: 10px;">
                                                        <textarea name="remarks" id="remarks" class="form-control" maxlength="150"><?php echo set_value('remarks') ? set_value('remarks') : @$details[0]->remarks; ?></textarea>
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="form-group col-lg-12 col-md-12" style="text-align: right;">

                                                <button type="submit" id="save" class="btn btn-lg btn-success " role="button">Save</button>
                                                <button type="button" id="cancel" class="btn btn-light" role="button">Cancel</button>
                                        </div>
                                </div>

                        </form>

                </div>
        </div>
</div>

<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>
        
<!-- Bootstrap 5 JS and Popper -->
<!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>-->

<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

        <script type="text/javascript">
                $(document).ready(function(){
                    

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