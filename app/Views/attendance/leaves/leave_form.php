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

                <div class="card mb-4 col-lg-12">
                    <?= $this->include('attendance/leaves/leave_form_progress') ?>
                </div>

                <div class="card mb-4 col-lg-7">
                        <!--<div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>-->

                        <div class="card-body">

                                <h2 class="mt-2 mb-4">Application for Leave</h2>

                                <form method="post" role="form" name="holiday_form" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); //echo site_url('attendance/holidays/submit'); //  ?>">

                                        <div class="col-lg-12 col-md-12" id="employee_div">


                                                <div class="container mb-2">

                                                    <input type="hidden" name="access_level" value="<?php echo $access_level; ?>">

                                                    <div class="row">
                                                            <label class="col col-lg-3">*<b>Employee</b></label>
                                                            <div class="col col-lg-9 <?php echo @$validation && $validation->hasError('employee_id') ? 'has-error has-feedback' : ''; ?>">
                                                                <?php if(@$validation && $validation->hasError('employee_id')){ ?><span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label">Please select an employee.</label></span><?php } ?>
                                                                <div class="input-group">
                                                                    
                                                                        <?php if($access_level==6){
                                                                            
                                                                                $fullname = $mydetails[0]->emp_fname.' ';
                                                                                if(@$mydetails[0]->emp_mi!=''){
                                                                                    $fullname .= $mydetails[0]->emp_mi.'. ';
                                                                                } else if(@$mydetails[0]->emp_mname!=''){
                                                                                    $fullname .= $mydetails[0]->emp_mname[0].'. ';
                                                                                }$fullname .= $mydetails[0]->emp_lname;
                                                                                $fullname .= @$mydetails[0]->emp_extname!='' ? ' '.@$mydetails[0]->emp_extname : '';
                                                                                
                                                                                
                                                                                $set_position_name = set_value('position_name') ? set_value('position_name') : @$mydetails[0]->position_name;
                                                                                $set_position_id = set_value('position_id') ? set_value('position_id') : @$mydetails[0]->emp_position;
                                                                                $set_salary = set_value('monthly_salary') ? set_value('monthly_salary') : @$mydetails[0]->salary;
                                                                                ?>
                                                                    
                                                                                <input type="text" id="employee_autocomplete" value="<?php echo $fullname; ?>" class="form-control" readonly style="padding: 10px; font-size:16px; height: 50px;" >
                                                                                
                                                                                <input type="hidden" value="<?php echo session()->get('empid'); ?>" name="employee_id" id="employee_id"/>
                                                                                <input type="hidden" value="<?php echo session()->get('emp_idno'); ?>" name="emp_idno" id="emp_idno"/>
                                                                                
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_lname; ?>" name="emp_lname" id="emp_lname"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_fname; ?>" name="emp_fname" id="emp_fname"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_mname; ?>" name="emp_mname" id="emp_mname"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_mi; ?>" name="emp_mi" id="emp_mi"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_extname; ?>" name="emp_extname" id="emp_extname"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_sex; ?>" name="emp_sex" id="emp_sex"/>

                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_office; ?>" name="emp_office" id="emp_office"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_division; ?>" name="emp_division" id="emp_division"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_unit; ?>" name="emp_unit" id="emp_unit"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_subunit; ?>" name="emp_subunit" id="emp_subunit"/>
                                                                                <input type="hidden" value="<?php echo $mydetails[0]->emp_class_name; ?>" name="emp_class_name" id="emp_class_name"/>
                                                                                
                                                                                
                                                                        <?php } else if($access_level>0){
                                                                            
                                                                                
                                                                                $set_position_name = set_value('position_name') ? set_value('position_name') : '';
                                                                                $set_position_id = set_value('position_id') ? set_value('position_id') : '';
                                                                                $set_salary = set_value('monthly_salary') ? set_value('monthly_salary') : 0;
                                                                        
                                                                            
                                                                                if(@$employees){ ?>

                                                                                        <select name="emp_idno" class="search_items form-control" id="employee_dropdown" style="padding: 10px; font-size:16px; height: 50px;">
                                                                                            <option value="">Select Employee</option>
                                                                                            <?php $set_emp_idno = set_value('emp_idno') ? set_value('emp_idno') : '';
                                                                                            if(@$employees){
                                                                                                $i=0;
                                                                                                foreach($employees as $emp){ $i++; ?>
                                                                                                        <option value="<?php echo $emp->emp_idno; ?>" <?php echo $set_emp_idno==$emp->emp_idno ? 'selected' : ''; ?>>
                                                                                                            <?php echo strtoupper($emp->emp_lname).', '.$emp->emp_fname.' '.$emp->emp_extname.' ';
                                                                                                            echo @$emp->emp_mi!='' ? $emp->emp_mi.'. ' : $emp->emp_mname;
                                                                                                            //echo 'Employee '.$i; // for live demo testing
                                                                                                            ?>
                                                                                                        </option>
                                                                                            <?php }
                                                                                            } ?>
                                                                                        </select>
                                                                                        <span class="input-group-addon">
                                                                                             <i class="glyphicon glyphicon-search"></i>
                                                                                        </span>

                                                                                <?php } else { ?>
                                                                                        <div class="alert alert-danger" id="error" role="alert">
                                                                                            For regular employees only.
                                                                                        </div>
                                                                                <?php } ?>
                                                                                
                                                                                <input type="hidden" value="<?php echo set_value('employee_id'); ?>" name="employee_id" id="employee_id"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_lname'); ?>" name="emp_lname" id="emp_lname"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_fname'); ?>" name="emp_fname" id="emp_fname"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_mname'); ?>" name="emp_mname" id="emp_mname"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_mi'); ?>" name="emp_mi" id="emp_mi"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_extname'); ?>" name="emp_extname" id="emp_extname"/>

                                                                                <input type="hidden" value="<?php echo set_value('emp_program'); ?>" name="emp_program" id="emp_program"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_office'); ?>" name="emp_office" id="emp_office"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_division'); ?>" name="emp_division" id="emp_division"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_unit'); ?>" name="emp_unit" id="emp_unit"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_unit'); ?>" name="emp_subunit" id="emp_subunit"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_sex'); ?>" name="emp_sex" id="emp_sex"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_solo'); ?>" name="emp_solo" id="emp_solo"/>
                                                                                <input type="hidden" value="<?php echo set_value('emp_class_name'); ?>" name="emp_class_name" id="emp_class_name"/>
                                                                                
                                                                        <?php } /*else { 
                                                                                $set_employee_autocomplete = set_value('employee_autocomplete') ? set_value('employee_autocomplete') : ''; ?>
                                                                            
                                                                                <input type="text" id="employee_autocomplete" name="employee_autocomplete" value="<?php echo $set_employee_autocomplete; ?>" class="form-control" <?php echo $set_employee_autocomplete!='' && set_value('employee_id')!='' ? 'readonly' : ''; ?> style="padding: 10px; font-size:16px; height: 50px;" placeholder="Search Employee" >
                                                                                <span class="input-group-addon">
                                                                                     <i class="glyphicon glyphicon-search"></i>
                                                                                </span>
                                                                                
                                                                                <input type="hidden" value="<?php echo set_value('emp_idno'); ?>" name="emp_idno" id="emp_idno"/>
                                                                                
                                                                        <?php }*/ ?>
                                                                                
                                                                </div><!-- /input-group -->
                                                                
                                                            </div>
                                                    </div>
                                                </div>

                                                <div class="container mb-2">
                                                    <div class="row">
                                                            <label class="col col-lg-3">Position</label>
                                                            <div class="col col-lg-9 <?php echo @$validation && $validation->hasError('position_name') ? 'has-error has-feedback' : ''; ?>">
                                                                <?php if(@$validation && $validation->hasError('position_name') ){ ?><span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label">Position field is required.</label></span><?php } ?>
                                                                <input type="text" style="width: 90%;" name="position_name" id="position_name" class="form-control m-bot15" readonly value="<?php echo $set_position_name; ?>" />
                                                                <input type="hidden" name="position_id" id="position_id" value="<?php echo $set_position_id; ?>" />
                                                            </div>
                                                    </div>
                                                </div>

                                                <div class="container mb-2">
                                                    <div class="row">
                                                        <label class="col col-lg-3">Monthly Salary</label>
                                                        <div class="col col-lg-9">
                                                            <?php  ?>
                                                            <input type="text" style="width: 90%;" name="monthly_salary" id="monthly_salary" class="form-control m-bot15" readonly value="<?php echo number_format($set_salary,2,".",","); ?>" />
                                                        </div>
                                                    </div>
                                                </div>

                                            <hr>

                                                <div class="container mb-2" id="leave_credits_div" style="">
                                                    <div class="row">
                                                        <h4 class="boldtext" style="padding-left:20px;">Leave Credits</h4>

                                                        <!--<div class="col-xs-12" style="display: inline-block;">
                                                            <label style="text-align: right; padding-top:0px; font-size: 13px;">As of</label>
                                                            <div class="col-xs-9">
                                                                <span style="text-decoration: underline; font-weight: bold; color: darkred;" id="asofdate"></span>
                                                            </div>
                                                        </div>-->

                                                        <div class="col col-lg-12" style="display: inline-block;margin-left:30px">
                                                            
                                                                <input type="hidden" name="credit_id" class="credit_id" value="<?php echo @$mydetails[0]->credit_id; ?>">
                                                                <input type="hidden" name="asofdate" class="asofdate" value="<?php echo @$mydetails[0]->credits_asof; ?>">
                                                                <input type="hidden" name="asofvl" class="asofvl" value="<?php echo @$mydetails[0]->vl; ?>">
                                                                <input type="hidden" name="asofsl" class="asofsl" value="<?php echo @$mydetails[0]->sl; ?>">
                                                                <input type="hidden" name="asofslp" class="asofslp" value="<?php echo @$mydetails[0]->slp; ?>">
                                                                
                                                                <table style="width: 90%;" border="1" style="">
                                                                    <tr>
                                                                        <td style="width:22%;"></td>
                                                                        <td style="width:26%; text-align: center; padding: 5px; font-weight: bold;">Vacation Leave</td>
                                                                        <td style="width:26%; text-align: center; padding: 5px; font-weight: bold;">Sick Leave</td>
                                                                        <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">SPL</td>
                                                                        <td style="width:16%; text-align: center; padding: 5px; font-weight: bold;">Service Credit</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 5px;">Total Earned
                                                                            <br>
                                                                            <label style="text-align: right; padding-top:0px; font-size: 13px;">as of</label>
                                                                            <span style="text-decoration: underline; font-weight: bold; color: darkred;" id="asofdate"><?php echo @$mydetails[0]->credits_asof; ?></span>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofvl">   
                                                                            <?php echo @$mydetails[0]->vl; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofsl">
                                                                            <?php echo @$mydetails[0]->sl; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofslp">
                                                                            <?php echo @$mydetails[0]->slp; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofservice">
                                                                            <?php echo @$mydetails[0]->service; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <input type="hidden" name="vl_earmarked" class="vl_earmarked" value="<?php echo @$mydetails[0]->vl_earmarked; ?>">
                                                                        <input type="hidden" name="sl_earmarked" class="sl_earmarked" value="<?php echo @$mydetails[0]->sl_earmarked; ?>">
                                                                        <input type="hidden" name="slp_earmarked" class="slp_earmarked" value="<?php echo @$mydetails[0]->slp_earmarked; ?>">
                                                                        <input type="hidden" name="balance_vl" class="balance_vl" value="<?php echo @$mydetails[0]->balance_vl; ?>">
                                                                        <input type="hidden" name="balance_sl" class="balance_sl" value="<?php echo @$mydetails[0]->balance_sl; ?>">
                                                                        <input type="hidden" name="balance_slp" class="balance_slp" value="<?php echo @$mydetails[0]->balance_slp; ?>">

                                                                        <td style="padding: 5px; text-align: right; font-style: italic;">Earmarked (Pending)</td>
                                                                        <td style="text-align: center; padding: 5px;" id="vl_earmarked">
                                                                            <?php echo @$mydetails[0]->vl_earmarked; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px;" id="sl_earmarked">
                                                                            <?php echo @$mydetails[0]->sl_earmarked; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px;" id="slp_earmarked">
                                                                            <?php echo @$mydetails[0]->slp_earmarked; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px;" id="service_earmarked">
                                                                            <?php echo @$mydetails[0]->service_earmarked; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr style="background-color:#ffb4ae;">
                                                                        <td style="padding: 5px; text-align: right; font-weight: bold; font-size: larger;">Balance</td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold; font-size: larger;" id="balance_vl">
                                                                            <?php echo @$mydetails[0]->balance_vl; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold; font-size: larger;" id="balance_sl">
                                                                            <?php echo @$mydetails[0]->balance_sl; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold; font-size: larger;" id="balance_slp">
                                                                            <?php echo @$mydetails[0]->balance_slp; ?>
                                                                        </td>
                                                                        <td style="text-align: center; padding: 5px; font-weight: bold; font-size: larger;" id="balance_service">
                                                                            <?php echo @$mydetails[0]->balance_service; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php /*
                                                                    <tr>
                                                                        <td style="padding: 5px;">Less this application</td>
                                                                        <td style="text-align: center; padding: 5px;"></td>
                                                                        <td style="text-align: center; padding: 5px;"></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="padding: 5px;">Balance</td>
                                                                        <td style="text-align: center; padding: 5px;"></td>
                                                                        <td style="text-align: center; padding: 5px;"></td>
                                                                    </tr>*/ ?>
                                                                </table>
                                                                <br><em>Note: To be updated by HRMO.</em>
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="container mb-2" style="padding-bottom: 30px;">

                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <a href="<?php echo site_url('attendance/leave'); ?>" onclick="return confirm('There are unsaved changes. Are you sure you want to leave?')">
                                                                <button type="button" id="cancel" class="btn-default btn cancel_btn" style="margin-left:15px;float: right;">Cancel</button>
                                                            </a>
                                                            <?php if(@$employees || session()->get('access_level')==6){ ?>
                                                                <button type="submit" class="btn btn-success btn-lg save_button1" id="submit_btn" style="display: inline-block; float: right;">
                                                                    <i class="glyphicon glyphicon-save"></i> Create
                                                                </button>
                                                            <?php } ?>
                                                        </div>
                                                    </div>

                                                </div>

                                        </div>

                                </form>

                        </div>
                </div>


        </div>

<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

        <script src="<?php echo js('myscript/leave_form_add.js'); ?>"></script>

        <script type="text/javascript">
                $(document).ready(function(){
                    search_employee = '<?php echo site_url('attendance/leaves/search_employee'); ?>';
                    employee_details_url = '<?php echo site_url('attendance/leaves/load_employee_details'); ?>';
                    

                });
        </script>

<?= $this->endSection('footer_jscript') ?>