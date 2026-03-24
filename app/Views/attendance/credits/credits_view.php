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
                    <!--
                    <li style="">
                        <a href="<?php echo site_url('/credits/edit'); ?>" class="btn btn-light text-danger"  role="button" onclick="return confirm('Are you sure you want to cancel this leave? This cannot be undone.')">
                            <i class="fas fa-ban" style=""></i>
                            <div style="color: #999;">Cancel</div>
                        </a>
                    </li>-->
                    
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>


<script type="text/javascript">
        $(document).ready(function(){

                $( ".datepicker" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
        
        });
</script>


        <div style="min-height: 50vh">

                <div class="row">
                        <div class="col mb-4 col-lg-7">
                                <div class="card mb-4">
                                        <!--<div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            DataTable Example
                                        </div>-->

                                        <div class="card-body">
                                            
                                                <h3 class="boldtext">Leave Card 
                                                    <?php //echo $year; ?>
                                                    <select class="form-control" id="credit_year" style="font-size: 20px; font-weight: bold; padding: inherit; text-align: center; width:100px; display: inline-block;">
                                                        <option value="<?php echo date('Y'); ?>"><?php echo date('Y'); ?></option>
                                                        <?php if(@$years){
                                                            foreach($years as $yr){ ?>
                                                                <option value="<?php echo $yr->log_year; ?>" <?php echo $year==$yr->log_year ? 'selected' : ''; ?>><?php echo $yr->log_year; ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </h3>
                                            
                                                <div class="col-lg-12 col-md-12 col col-lg-12 col-xs-12 px-4">
                                                        <div class="form-horizontal tasi-form">

                                                                <?php //$this->load->view('credits/credits_view_details'); ?>


                                                                <div class="form-group">

                                                                        <div class="row" style="clear:both;">
                                                                            <label class="col col-lg-4 control-label"><b>Name</b></label>
                                                                            <div class="col col-lg-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                                                                                <?php echo @$employee[0]->emp_fname.' ';
                                                                                echo @$employee[0]->emp_mi!='' ? @$employee[0]->emp_mi.'. ' : '';
                                                                                echo @$employee[0]->emp_lname.' '.@$employee[0]->emp_extname; ?>
                                                                                <input type="hidden" name="employee_name" value="<?php echo @$employee[0]->emp_fname.' '.@$employee[0]->emp_mname[0].'. '.@$employee[0]->emp_lname.' '.@$employee[0]->emp_extname; ?>" >
                                                                            </div>
                                                                        </div>

                                                                        <div class="row" style="clear:both;">
                                                                            <label class="col col-lg-4 control-label"><b>Date of appointment</b></label>
                                                                            <div class="col col-lg-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                                                                                <?php echo @$employee[0]->emp_date_hired ? date('F j, Y',strtotime(@$employee[0]->emp_date_hired)) : '---'; ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row" style="clear:both;">
                                                                            <label class="col col-lg-4 control-label"><b>Position</b></label>
                                                                            <div class="col col-lg-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                                                                                <?php echo @$employee[0]->position_name; ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row" style="clear:both;">
                                                                            <label class="col col-lg-4 control-label"><b>Status of Appointment</b></label>
                                                                            <div class="col col-lg-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                                                                                <?php echo @$employee[0]->status_name; ?>
                                                                            </div>
                                                                        </div>

                                                                        <?php /*<div class="col col-lg-12" style="clear:both;">
                                                                            <label class="col col-lg-4 control-label">Remarks</label>
                                                                            <div class="col col-lg-8" id="" style="padding-bottom: 10px;">
                                                                                <textarea name="remarks" id="remarks" class="form-control" maxlength="150"><?php echo set_value('remarks') ? set_value('remarks') : @$details[0]->remarks; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                         */ ?>
                                                                </div>
                                                                <div class="form-group"  id="credits_details_div" style="min-height: 300px;">
                                                                </div>
                                                        </div>

                                                            <a href="<?php echo site_url('credits/add_log/'.@$employee[0]->id_employee); ?>" style="float: right; margin-left:10px;">
                                                                    <button class="btn btn-success " ><i class="icon-plus-sign"></i>  Add Log</button>
                                                            </a>
                                                    
                                                        <a href="<?php echo site_url('credits/recompute/'.@$employee[0]->id_employee); ?>" style="float: right;">
                                                                <button class="btn btn-primary btn-xs " ><i class="icon-refresh"></i>  Recompute</button>
                                                        </a>
                                                </div>
                                            
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
                                                        <input type="hidden" id="employee_id" value="<?php echo $employee_id; ?>">
                                                        <input type="hidden" id="thisyear" value="<?php echo $year; ?>">

                                                        <div class="panel-body" id="credit_logs_div" style="">

                                                        </div>
                                                </section>
                                        </div>
                                        
                                </div>

                        </div>
                                            
                </div>
                                        
        </div>




<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>


<script type="text/javascript">
        $(document).ready(function(){
            
            load_logs();
            load_details();
            
            function load_logs(){
                    $('#credit_logs_div').html('Loading logs...');
                    
                    var post_data={};
                    post_data['thisyear'] = $('#credit_year').val();
                    post_data['employee_id'] = $('#employee_id').val();
                    $.ajax({
                            url: '<?php echo site_url('credits/load_logs'); ?>',
                            type: 'POST',
                            data: post_data,
                            success:function(result){
                                $('#credit_logs_div').html(result);
                            }
                    });
            }
            
            function load_details(){
                    
                    $.ajax({
                            url: '<?php echo site_url('credits/load_details'); ?>/'+$('#employee_id').val()+'/'+$('#credit_year').val(),
                            success:function(result){
                                $('#credits_details_div').html(result);
                            }
                    });
            }
            
            $('#credit_year').on('change',function(){
                    load_logs();
                    load_details();
            });
            
            $('.print_btn').on('click',function(){
                    //alert($('#credit_year').val())
                    window.open("<?php echo site_url('attendance/credits/credits_download'); ?>/" + $('#employee_id').val() + "/" + $('#credit_year').val());
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