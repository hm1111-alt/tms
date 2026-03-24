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

        <!-- Bootstrap Datepicker CSS -->
        <link rel="stylesheet" href="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.css'); ?>">


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
            .form-control[readonly]{
                cursor: default !important;
                background-color: white !important;
            }
            .form-control[readonly][disabled]{
                cursor: not-allowed !important;
                background-color: #eee !important;
            }

        </style>

        <script type="text/javascript">
                $(function() {

                        $('.separation_date').datepicker({
                            format: 'mm/dd/yyyy',
                            autoclose: true,
                            todayHighlight: true,
                            
                        });
                        $('.datepicker').datepicker({
                            format: 'mm/dd/yyyy',
                            autoclose: true,
                            todayHighlight: true,
                            
                        });
                        

//                        $( ".separation_date" ).datepicker({
//                            dateFormat: 'yy-mm-dd'
//                        });
//                        $( ".datepicker" ).datepicker({
//                            dateFormat: 'yy-mm-dd',
//                        });

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

                                                <input type="hidden" id="access_level" value="<?php echo session()->get('access_level'); ?>">
                                                <input type="hidden" id="user_type" value="<?php echo session()->get('user_type_id'); ?>">


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
                                                                    <a href="<?php echo site_url('credits'); ?>" title="Click here to view Leave Card" target="_blank" class="text-danger">
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
                                        </div>

                                </div>
                        </div>


                        <div class="col mb-4 col-lg-7">
                                <!--<div class="card-header">
                                    <i class="fas fa-table me-1"></i>
                                    DataTable Example
                                </div>-->

                                <div class="card mb-4">
                                        <div class="card-body">

                                                <div class="container mb-2" id="" style="">

                                                        <h4 class="boldtext" style="padding-left:20px;">Details of Application</h4>


                                                        <?php if(@$details){ ?>

                                                                <div class="col col-sm-12" style="margin-left:30px;">
                                                                        <?= $this->include('attendance/leaves/form_details_table') ?>
                                                                </div>

                                                                <div class="col col-sm-12" id="submit_btns_div" style="<?php echo  @$validation && $validation->listErrors() ? 'display:none;' : 'display: inline-block;'; ?> text-align: right; padding-top:10px;">

                                                                        <?php //if(@$details[0]->leave_type_isbasic==1){ ?>
                                                                        <?php if(!@$details){ ?>
                                                                            <button class="btn btn-primary add_leave_btn" id="<?php echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="background-color: #428bca; border-color: #428bca; ">
                                                                                <i class="icon-plus-sign"></i> Add Type of Leave
                                                                            </button>
                                                                        <?php } ?>


                                                                                <!--<a href="<?php echo site_url('leaves/add_signatories'); ?>" onclick="return confirm('Are you sure you want to proceed?')" title="Go to next step">-->
                                                                                <a href="<?php echo site_url('leaves/add_files'); ?>" onclick="return confirm('Are you sure you want to proceed?')" title="Go to next step">
                                                                                    <button class="btn btn-primary btn-lg " id="<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="background-color: #3ACF93; border-color: #3ACF93; margin-right:10px;">
                                                                                        <i class="glyphicon glyphicon-arrow-right" style="padding-right:7px;"></i> Next
                                                                                    </button>
                                                                                </a>

                                                                </div>

                                                        <?php } ?>

                                                        <?php if(!@$details || @$leave[0]->is_monetization==0){ ?>
                                                            <div class="col col-sm-12" style="display: inline-block; padding-left: 50px; padding-top: 10px;">
                                                                    <div class="col col-sm-9">
                                                                        <!--<em style="cursor: pointer; color: #428bca;" id="view_holiday">View Holidays</em>-->
                                                                    </div>
                                                            </div>
                                                        <?php } ?>
                                                </div>
                                        </div>
                                </div>
                                
                                <div class="card mb-4"  id="details_form_div" style="<?php echo (@$details) ? 'display:none;' : ''; ?>">
                                    
                                        <div class="card-body">

                                                <div class="container mb-2" id="" style="">

                                                        <form method="post" name="leave_form" id="leave_form" action="<?php echo $request->getUri()->getPath(); ?>">

                                                                <input type="hidden" name="leave_id" class="leave_id" value="<?php echo @$leave[0]->id_leave; ?>">
                                                                <input type="hidden" name="employee_id" class="employee_id" value="<?php echo @$leave[0]->employee_id; ?>">
                                                                
                                                                <input type="hidden" name="vl_balance" class="vl_balance" value="<?php echo @$vl_balance; ?>">
                                                                <input type="hidden" name="sl_balance" class="sl_balance" value="<?php echo @$sl_balance; ?>">
                                                                
                                                                <!--<input type="hidden" name="leave_detail_id" value="<?php // echo @$leave[0]->id_leave; ?>">-->
                                                                <?php /*if(session()->get('access_level')==1){ ?>
                                                                    <input type="text" name="date_filing" style="width: 90%;" class="form-control m-bot15 datepicker" value="<?php echo set_value('date_filing') ? set_value('date_filing') : date('Y-m-d', strtotime(@$leave[0]->filing_date)); ?>" />
                                                                <?php }*/ ?>
                                                                
                                                                <h4 class="boldtext" style="">Leave form</h4>
                                                                
                                                                <?php $manda_balance = $manda_balance<=0 ? 0 : $manda_balance;
                                                                echo "Available Mandatory Leave = ".$manda_balance." day(s)";
                                                                echo '<br>';
                                                                echo 'Available SPL = <span id="slp_balance">'.$slp_balance.'</span> day(s)';
                                                                echo '<br>';
                                                                echo @$leave[0]->emp_solo==1 ? "Available Solo Parent Leave = ".$spl_balance." day(s)" : ""; ?>

                                                                <?= $this->include('attendance/leaves/form_details') ?>
                                                        </form>
                                

                                                </div>
                                        </div>
                                </div>
                                
                                <div class="card mb-4"  id="inclusive_dates_div" style="display:none">
                                    
                                        <div class="card-body">

                                                <div class="container mb-2" id="" style="">

                                                        <form method="post" name="leave_date_form" id="leave_date_form" action="<?php echo site_url('leaves/add_date'); ?>">        

                                                                <input type="hidden" name="employee_id" class="employee_id" value="<?php echo @$leave[0]->employee_id; ?>">
                                                                <?= $this->include('attendance/leaves/form_details_dates') ?>
                           
                                                        </form>
                                
                                                </div>
                                        </div>
                                </div>
                                
                        </div>
                </div>
        </div>

        
        
<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>

<!-- Bootstrap Datepicker JS -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>-->
<script src="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo js('myscript/leave_form_details.js'); ?>"></script>

<script type="text/javascript">
        $(document).ready(function(){
            
                


                $('#view_holiday').on('click',function(){
                        //$('.wrapper').mask('Loading holidays... Please wait.');
                        var post_data={};
                        $.ajax({
                                url: "<?php echo site_url('attendance/leave/holidays') ?>",
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                    //$('.wrapper').unmask();
                                    $('#samplediv').html(result);
                                    $('#samplediv').lightbox_me({
                                        closeClick: true, 
                                        closeSelector: "#cancel,#print"
                                    });
                                }
                        });
                });


                $('.upload_btn').on('click',function(){
                        //$('#main-content').mask('Loading form... Please wait.');
                        var post_data={};
                        post_data['detail_id'] = $(this).attr('id');
                        post_data['leave_type'] = $(this).attr('leave_type');
                        post_data['leave_type_id'] = $(this).attr('leave_type_id');
                        post_data['filetype_id'] = $(this).attr('filetype_id');
                        post_data['refno'] = $('#leave_refno_lbl').html();
                        post_data['leave_id'] = $('.leave_id').val();
                        
                        var upload_url = "<?php echo site_url('attendance/leave/upload_form'); ?>";
                        
                        $.ajax({
                                url: upload_url,
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                    //$('#main-content').unmask();
                                    $('#samplediv').html(result);
                                    $('#samplediv').lightbox_me({
                                        closeClick: true, 
                                        closeSelector: "#cancel"
                                    });
                                }
                        });
                });
                
                var file_preview_url = "<?php echo site_url('attendance/leave/preview_file') ?>";
                
                $('.preview').on('click',function(){
                        //$('#main-content').mask('Loading... Please wait.');
                        var post_data={};
                        post_data['file_id'] = $(this).attr('id');
                        $.ajax({
                                url: file_preview_url,
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                    //$('#main-content').unmask();
                                    $('#samplediv').html(result);
                                    $('#samplediv').lightbox_me({
                                        closeClick: true, 
                                        closeSelector: "#cancel"
                                    });
                                }
                        });
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




<script type="text/javascript">
        $(document).ready(function(){
            
                
            get_mindate_url = "<?php echo site_url('leaves/get_mindate') ?>";
            edit_date_url = "<?php echo site_url('leaves/edit_date') ?>";
                 
            if($('#leave_type').val()!=''){
                
                //$('#leave_form').mask('Loading form... Please wait.');
                
                var leave_type = $('#leave_type').val();
                if(leave_type=='' || leave_type==15){ // 15 - Terminal Leave
                        //$('#leave_form').unmask();
                } else if(leave_type=='others'){
                        //$('#leave_form').unmask();
                } else {
                    
                    if(leave_type==2){ // sick leave
                            var datenow = new Date();
                            datenow.setDate(datenow.getDate() - 1);
                            if(datenow===0 || datenow===6){
                                datenow.setDate(datenow.getDate() - 1);
                            }
                            if(datenow===0 || datenow===6){
                                datenow.setDate(datenow.getDate() - 1);
                            }
                            //$(".date_from").datepicker( "option", "maxDate", datenow);
                            $('.date_from').datepicker('setEndDate', datenow);
                            //$(".date_to").datepicker( "option", "maxDate", datenow);
                            $('.date_to').datepicker('setEndDate', datenow);
                            //$('#leave_form').unmask();

                    } 
                    
                    var post_data={};
                    post_data['leave_type']=leave_type;
                    $.ajax({
                            url: get_mindate_url,
                            type: 'POST',
                            data: post_data,
                            success:function(result){
                                var now = new Date(result * 1000);
                                //$('#leave_form').unmask();

                                //$(".date_from").datepicker( "option", "minDate", now);
                                $('.date_from').datepicker('setStartDate', now);
                                if($(".date_from").val()!=''){
                                    var date2 = new Date($(".date_from").val());
                                    //$(".date_to").datepicker( "option", "minDate", date2);
                                    $('.date_to').datepicker('setStartDate', date2);
                                }
                    
                            }
                    });
                }
            }
            
        });
</script>

<?= $this->endSection('footer_jscript') ?>