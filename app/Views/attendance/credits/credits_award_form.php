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

<style type="text/css">

        table#employees_table tr:hover  {
                background-color: #EFEFEF;
        }

</style>

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

                <form method="post" name="credits_award_form" id="credits_award_form" action="<?php echo $request->getUri()->getPath(); ?>">


                        <div class="row">
                                <div class="col mb-4 col-lg-7">

                                        <div class="card mb-4">
                                                <!--<div class="card-header">
                                                    <i class="fas fa-table me-1"></i>
                                                    DataTable Example
                                                </div>-->

                                                <div class="card-body">
                                                
                                                        <section class="panel">

                                                                <div class="panel-body" style="">

                                                                        <!------------------------------------------------------>
                                                                        <div class="col-lg-12 col-md-12 col col-xs-12">

                                                                                <h3 class="boldtext">Awarding of Leave Credits Earned</h3>

                                                                                <div class="form-horizontal tasi-form">

                                                                                        <div class="form-group px-4">

                                                                                                <div class="row mt-4">
                                                                                                    <label class="col col-lg-4">*<b style="font-size:larger;">Month</b></label>
                                                                                                    <div class="col col-lg-3 <?php echo @$validation && $validation->hasError('credit_month') ? 'has-error has-feedback' : ''; ?>" style="padding-bottom: 10px;">

                                                                                                            <?php if(@$validation && $validation->hasError('credit_month')){ ?><label id="month_error_label" class="control-label"><?php echo @$validation && $validation->hasError('credit_month'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                                                                            <select name="credit_month" id="credit_month" class="form-control input-lg" style="display: inline-block; width: 200px;">
                                                                                                                <?php $last_month = date('Y-m-01',strtotime(date('Y-m-01').' - 1month'));
                                                                                                                $last_december = date('Y-12-01',strtotime(date('Y-12-01').' - 1year')); ?>
                                                                                                                <option value="<?php echo $last_december; ?>" <?php echo $last_month==$last_december ? 'selected' : ''; ?>>
                                                                                                                    <?php echo date('F Y',strtotime($last_december)); ?>
                                                                                                                </option>

                                                                                                                <?php for($i=1;$i<=12;$i++){
                                                                                                                    $date_value = date('Y-m-01',strtotime(date('Y').'-'.$i));
                                                                                                                    //$date_value = date('2017-'.$i.'-01');
                                                                                                                    if(date('Y-m',strtotime(@$salary[0]->salary_date)) <= date('Y-m',strtotime($date_value))){ ?>

                                                                                                                        <option value="<?php echo $date_value; ?>" <?php echo $last_month==$date_value ? 'selected' : ''; ?>>
                                                                                                                            <?php echo date('F Y',strtotime($date_value)); ?>
                                                                                                                        </option>

                                                                                                                <?php }
                                                                                                                } ?>
                                                                                                            </select>
                                                                                                    </div>
                                                                                                    <div class="col col-lg-5 "></div>
                                                                                                </div>


                                                                                                <div class="row">
                                                                                                    <label class="col col-lg-4">*<b style="font-size:larger;">Days Present</b></label>
                                                                                                    <div class="col col-lg-3 <?php echo @$validation && $validation->hasError('days_present') ? 'has-error has-feedback' : ''; ?>" style="padding-bottom: 10px;">
                                                                                                            <?php if(@$validation && $validation->hasError('days_present')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('days_present'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                                                                            <select name="days_present" id="days_present" class="form-control" style="height:40px; font-weight: bold; font-size: larger;">
                                                                                                                <?php foreach($days as $d){ ?>
                                                                                                                    <option value="<?php echo $d->id_credits_table; ?>">
                                                                                                                        <?php echo $d->credit_days_present; 
                                                                                                                        echo $d->credit_days_present>1 ? ' days' : ' day'; 
                                                                                                                        ?> 
                                                                                                                    </option>
                                                                                                                <?php } ?>
                                                                                                            </select>
                                                                                                    </div>
                                                                                                    <div class="col col-lg-5 "></div>
                                                                                                </div>

                                                                                                <div class="row">
                                                                                                    <label class="col col-lg-4">*<b>Days On Leave Without Pay</b></label>
                                                                                                    <div class="col col-lg-5 <?php echo @$validation && $validation->hasError('days_wopay') ? 'has-error has-feedback' : ''; ?>" style="padding-bottom: 10px;">
                                                                                                            <?php if(@$validation && $validation->hasError('days_wopay')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('days_wopay'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                                                                            <input type="text" name="days_wopay" id="days_wopay" class="form-control" value="0.00" style="text-align: right; height:40px; font-weight: bold; font-size: larger;" readonly>
                                                                                                    </div>
                                                                                                    <div class="col col-lg-3 "></div>
                                                                                                </div>

                                                                                                <div class="row">
                                                                                                    <label class="col col-lg-4">*<b>Leave Credits Earned</b></label>
                                                                                                    <div class="col col-lg-5 <?php echo @$validation && $validation->hasError('credit_earned') ? 'has-error has-feedback' : ''; ?>" style="padding-bottom: 10px;">
                                                                                                        <?php if(@$validation && $validation->hasError('credit_earned')){ ?><label class="control-label"><?php echo @$validation && $validation->hasError('credit_earned'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                                                                        <div class="col col-lg-12">
                                                                                                            <label class="col col-lg-3" style="font-size: larger;padding-top:7px;"> 
                                                                                                                <b>VL </b>
                                                                                                            </label>
                                                                                                            <div class="col col-lg-9">
                                                                                                                <input type="text" name="credit_earned" id="credit_earned" class="form-control credit_earned" value="1.250" style="text-align: right; height:40px; font-weight: bold; font-size: larger;" readonly>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col col-lg-12">
                                                                                                            <label class="col col-lg-3" style="font-size: larger;padding-top:7px;"> 
                                                                                                                <b>SL </b>
                                                                                                            </label>
                                                                                                            <div class="col col-lg-9">
                                                                                                                <input type="text" name="credit_earned2" id="credit_earned2" class="form-control credit_earned" value="1.250" style="text-align: right; height:40px; font-weight: bold; font-size: larger;" readonly>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col col-lg-3 "></div>
                                                                                                </div>

                                                                                                <div class="row mt-4">
                                                                                                    <label class="col col-lg-4"></label>
                                                                                                    <div class="col col-lg-8" style="padding-bottom: 10px;">
                                                                                                        <em style="cursor: pointer; color: #428bca;" id="view_table">View Leave credits earned table</em>
                                                                                                    </div>
                                                                                                </div>



                                                                                        </div>


                                                                                </div>
                                                                        </div>

                                                                </div>

                                                                <!------------------------------------------------------>
                                                                <div class="panel-body mt-4" style="">
                                                                        <div class="col-lg-12 col-md-12 col col-lg-12 col-xs-12" >       

                                                                                <table class="table table-hover" id="days_table" style="display:none;">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th align="center" style="width:33%;">No. of Days Present</th>
                                                                                            <th align="center" style="width:33%;">No. of Days on Leave Without Pay</th>
                                                                                            <th align="center" style="width:34%;">Leave Credits Earned</th>

                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="sortable">
                                                                                        <?php
                                                                                        if(@$days){
                                                                                        foreach (@$days as $d){ ?>

                                                                                            <tr class="odd gradeX" id="<?php  ?>">
                                                                                                <td align="center"><?php echo $d->credit_days_present; ?></td>
                                                                                                <td align="center"><?php echo $d->credit_days_wopay; ?></td>
                                                                                                <td align="center"><?php echo $d->credit_earned; ?></td>

                                                                                            </tr>
                                                                                        <?php }
                                                                                        } else { ?>
                                                                                            <tr class="odd gradeX">
                                                                                                <td colspan="4">No record.</td>
                                                                                            </tr>

                                                                                        <?php } ?>
                                                                                    </tbody>

                                                                                </table>
                                                                        </div>
                                                                </div>
                                                                <!------------------------------------------------------>

                                                        </section>

                                                </div>

                                        </div>
                            

                                </div>
                                <div class="col mb-4 col-lg-5">

                                        <div class="card mb-4">
                                                <!--<div class="card-header">
                                                    <i class="fas fa-table me-1"></i>
                                                    DataTable Example
                                                </div>-->

                                                <div class="card-body">

                                                <section class="panel">

                                                        <div class="panel-body" style="">
                                                                <!------------------------------------------------------>
                                                                <div class="col-lg-12 col-md-12 col col-lg-12 col-xs-12">       

                                                                        <h4 class="boldtext">Select Employee</h4>

                                                                        <div class="form-horizontal tasi-form">

                                                                                <div class="form-group">

                                                                                        <div class="col col-lg-12" style="display: inline-block;">
                                                                                            <label class="col col-lg-3">*<b>Employee</b></label>
                                                                                            <div class="col col-lg-9 <?php echo @$validation && $validation->hasError('employee_id') ? 'has-error has-feedback' : ''; ?>" id="employee_form_div" style="padding-bottom: 10px;">
                                                                                                    <?php if(@$validation && $validation->hasError('employee_id')){ ?><label id="employee_error_label" class="control-label"><?php echo @$validation && $validation->hasError('employee_id'); ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                                                                    <select class="form-control" name="employee_id" id="employee_id" >
                                                                                                        <option value="">Select employee...</option>
                                                                                                        <option value="all" <?php echo set_value('employee_id')=='all' ? 'selected' : ''; ?>>...by batch</option>
                                                                                                        <?php foreach($employees as $emp){
                                                                                                            if(@$emp->credits){ ?>
                                                                                                                <option value="<?php echo $emp->id_employee; ?>" <?php echo set_value('employee_id')==$emp->id_employee ? 'selected' : ''; ?>>
                                                                                                                    <?php echo strtoupper($emp->emp_lname).', '.$emp->emp_fname.' '.@$emp->emp_mname; ?>
                                                                                                                </option>
                                                                                                        <?php }
                                                                                                        } ?>
                                                                                                    </select>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col col-lg-12" style="<?php echo @$emp_error=='' || set_value('employee_id')!='all' ? 'display: none;' : 'display:inline-block;'; ?>" id="employees_check">
                                                                                            <label class="col col-lg-2"></label>
                                                                                            <div class="col col-lg-10 <?php echo @$emp_error!='' ? 'has-error has-feedback' : ''; ?>" id="employee_form_div" style="padding-bottom: 10px;">
                                                                                                    <?php if(@$emp_error!=''){ ?><label id="employee_error_label" class="control-label"><?php echo @$emp_error; ?></label><span class="glyphicon glyphicon-warning-sign form-control-feedback"></span><?php } ?>
                                                                                                    <table id="employees_table" style="width: 100%;">
                                                                                                    <tr>
                                                                                                        <td>#</td>
                                                                                                        <td>Name</td>
                                                                                                        <td>Status</td>
                                                                                                    </tr>
                                                                                                    <?php $i=0;
                                                                                                    foreach($employees as $emp){ 
                                                                                                        if(@$emp->credits){
                                                                                                            $i++; ?>
                                                                                                                <tr>
                                                                                                                    <td><?php echo $i.'.'; ?></td>
                                                                                                                    <td>
                                                                                                                        <div class="checkbox">
                                                                                                                            <label>
                                                                                                                                <input type="checkbox" name="employee[]" value="<?php echo $emp->id_employee; ?>" class="checkbox employee_checkbox" <?php echo 'checked'; ?>>
                                                                                                                                <?php echo strtoupper($emp->emp_lname).', '.$emp->emp_fname.' '.@$emp->emp_mname; ?><br>
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td align="<?php echo $emp->emp_status!=11001 ? 'right': 'left'; ?>">
                                                                                                                        <?php echo $emp->status_name; ?>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                        <?php }
                                                                                                    } ?>
                                                                                                </table>
                                                                                                <button type="button" id="uncheckall_btn" class="btn-danger btn uncheckall_btn" style="margin-top:15px;">Uncheck all</button>
                                                                                                <button type="button" id="checkall_btn" class="btn-success btn checkall_btn" style="margin-top:15px;">Check all</button>
                                                                                            </div>
                                                                                        </div>

                                                                                </div>

                                                                                <div class="form-group" id="dp3">
                                                                                        <div class="col col-lg-12">
                                                                                            <a href="<?php echo site_url('attendance/leave'); ?>" onclick="return confirm('Are you sure you want to leave?')">
                                                                                                <button type="button" id="cancel" class="btn-default btn cancel_btn" style="margin-left:15px;float: right;">Cancel</button>
                                                                                            </a>
                                                                                            <button type="button" class="btn btn-info btn-lg save_button" id="submit_btn" style="display: inline-block; float: right;"><i class="glyphicon glyphicon-save"></i> Submit</button>
                                                                                        </div>
                                                                                </div>
                                                                        </div>

                                                                </div>
                                                        </div>
                                                </section>



                                                </div>

                                        </div>


                                </div>

                        </div>
                </form>
        </div>





<section id="main-content">
	<section class="wrapper">
		<div class="row">
             
                    
			<div class="col-lg-6 col-md-12">
                            


                                <div class="col-lg-6 col-md-12">

                                </div>
                        </form>
                                                            
		</div>
	</section>
</section>

<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>

<script type="text/javascript">
        $(document).ready(function(){
            
                
                var credits_earned_url = '<?php echo site_url('credits/load_earned'); ?>';
                
                $('#employee_id').on('change',function(){
                    if($(this).val()=='all'){
                        $('#employees_check').slideDown();
                    } else {
                        $('#employees_check').slideUp();
                    }
                });
                
                $('#view_table').on('click',function(){
                    $('#days_table').slideDown();
                });
                
                $('.uncheckall_btn').on('click',function(){
                    //employee_checkbox
                    $('.employee_checkbox').prop("checked", false).trigger("change");
                });
                $('.checkall_btn').on('click',function(){
                    //employee_checkbox
                    $('.employee_checkbox').prop("checked", true).trigger("change");
                });
                
                $('.save_button').on('click',function(){
                    $('form[name=credits_award_form]').submit();
                        
                });
                
                //$('body').mask('Saving credits earned... Please don\'t close.');
                
                $('#days_present').on('change',function(){
                            var post_data={};
                            post_data['day_id']=$(this).val();

                            $.ajax({
                                    url: credits_earned_url,
                                    type: 'POST',
                                    data: post_data,
                                    success:function(result){
                                            var json = $.parseJSON(result);
                                            $(json).each(function(i,val){
                                                $('#days_wopay').val(val.credit_days_wopay);
                                                //$('#credit_earned').val(val.credit_earned);
                                                $('.credit_earned').val(val.credit_earned);
                                            });
                                    }
                            });
                });

        });
        
</script>


<?= $this->endSection('footer_jscript') ?>