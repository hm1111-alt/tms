<?php $request = \Config\Services::request(); ?>

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
    
    input.days{
        /*background-color: #f9f9f9;*/
        background-color: #FFE8A0;
        text-align: right;
        font-weight: bold;
        font-size: larger;
    }
    
    .days_error{
        /*background-color: #f9f9f9;*/
        background-color: #FFADAB;
    }
    
    .days_warning{
        /*background-color: #f9f9f9;*/
        background-color: #f0ad4e;
    }
    
    .wizard_horizontal ul.wizard_steps li a.step_no_last:before {
    background: #1ABB9C !important;
}

    .form-control[readonly]{
        cursor: default !important;
        background-color: white !important;
    }
    .form-control[readonly][disabled]{
        cursor: not-allowed !important;
        background-color: #eee !important;
    }
    
    input.approved_days{
        text-align: center;
        font-size: larger;
        font-weight: bold;
        border: none;
    }
</style>

<script type="text/javascript">
        $(function() {
                //var leave_type = $('#leave_type').val();
                var date = new Date();
                var minDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() -50); // min days allowed
  
                $( ".date_to" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
                $( ".date_from" ).datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: minDate,
                    onSelect: function(date) {
                        if($(".date_to" ).val()=='' && $('.from_hd').val()!=1){
                            //$("#date_to" ).val($("#date_from").val());
                            $('.date_to').attr('disabled',false);
                            $('.to_hd').attr('disabled',false);
                        }
                        $(".date_to").datepicker( "option", "minDate", date);
//                        var minDate = $(this).datepicker('getDate');
//                        $( "#date_to" ).datepicker({
//                            minDate: $("#date_from").val()
//                        });
                    }
                });
            
        });
</script>


<div style="min-height: 50vh">

        <div class="row">

                <div class="col mb-4 col-lg-4">

                        <div class="card mb-4">

                                <div class="card-body">
                                        <h2 class="mt-4 mb-4">Details of Application for Leave</h2>

                                        <div class="container mb-4" id="leave_credits_div" style="">

                                                <div class="form-group" id="employee_div">

                                                        <?= $this->include('attendance/leaves/form_details_employee') ?>

                                                        <div class="container mb-2">
                                                            <div class="row">
                                                                <label class="col col-lg-3" style="padding-top:0px;">Date of filing</label>
                                                                <div class="col col-lg-9">
                                                                    <?php echo @$leave[0]->filing_date ? date('F j, Y',strtotime(@$leave[0]->filing_date)) : ''; ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php  $status_color = 'black';
                                                        $status_bg = 'white';
                                                        if(@$leave[0]->is_cancelled==1){
                                                            $leave_status =  'Cancelled';
                                                            $status_color = 'white';
                                                            $status_bg = '#67492b';
                                                        } else if(@$leave[0]->is_confirmed==1){
                                                            $leave_status =  'APPROVED. Received by HR.';
                                                            $status_color = 'white';
                                                            $status_bg = '#337ab7';
                                                        } else if(@$leave[0]->leave_status==1){
                                                            $leave_status = @$leave[0]->leave_status_name;
                                                            $status_color = 'white';
                                                            $status_bg = '#f87c2f';
                                                        } else if(@$leave[0]->leave_status==2){
                                                            $leave_status = @$leave[0]->leave_status_name;
                                                            $status_color = 'white';
                                                            $status_bg = '#d9534f';
                                                        } else if(@$leave[0]->leave_status==3){
                                                            $leave_status = @$leave[0]->leave_status_name;
                                                            $status_color = 'white';
                                                            $status_bg = '#337ab7';
                                                        } else {
                                                            $leave_status = @$leave[0]->leave_status_name;
                                                            $status_color = 'black';
                                                        } ?>

                                                        <div class="container mb-2">
                                                            <div class="row">
                                                                <label class="col col-lg-3 control-label" style="padding-top:0px;"><b>Status</b></label>
                                                                <div class="col col-lg-9" >
                                                                    <b style="padding: 5px; font-size:larger;<?php echo 'background-color: '.$status_bg.';color: '.$status_color.';'; ?>">
                                                                        <?php echo $leave_status; ?>
                                                                    </b>

                                                                </div>
                                                            </div>
                                                        </div>

                                                </div>

                                        </div>

                                        <div class="container mt-2 mb-2">

                                                <section class="panel">

                                                        <div class="panel-body" >

                                                                <!------------------------------------------------------>
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:20px; padding-left:30px;">


                                                                        <h4 class="boldtext" style="font-size: 14px;">Leave Credits</h4>

                                                                        <div class="row" style="">
                                                                                <label class="col col-lg-3 control-label" style="text-align: right; padding-top:0px; font-size: 13px;">As of </label>
                                                                                <div class="col col-lg-9">
                                                                                    <span style="text-decoration: underline; font-weight: bold;" id="asofdate">
                                                                                        <?php echo @$leave[0]->leave_credit_asofdate!='' ? date('F j, Y',strtotime(@$leave[0]->leave_credit_asofdate)) : ''; ?>
                                                                                        <a href="<?php echo site_url('credits/view/'.@$leave[0]->employee_id.'/'.date('Y')); ?>" title="Click here to view Leave Card" target="_blank" class="text-danger" style="margin-left:20px;">
                                                                                            (View <?php echo (@$leave[0]->employee_id==session()->get('empid') && @$leave[0]->employee_id!='') ? 'My' : ''; ?> Leave Card)
                                                                                        </a>
                                                                                    </span>
                                                                                </div>

                                                                        </div>

                                                                        <div class="col col-lg-12" style="display: inline-block;margin-left:30px">

                                                                                <table style="width: 90%;" border="1" style="">
                                                                                    <tr>
                                                                                        <td style="width:22%;"></td>
                                                                                        <td style="width:34%; text-align: center; padding: 5px; font-weight: bold;">Vacation Leave</td>
                                                                                        <td style="width:34%; text-align: center; padding: 5px; font-weight: bold;">Sick Leave</td>
                                                                                        <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">SLP</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding: 5px;">Total Earned</td>
                                                                                        <td style="text-align: center; padding: 5px; font-weight: bold;" id="">
                                                                                            <?php echo @$leave[0]->leave_credit_asofvl;
                                                                                            $credit_asofvl = floatval(@$leave[0]->leave_credit_asofvl);
                                                                                            ?>
                                                                                        </td>
                                                                                        <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofsl">
                                                                                            <?php echo @$leave[0]->leave_credit_asofsl; 
                                                                                            $credit_asofsl = floatval(@$leave[0]->leave_credit_asofsl);
                                                                                            ?>
                                                                                        </td>
                                                                                        <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofslp">
                                                                                            <?php echo @$leave[0]->leave_credit_asofslp; 
                                                                                            $credit_asofslp = floatval(@$leave[0]->leave_credit_asofslp);
                                                                                            ?>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding: 5px;">Less this application</td>
                                                                                        <td style="text-align: center; padding: 5px;"><?php echo @$leave[0]->totalvl_days_applied ? @$leave[0]->totalvl_days_applied : ''; ?></td>
                                                                                        <td style="text-align: center; padding: 5px;"><?php echo @$leave[0]->totalsl_days_applied ? @$leave[0]->totalsl_days_applied : ''; ?></td>
                                                                                        <td style="text-align: center; padding: 5px;"><?php echo @$leave[0]->totalslp_days_applied ? @$leave[0]->totalslp_days_applied : ''; ?></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td style="padding: 5px;">Balance</td>
                                                                                        <td style="text-align: center; padding: 5px;"></td>
                                                                                        <td style="text-align: center; padding: 5px;"></td>
                                                                                        <td style="text-align: center; padding: 5px;"></td>
                                                                                    </tr>
                                                                                </table>
                                                                        </div>


                                                                </div>
                                                        </div>
                                                </section>
                                        </div>

                                </div>

                        </div>

                </div>


                <div class="col mb-4 col-lg-8">

                        <div class="card mb-4">

                                <div class="card-body">

                                        <div class="container  mb-4 mt-4 px-4">


                                                <section class="panel">

                                                        <div class="panel-body" >

                                                                <!------------------------------------------------------>
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                                        <h4 class="boldtext" style="">Details of Application</h4>

                                                                        <div class="col-sm-12" style="margin-left:30px;">

                                                                                <?php if(@$details){ ?>

                                                                                        <div class="col-sm-12" style="margin-left:30px;">
                                                                                                <?php $this->include('attendance/leaves/form_details_table'); ?>
                                                                                        </div>

                                                                                <?php } ?>

                                                                        </div>


                                                                </div>
                                                        </div>

                                                </section>
                                            
                                            
                                                <?php /*
                                                <section class="panel" id="inclusive_dates_div" style="display: none; margin-top:30px;">

                                                        <div class="panel-body" >

                                                                <!------------------------------------------------------>
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                                        <form method="post" name="leave_date_form" id="leave_date_form" action="<?php echo site_url('attendance/leave/add_date/2'); ?>">        

                                                                                <input type="hidden" name="employee_id" class="employee_id" value="<?php echo @$leave[0]->employee_id; ?>">

                                                                                <?php $this->include('attendance/leaves/form_details_dates'); ?>

                                                                        </form>
                                                                </div>
                                                        </div>

                                                </section>    
                                                 * 
                                                 */ ?>

                                                <section class="panel">

                                                        <div class="panel-body" >


                                                                <!------------------------------------------------------>
                                                                <form method="post" name="receive_form" id="receive_form" action="<?php echo $request->getUri()->getPath(); ?>">        

                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >


                                                                                <h3 class="boldtext">Approve and Receive</h3>


                                                                                <div class="col-xs-12" style="display: inline-block;margin-left:30px;margin-right:30px;">

                                                                                        <table style="width: 100%;" border="1" style="">
                                                                                            <tr>
                                                                                                <td style="padding: 5px; font-weight: bold;" colspan="2">Type of Leave</td>
                                                                                            </tr>

                                                                                            <?php if(@$details){

                                                                                                    $i = 0;
                                                                                                    $j = 0;

                                                                                                    $total_vl_days = 0;
                                                                                                    $total_vl_nopay = 0;
                                                                                                    $total_sl_days = 0;
                                                                                                    $total_sl_nopay = 0;
                                                                                                    $total_slp_days = 0;

                                                                                                    foreach($details as $d){ ?>

                                                                                                        <tr>
                                                                                                                <?php /*
                                                                                                                <td style="padding: 5px;"><?php 
                                                                                                                        if(@$d->for_monetization==1){
                                                                                                                            echo 'Monetization';
                                                                                                                        } else {
                                                                                                                            echo @$d->leave_type_id!=0 ? @$d->leave_type_name : 'Others';
                                                                                                                        } ?>
                                                                                                                </td>
                                                                                                                 */ ?>

                                                                                                                <td style=""  colspan="2">

                                                                                                                        <table border="1" cellpadding="5" style="width:100%;">

                                                                                                                                <?php 

                                                                                                                                // modify code here----------------
                                                                                                                                // modify code here----------------
                                                                                                                                // modify code here----------------

                                                                                                                                if(@$d->for_monetization==1){
                                                                                                                                    echo  '<b>'.@$d->subtotal_days_applied.' days</b> - (Php '.number_format(@$d->monetize_amount, 2, '.', ',').')';
                                                                                                                                } else if(@$d->for_terminal==1){
                                                                                                                                    echo  '<b>'.date('F j, Y',strtotime($d->separation_date)).'</b>';
                                                                                                                                } else if(@$d->dates){

                                                                                                                                        $aa = 0;

                                                                                                                                        $date_count = count(@$d->dates);

                                                                                                                                        foreach(@$d->dates as $dat){
                                                                                                                                            $aa++;

                                                                                                                                            if($aa==1){ ?>

                                                                                                                                                    <tr>
                                                                                                                                                        <td style="width:25%; "><?php 
                                                                                                                                                            if(@$d->for_monetization==1){
                                                                                                                                                                echo 'Monetization';
                                                                                                                                                            } else {
                                                                                                                                                                echo @$d->leave_type_id!=0 ? @$d->leave_type_name : 'Others';
                                                                                                                                                            } ?>
                                                                                                                                                        </td>

                                                                                                                                                        <td style="width:12%; text-align: center; padding: 5px; font-weight: bold;">VL w/ pay</td>
                                                                                                                                                        <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">VL w/o pay</td>
                                                                                                                                                        <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">SL w/ pay</td>
                                                                                                                                                        <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">SL w/o pay</td>
                                                                                                                                                        <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">SLP</td>
                                                                                                                                                        <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">Service</td>
                                                                                                                                                        <td style="width:13%; text-align: center; padding: 5px; font-weight: bold;">Total Days</td>
                                                                                                                                                    </tr>

                                                                                                                                            <?php } ?>

                                                                                                                                            <tr>

                                                                                                                                                    <td>
                                                                                                                                                            <?php $from_year = date('Y', strtotime($dat->date_from));
                                                                                                                                                            $to_year = @$dat->date_to!='' ? date('Y', strtotime($dat->date_to)) : '';
                                                                                                                                                            echo date('M. j', strtotime($dat->date_from));
                                                                                                                                                            echo ($from_year!=$to_year || $from_year!=date('Y')) ? ', '.$from_year : '';

                                                                                                                                                            if($dat->date_from_ishalf==1){
                                                                                                                                                                echo ' (AM)';
                                                                                                                                                            } else if($dat->date_from_ishalf==2){
                                                                                                                                                                echo ' (PM)';
                                                                                                                                                            } else {
                                                                                                                                                                echo ' (WD)';
                                                                                                                                                            }

                                                                                                                                                            //echo $dat->nodays>1 ? ' - '.date('M. j', strtotime($dat->date_to)) : date(', Y', strtotime($dat->date_from));
                                                                                                                                                            echo $dat->nodays>1 ? ' - '.date('M. j', strtotime($dat->date_to)) : '';
                                                                                                                                                            //echo $dat->date_to_ishalf==1 ? ' (AM)'.date(', Y', strtotime($dat->date_to)) : '';


                                                                                                                                                            if(@$dat->date_to!='' && $dat->nodays>1){
                                                                                                                                                                echo ($to_year!=date('Y')) ? ', '.$to_year : '';
                                                                                                                                                                if($dat->date_to_ishalf==1){
                                                                                                                                                                    echo ' (AM)';
                                                                                                                                                                } else if($dat->date_to_ishalf==2){
                                                                                                                                                                    echo ' (PM)';
                                                                                                                                                                } else if(@$dat->date_to!=''){
                                                                                                                                                                    echo ' (WD)';
                                                                                                                                                                }
                                                                                                                                                            }
                                                                                                                                                            echo '<br>&emsp;(<b>'.$dat->nodays.' days</b>)'; ?>

                                                                                                                                                    </td>

                                                                                                                                                    <?php $set_sl_day='';
                                                                                                                                                    $sl_to_vl = 0;


                                                                                                                                                    if(@$d->leave_type_id==1 || @$d->leave_type_id==9){
                                                                                                                                                            $init_vl_days[$i] = number_format($dat->nodays,3,'.',''); 


                                                                                                                                                            $set_vl_days[$i] = set_value('vl_withpay_'.@$dat->id_leave_date.'') ? set_value('vl_withpay_'.@$dat->id_leave_date.'') : @$init_vl_days[$i];
                                                                                                                                                            $set_vl_no[$i] = set_value('vl_wopay_'.@$dat->id_leave_date.'') ? set_value('vl_wopay_'.@$dat->id_leave_date.'') : 0;

                                                                                                                                                            if(set_value('vl_withpay_'.@$dat->id_leave_date.'')){
                                                                                                                                                                $set_vl_days[$i] = set_value('vl_withpay_'.@$dat->id_leave_date.'');
                                                                                                                                                                $total_vl_days += $set_vl_days[$i];
                                                                                                                                                            } else {



                                                                                                                                                                $set_vl_day = $init_vl_days[$i];
                                                                                                                                                                $total_vl_days0 = $total_vl_days + $set_vl_day; 

                                                                                    //                                                                            echo '---------<br>';
                                                                                    //                                                                            echo '$set_vl_day = '.$init_vl_days[$i].'<br>';
                                                                                    //                                                                            echo '$total_vl_days0 = '.$total_vl_days .' + '. $set_vl_day.'<br>';

                                                                                    //                                                                            echo 'if ('.$total_vl_days0.' <= '.$credit_asofvl.')<br>';

                                                                                                                                                                if($total_vl_days0<=($credit_asofvl)){ 
                                                                                    //                                                                                echo 'yes<br>';
                                                                                    //                                                                                echo $total_vl_days0. ' <= '.$credit_asofvl; 
                                                                                                                                                                    $set_vl_days[$i] = $set_vl_day;
                                                                                                                                                                    $total_vl_days += $set_vl_days[$i];
                                                                                                                                                                } else { 
                                                                                    //                                                                                echo 'no<br>';

                                                                                                                                                                    //$vl_over = $total_vl_days0 - $credit_asofvl;

                                                                                                                                                                    $set_vl_days[$i] = $credit_asofvl - $total_vl_days; 
                                                                                    //                                                                                echo '$set_vl_days[$i] = $credit_asofvl - $total_vl_days<br>';
                                                                                    //                                                                                echo '$set_vl_days[$i] = '.$credit_asofvl.' - '.$total_vl_days.'<br>';
                                                                                    //                                                                                echo '$total_vl_days += '.$set_vl_days[$i].'<br>';
                                                                                                                                                                    $total_vl_days += $set_vl_days[$i]; 
                                                                                                                                                                    $vl_nopay = $set_vl_day-$set_vl_days[$i];
                                                                                    //                                                                                echo '$vl_nopay = $set_vl_day - $set_vl_days[$i] <br>';
                                                                                    //                                                                                echo '$vl_nopay = '.$set_vl_day.' - '.$set_vl_days[$i].'<br><br>';
                                                                                    //
                                                                                    //                                                                                echo 'if(($total_vl_days + $vl_nopay)<=($credit_asofvl))<br>';
                                                                                    //                                                                                echo 'if('.($total_vl_days + $vl_nopay).' <= '.($credit_asofvl).' )<br>';

                                                                                                                                                                    $set_vl_no[$i] = $vl_nopay; // chahrge to VL nopay
                                                                                                                                                                    $total_vl_nopay += $vl_nopay;

                                                                                                                                                                }





                                                                                                                                                            }



                                                                                                                                                    } else if(@$d->leave_type_id==2){

                                                                                                                                                            $init_sl_days[$i] = number_format($dat->nodays,3,'.',''); 

                                                                                    //                                                                        $set_sl_days[$i] = set_value('sl_withpay_'.@$dat->id_leave_date.'') ? set_value('sl_withpay_'.@$dat->id_leave_date.'') : @$init_sl_days[$i];
                                                                                    //                                                                        $set_sl_no[$i] = set_value('sl_wopay_'.@$dat->id_leave_date.'') ? set_value('sl_wopay_'.@$dat->id_leave_date.'') : 0;


                                                                                                                                                            if(set_value('sl_withpay_'.@$dat->id_leave_date.'')){
                                                                                                                                                                $set_sl_day = set_value('sl_withpay_'.@$dat->id_leave_date.'');
                                                                                                                                                                $total_sl_days0 = $total_sl_days + $set_sl_day; // 0 + 3 = 0

                                                                                                                                                                if($total_sl_days0<=$credit_asofsl){ // 2 <= 2.083
                                                                                    //                                                                                echo $total_sl_days0. ' <= '.$credit_asofsl; 
                                                                                                                                                                    $set_sl_days[$i] = $set_sl_day;
                                                                                                                                                                    $total_sl_days += $set_sl_days[$i];
                                                                                                                                                                } else { // 3<= 2.083

                                                                                                                                                                    //$sl_over = $total_sl_days0 - $credit_asofvl;

                                                                                                                                                                    $set_sl_days[$i] = $credit_asofsl - $total_sl_days; 
                                                                                                                                                                    $total_sl_days += $set_sl_days[$i]; 
                                                                                                                                                                    $sl_to_vl = $set_sl_days[$i] - $set_sl_day;
                                                                                                                                                                }


                                                                                                                                                            } else {


                                                                                                                                                                $set_sl_day = $init_sl_days[$i];
                                                                                                                                                                $total_sl_days0 = $total_sl_days + $set_sl_day; 

                                                                                    //                                                                            echo '---------<br>';
                                                                                    //                                                                            echo '$set_sl_day = '.$init_sl_days[$i].'<br>';
                                                                                    //                                                                            echo '$total_sl_days0 = '.$total_sl_days .' + '. $set_sl_day.'<br>';
                                                                                    //                                                                            
                                                                                    //                                                                            echo 'if ('.$total_sl_days0.' <= '.$credit_asofsl.')<br>';

                                                                                                                                                                if($total_sl_days0<=($credit_asofsl)){ 
                                                                                    //                                                                                echo 'yes<br>';
                                                                                    //                                                                                echo $total_sl_days0. ' <= '.$credit_asofsl; 
                                                                                                                                                                    $set_sl_days[$i] = $set_sl_day;
                                                                                                                                                                    $total_sl_days += $set_sl_days[$i];
                                                                                                                                                                } else { 
                                                                                    //                                                                                echo 'no<br>';

                                                                                                                                                                    //$sl_over = $total_sl_days0 - $credit_asofvl;

                                                                                                                                                                    $set_sl_days[$i] = $credit_asofsl - $total_sl_days; 
                                                                                    //                                                                                echo '$set_sl_days[$i] = $credit_asofsl - $total_sl_days<br>';
                                                                                    //                                                                                echo '$set_sl_days[$i] = '.$credit_asofsl.' - '.$total_sl_days.'<br>';
                                                                                    //                                                                                echo '$total_sl_days += '.$set_sl_days[$i].'<br>';
                                                                                                                                                                    $total_sl_days += $set_sl_days[$i]; 
                                                                                                                                                                    $sl_nopay = $set_sl_day-$set_sl_days[$i];
                                                                                    //                                                                                echo '$sl_nopay = $set_sl_day - $set_sl_days[$i] <br>';
                                                                                    //                                                                                echo '$sl_nopay = '.$set_sl_day.' - '.$set_sl_days[$i].'<br><br>';
                                                                                    //
                                                                                    //                                                                                echo 'if(($total_vl_days + $sl_nopay)<=($credit_asofvl))<br>';
                                                                                    //                                                                                echo 'if('.($total_vl_days + $sl_nopay).' <= '.($credit_asofvl).' )<br>';

                                                                                                                                                                    if(($total_vl_days + $sl_nopay)<=($credit_asofvl)){  //charge to VL
                                                                                    //                                                                                    echo 'yes---- charge to VL<br>';
                                                                                                                                                                        $sl_to_vl = $sl_nopay;
                                                                                    //                                                                                    echo '$sl_to_vl = $sl_nopay<br>';
                                                                                    //                                                                                    echo '$sl_to_vl = '.$sl_nopay.'<br>';
                                                                                                                                                                        $total_vl_days += $sl_nopay;
                                                                                                                                                                        $set_vl_days[$i] = $sl_nopay;
                                                                                    //                                                                                    echo '$total_vl_days += $sl_nopay<br>';
                                                                                    //                                                                                    echo '$total_vl_days = '.$sl_nopay.'<br>';
                                                                                                                                                                    } else {

                                                                                    //                                                                                    echo 'no<br>';
                                                                                                                                                                        $sl_to_vl = $credit_asofvl-$total_vl_days;
                                                                                                                                                                        $sl_nopay = $sl_nopay - $sl_to_vl;
                                                                                                                                                                        $set_sl_no[$i] = $sl_nopay; // chahrge to SL nopay
                                                                                                                                                                        $total_sl_nopay += $sl_nopay;
                                                                                    //                                                                                    echo '$sl_to_vl = $credit_asofvl-$total_vl_days<br>';
                                                                                    //                                                                                    echo '$sl_to_vl = '.$credit_asofvl.'<br>';
                                                                                    //                                                                                    echo '$sl_nopay = $sl_nopay - $sl_to_vl<br>';
                                                                                    //                                                                                    echo '$sl_nopay = '.(floatval($sl_nopay) - floatval($sl_to_vl)).'<br>';
                                                                                    //                                                                                    echo '$set_sl_no[$i] = $sl_nopay<br>';
                                                                                    //                                                                                    echo '$set_sl_no[$i] = '.($sl_nopay).'<br>';
                                                                                                                                                                        $total_vl_days += $sl_to_vl;
                                                                                                                                                                        //$set_vl_no[$i] = $sl_to_vl;

                                                                                    //                                                                                    echo '$total_vl_days += '.$sl_nopay.'<br>';

                                                                                                                                                                    }

                                                                                                                                                                }

                                                                                                                                                            }

                                                                                    //                                                                        echo '<br><br><br>';



                                                                                                                                                    } else if(@$d->leave_type_id==6){

                                                                                                                                                            $init_slp_days[$i] = number_format($dat->nodays,3,'.',''); 


                                                                                                                                                            if(set_value('slp_withpay_'.@$dat->id_leave_date.'')){
                                                                                                                                                                $set_slp_day = set_value('slp_withpay_'.@$dat->id_leave_date.'');
                                                                                                                                                                $total_slp_days0 = $total_slp_days + $set_slp_day; // 0 + 3 = 0

                                                                                                                                                                if($total_slp_days0<=$credit_asofslp){ // 2 <= 2.083
                                                                                    //                                                                                echo $total_slp_days0. ' <= '.$credit_asofslp; 
                                                                                                                                                                    $set_slp_days[$i] = $set_slp_day;
                                                                                                                                                                    $total_slp_days += $set_slp_days[$i];
                                                                                                                                                                } else { // 3<= 2.083

                                                                                                                                                                    //$slp_over = $total_slp_days0 - $credit_asofvl;

                                                                                                                                                                    $set_slp_days[$i] = $credit_asofslp - $total_slp_days; 
                                                                                                                                                                    $total_slp_days += $set_slp_days[$i]; 
                                                                                                                                                                    $slp_to_vl = $set_slp_days[$i] - $set_slp_day;
                                                                                                                                                                }


                                                                                                                                                            } else {


                                                                                                                                                                $set_slp_day = $init_slp_days[$i];
                                                                                                                                                                $total_slp_days0 = $total_slp_days + $set_slp_day; 

                                                                                    //                                                                            echo '---------<br>';
                                                                                    //                                                                            echo '$set_slp_day = '.$init_slp_days[$i].'<br>';
                                                                                    //                                                                            echo '$total_slp_days0 = '.$total_slp_days .' + '. $set_slp_day.'<br>';
                                                                                    //                                                                            
                                                                                    //                                                                            echo 'if ('.$total_slp_days0.' <= '.$credit_asofslp.')<br>';

                                                                                                                                                                if($total_slp_days0<=($credit_asofslp)){ 
                                                                                    //                                                                                echo 'yes<br>';
                                                                                    //                                                                                echo $total_slp_days0. ' <= '.$credit_asofslp; 
                                                                                                                                                                    $set_slp_days[$i] = $set_slp_day;
                                                                                                                                                                    $total_slp_days += $set_slp_days[$i];
                                                                                                                                                                } else { 
                                                                                    //                                                                                echo 'no<br>';

                                                                                                                                                                    //$slp_over = $total_slp_days0 - $credit_asofvl;

                                                                                                                                                                    $set_slp_days[$i] = $credit_asofslp - $total_slp_days; 
                                                                                    //                                                                                echo '$set_slp_days[$i] = $credit_asofslp - $total_slp_days<br>';
                                                                                    //                                                                                echo '$set_slp_days[$i] = '.$credit_asofslp.' - '.$total_slp_days.'<br>';
                                                                                    //                                                                                echo '$total_slp_days += '.$set_slp_days[$i].'<br>';
                                                                                                                                                                    $total_slp_days += $set_slp_days[$i]; 

                                                                                                                                                                    $slp_nopay = $set_slp_day-$set_slp_days[$i];
                                                                                    //                                                                                echo '$slp_nopay = $set_slp_day - $set_slp_days[$i] <br>';
                                                                                    //                                                                                echo '$slp_nopay = '.$set_slp_day.' - '.$set_slp_days[$i].'<br><br>';
                                                                                    //
                                                                                    //                                                                                echo 'if(($total_vl_days + $slp_nopay)<=($credit_asofvl))<br>';
                                                                                    //                                                                                echo 'if('.($total_vl_days + $slp_nopay).' <= '.($credit_asofvl).' )<br>';

                                                                                                                                                                    if(($total_vl_days + $slp_nopay)<=($credit_asofvl)){  //charge to VL
                                                                                    //                                                                                    echo 'yes---- charge to VL<br>';
                                                                                                                                                                        $slp_to_vl = $slp_nopay;
                                                                                    //                                                                                    echo '$slp_to_vl = $slp_nopay<br>';
                                                                                    //                                                                                    echo '$slp_to_vl = '.$slp_nopay.'<br>';
                                                                                                                                                                        $total_vl_days += $slp_nopay;
                                                                                                                                                                        $set_vl_days[$i] = $slp_nopay;
                                                                                    //                                                                                    echo '$total_vl_days += $slp_nopay<br>';
                                                                                    //                                                                                    echo '$total_vl_days = '.$slp_nopay.'<br>';
                                                                                                                                                                    } else {

                                                                                    //                                                                                    echo 'no<br>';
                                                                                                                                                                        $slp_to_vl = $credit_asofvl-$total_vl_days;
                                                                                                                                                                        $slp_nopay = $slp_nopay - $slp_to_vl;
                                                                                                                                                                        $set_vl_no[$i] = $slp_nopay; // chahrge to SL nopay
                                                                                                                                                                        $total_vl_nopay += $slp_nopay;
                                                                                    //                                                                                    echo '$slp_to_vl = $credit_asofvl-$total_vl_days<br>';
                                                                                    //                                                                                    echo '$slp_to_vl = '.$credit_asofvl.'<br>';
                                                                                    //                                                                                    echo '$slp_nopay = $slp_nopay - $slp_to_vl<br>';
                                                                                    //                                                                                    echo '$slp_nopay = '.(floatval($slp_nopay) - floatval($slp_to_vl)).'<br>';
                                                                                    //                                                                                    echo '$set_slp_no[$i] = $slp_nopay<br>';
                                                                                    //                                                                                    echo '$set_slp_no[$i] = '.($slp_nopay).'<br>';
                                                                                                                                                                        $total_vl_days += $slp_to_vl;
                                                                                                                                                                        $set_vl_days[$i] = $slp_to_vl;
                                                                                    //                                                                                    echo '$total_vl_days += '.$slp_nopay.'<br>';

                                                                                                                                                                    }

                                                                                                                                                                }

                                                                                                                                                            }

                                                                                    //                                                                        echo '<br><br><br>';

                                                                                                                                                    }



                                                                                                                                                    ?>


                                                                                                                                                    <td style="text-align: center; padding: 5px 1px 5px 1px;" id="">

                                                                                                                                                        <input type="text" name="vl_withpay_<?php echo @$dat->id_leave_date; ?>" id="<?php echo @$dat->leave_detail_id; ?>" day_type="vl_withpay" class="form-control days vl_withpay days_<?php echo @$dat->leave_detail_id; ?>" onkeypress="validateamount(event)" value="<?php echo @$set_vl_days[$i]; ?>" maxlength="8" style="padding: 5px 3px 5px 1px;" >
                                                                                                                                                    </td>

                                                                                                                                                    <td style="text-align: center; padding: 5px 1px 5px 1px;" id="">
                                                                                                                                                        <input type="text" name="vl_wopay_<?php echo @$dat->id_leave_date; ?>" id="<?php echo @$dat->leave_detail_id; ?>" day_type="vl_wopay" class="form-control days vl_wopay  days_<?php echo @$dat->leave_detail_id; ?>" onkeypress="validateamount(event)" value="<?php echo @$set_vl_no[$i]>0 ? @$set_vl_no[$i] : ''; ?>" maxlength="8" style="padding: 5px 3px 5px 1px;" >
                                                                                                                                                    </td>

                                                                                                                                                    <td style="text-align: center; padding: 5px 1px 5px 1px;" id="">
                                                                                                                                                        <input type="text" name="sl_withpay_<?php echo @$dat->id_leave_date; ?>" id="<?php echo @$dat->leave_detail_id; ?>" day_type="sl_withpay" class="form-control days sl_withpay days_<?php echo @$dat->leave_detail_id; ?>" onkeypress="validateamount(event)" value="<?php echo @$set_sl_days[$i]; ?>" maxlength="8" style="padding: 5px 3px 5px 1px;" >
                                                                                                                                                    </td>

                                                                                                                                                    <td style="text-align: center; padding: 5px 1px 5px 1px;" id="">
                                                                                                                                                        <input type="text" name="sl_wopay_<?php echo @$dat->id_leave_date; ?>" id="<?php echo @$dat->leave_detail_id; ?>" day_type="sl_wopay" class="form-control days sl_wopay days_<?php echo @$dat->leave_detail_id; ?>" onkeypress="validateamount(event)" value="<?php echo @$set_sl_no[$i]>0 ? @$set_sl_no[$i] : ''; ?>" maxlength="8" style="padding: 5px 3px 5px 1px;" >
                                                                                                                                                    </td>

                                                                                                                                                    <td style="text-align: center; padding: 5px 1px 5px 1px;" id="">
                                                                                                                                                        <input type="text" name="slp_withpay_<?php echo @$dat->id_leave_date; ?>" id="<?php echo @$dat->leave_detail_id; ?>" day_type="slp_withpay" class="form-control days slp_withpay days_<?php echo @$dat->leave_detail_id; ?>" onkeypress="validateamount(event)" value="<?php echo @$set_slp_days[$i]>0 ? @$set_slp_days[$i] : ''; ?>" maxlength="8" style="padding: 5px 3px 5px 1px;" >
                                                                                                                                                    </td>

                                                                                                                                                    <td style="text-align: center; padding: 5px 1px 5px 1px;" id="">
                                                                                                                                                        <input type="text" name="service_withpay_<?php echo @$dat->id_leave_date; ?>" id="<?php echo @$dat->leave_detail_id; ?>" day_type="service_withpay" class="form-control days service_withpay days_<?php echo @$dat->leave_detail_id; ?>" onkeypress="validateamount(event)" value="<?php echo @$set_service_days[$i]>0 ? @$set_service_days[$i] : ''; ?>" maxlength="8" style="padding: 5px 3px 5px 1px;" >
                                                                                                                                                    </td>

                                                                                                                                                    <?php if($aa==1){ ?>
                                                                                                                                                        <td rowspan="<?php echo $date_count; ?>" style="text-align: center; padding: 5px; font-weight: bold;">
                                                                                                                                                            <b class="detail_total_<?php echo @$d->id_leave_detail; ?>"><?php echo @$d->subtotal_days_applied; ?></b>

                                                                                                                                                            <?php $set_detail_total[@$d->id_leave_detail] = set_value('detail_total_'.@$d->id_leave_detail.'') ? set_value('detail_total_'.@$d->id_leave_detail.'') : @$d->subtotal_days_applied; ?>
                                                                                                                                                            <input type="hidden" name="detail_total_<?php echo @$d->id_leave_detail; ?>" class="days_subtotal form-control " id="detail_total_<?php echo @$d->id_leave_detail; ?>" value="<?php echo $set_detail_total[@$d->id_leave_detail]; ?>">

                                                                                                                                                            <?php /* not finished -- disregard
                                                                                                                                                            $set_date_total[@$dat->id_leave_date] = set_value('detail_total_'.@$d->id_leave_detail.'') ? set_value('detail_total_'.@$d->id_leave_detail.'') : @$d->subtotal_days_applied; ?>
                                                                                                                                                            <input type="text" name="detail_total_<?php echo @$dat->id_leave_date; ?>" class="days_subtotal form-control " id="detail_total_<?php echo @$d->id_leave_detail; ?>" value="<?php echo $set_date_total[@$dat->id_leave_date]; ?>">
                                                                                                                                                             */ ?>
                                                                                                                                                        </td>
                                                                                                                                                    <?php } ?>

                                                                                                                                            </tr>

                                                                                                                                        <?php $i++;
                                                                                                                                        } ?>
                                                                                                                                <?php } ?>
                                                                                                                        </table>


                                                                                                                </td>


                                                                                                        </tr>

                                                                                                        <?php $j++;
                                                                                                    } ?>

                                                                                                    <tr>
                                                                                                        <td colspan="2">

                                                                                                            <table border="1" cellpadding="5" style="width:100%;">
                                                                                                                <tr>
                                                                                                                        <td style="width:25%; padding: 20px 1px 5px 1px;text-align: right; font-weight: bold;" colspan="1">TOTAL days applied:</td>

                                                                                                                        <td valign="top" style="width:12%; text-align: center; padding: 5px;">
                                                                                                                            <em>(VL w/ pay)</em><br>
                                                                                                                            <b id="totalvl_days_applied"><?php echo @$leave[0]->totalvl_days_applied ? @$leave[0]->totalvl_days_applied : '0.00'; ?></b>
                                                                                                                        </td>
                                                                                                                        <td valign="top" style="width:10%; text-align: center; padding: 5px;">
                                                                                                                            <!--VL w/o pay<br>-->
                                                                                                                        </td>
                                                                                                                        <td valign="top" style="width:10%; text-align: center; padding: 5px;">
                                                                                                                            <em>(SL w/ pay)</em><br>
                                                                                                                            <b id="totalsl_days_applied"><?php echo @$leave[0]->totalsl_days_applied ? @$leave[0]->totalsl_days_applied : '0.00'; ?></b>
                                                                                                                        </td>
                                                                                                                        <td valign="top" style="width:10%; text-align: center; padding: 5px;">
                                                                                                                            <!--SL w/o pay<br>-->
                                                                                                                        </td>
                                                                                                                        <td valign="top" style="width:10%; text-align: center; padding: 5px;">
                                                                                                                            <em>(SLP)</em><br>
                                                                                                                            <b id="totalslp_days_applied"><?php echo @$leave[0]->totalslp_days_applied ? @$leave[0]->totalslp_days_applied : '0.00'; ?></b>

                                                                                                                        </td>
                                                                                                                        <td valign="top" style="width:10%; text-align: center; padding: 5px;">
                                                                                                                            <em>(Service credits)</em><br>
                                                                                                                            <b id="totalservice_days_applied"><?php echo @$leave[0]->totalservice_days_applied ? @$leave[0]->totalservice_days_applied : '0.00'; ?></b>

                                                                                                                        </td>

                                                                                                                        <td valign="top" style="width:13%; padding: 20px 1px 5px 1px; font-weight: bold;"  id="total_days_div">
                                                                                                                            <b class="total_days"><?php echo @$leave[0]->total_days_applied ? @$leave[0]->total_days_applied : 0; ?></b> <em>day(s)</em>
                                                                                                                            <input type="hidden" name="total_days" id="total_days" value="<?php echo @$leave[0]->total_days_applied; ?>">
                                                                                                                        </td>

                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>

                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td colspan="2">

                                                                                                            <table border="1" cellpadding="5" style="width:100%;">
                                                                                                                <tr>
                                                                                                                        <td style="width:25%; padding: 5px 3px 5px 1px;text-align: right;" colspan="1"><b>Leave credits</b>
                                                                                                                            <br><em><?php echo @$leave[0]->leave_credit_asofdate!='' ? '(as of '.date('F j, Y',strtotime(@$leave[0]->leave_credit_asofdate)).')' : ''; ?></em>
                                                                                                                        </td>

                                                                                                                        <td colspan="2" style="width:22%; text-align: center; padding: 5px;">
                                                                                                                            <b><?php echo @$leave[0]->leave_credit_asofvl; ?></b>
                                                                                                                        </td>
                                                                                                                        <td colspan="2" style="width:20%; text-align: center; padding: 5px;">
                                                                                                                            <b><?php echo @$leave[0]->leave_credit_asofsl; ?></b>
                                                                                                                        </td>
                                                                                                                        <td style="width:10%; text-align: center; padding: 5px;">
                                                                                                                            <b><?php echo @$leave[0]->leave_credit_asofslp; ?></b>
                                                                                                                        </td>
                                                                                                                        <td style="width:10%; text-align: center; padding: 5px;">
                                                                                                                            <b><?php echo @$leave[0]->leave_credit_asofservice; ?></b>
                                                                                                                        </td>

                                                                                                                        <td valign="top" style="width:13%; padding: 20px 1px 5px 1px; font-weight: bold;"  id="">
                                                                                                                        </td>

                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>

                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td colspan="2">

                                                                                                            <table border="1" cellpadding="5" style="width:100%;">
                                                                                                                <tr>
                                                                                                                    <td style="width:25%; padding: 5px; text-align: right; padding-top:20px; font-weight: bold; font-size: larger;" colspan="1">TOTAL days approved:</td>

                                                                                                                    <td valign="top" style="width:12%; text-align: center; padding: 5px;  font-size: larger;">
                                                                                                                            VL w/ pay<br>

                                                                                                                            <?php if(set_value('vl_withpay')){
                                                                                                                                $set_totalvl_withpay = set_value('vl_withpay');
                                                                                                                            } /*else if(@$leave[0]->totalvl_days_applied){
                                                                                                                                $set_totalvl_withpay = @$leave[0]->totalvl_days_applied;
                                                                                                                            }*/ else {
                                                                                                                                $set_totalvl_withpay = @$total_vl_days;
                                                                                                                            } ?>
                                                                                                                            <input type="text" name="vl_withpay" id="vl_withpay" class="form-control  vl_withpay_total approved_days" readonly onkeypress="validateamount(event)" value="<?php echo $set_totalvl_withpay; ?>"  >
                                                                                                                        </td>
                                                                                                                    <td valign="top" style="width:10%; text-align: center; padding: 5px;  font-size: larger;">
                                                                                                                            VL w/o pay<br>

                                                                                                                            <?php if(set_value('vl_wopay')){
                                                                                                                                $set_totalvl_nopay = set_value('vl_wopay');
                                                                                                                            } /*else if(@$leave[0]->vl_nopay){
                                                                                                                                $set_totalvl_nopay = @$leave[0]->vl_nopay;
                                                                                                                            }*/ else {
                                                                                                                                $set_totalvl_nopay = $total_vl_nopay;
                                                                                                                            } ?>
                                                                                                                            <input type="text" name="vl_wopay" id="vl_wopay" class="form-control  vl_wopay_total approved_days" readonly onkeypress="validateamount(event)" value="<?php echo @$set_totalvl_nopay; ?>"  >
                                                                                                                        </td>
                                                                                                                    <td valign="top" style="width:10%; text-align: center; padding: 5px;  font-size: larger;">
                                                                                                                            SL w/ pay<br>
                                                                                                                            <?php if(set_value('sl_withpay')){
                                                                                                                                $set_totalsl_withpay = set_value('sl_withpay');
                                                                                                                            } /*else if(@$leave[0]->totalsl_days_applied){
                                                                                                                                $set_totalsl_withpay = @$leave[0]->totalsl_days_applied;
                                                                                                                            }*/ else {
                                                                                                                                $set_totalsl_withpay = @$total_sl_days;
                                                                                                                            } ?>

                                                                                                                            <input type="text" name="sl_withpay" id="sl_withpay" class="form-control  sl_withpay_total approved_days" readonly onkeypress="validateamount(event)" value="<?php echo @$set_totalsl_withpay; ?>"  >
                                                                                                                        </td>
                                                                                                                    <td valign="top" style="width:10%; text-align: center; padding: 5px;  font-size: larger;">
                                                                                                                            SL w/o pay<br>

                                                                                                                            <?php if(set_value('sl_wopay')){
                                                                                                                                $set_totalsl_nopay = set_value('sl_wopay');
                                                                                                                            } /*else if(@$leave[0]->sl_nopay){
                                                                                                                                $set_totalsl_nopay = @$leave[0]->sl_nopay;
                                                                                                                            }*/ else {
                                                                                                                                $set_totalsl_nopay = $total_sl_nopay;
                                                                                                                            } ?>

                                                                                                                            <input type="text" name="sl_wopay" id="sl_wopay" class="form-control  sl_wopay_total approved_days" readonly onkeypress="validateamount(event)" value="<?php echo @$set_totalsl_nopay; ?>"  >
                                                                                                                        </td>
                                                                                                                    <td valign="top" style="width:10%; text-align: center; padding: 5px;  font-size: larger;">
                                                                                                                            SLP<br> 
                                                                                                                            <?php if(set_value('slp_withpay')){
                                                                                                                                $set_totalslp_withpay = set_value('slp_withpay');
                                                                                                                            } /*else if(@$leave[0]->totalslp_days_applied){
                                                                                                                                $set_totalslp_withpay = @$leave[0]->totalslp_days_applied;
                                                                                                                            }*/ else {
                                                                                                                                $set_totalslp_withpay = @$total_slp_days;
                                                                                                                            } ?>
                                                                                                                            <input type="text" name="slp_withpay" id="slp_withpay" class="form-control  slp_withpay_total approved_days" readonly onkeypress="validateamount(event)" value="<?php echo @$set_totalslp_withpay; ?>"  >
                                                                                                                        </td>

                                                                                                                    <td valign="top" style="width:10%; text-align: center; padding: 5px;  font-size: larger;">
                                                                                                                            Service credits<br> 
                                                                                                                            <?php if(set_value('service_withpay')){
                                                                                                                                $set_totalservice_withpay = set_value('service_withpay');
                                                                                                                            } /*else if(@$leave[0]->totalslp_days_applied){
                                                                                                                                $set_totalslp_withpay = @$leave[0]->totalslp_days_applied;
                                                                                                                            }*/ else {
                                                                                                                                $set_totalservice_withpay = @$total_service_days;
                                                                                                                            } ?>
                                                                                                                            <input type="text" name="service_withpay" id="service_withpay" class="form-control  service_withpay_total approved_days" readonly onkeypress="validateamount(event)" value="<?php echo @$set_totalservice_withpay; ?>"  >
                                                                                                                        </td>

                                                                                                                    <td valign="top" style="width:13%; padding: 5px; padding-top:20px; font-weight: bold; font-size: larger;"  id="total_approved_div">
                                                                                                                        <b class="total_approved"><?php echo @$leave[0]->total_days_applied ? @$leave[0]->total_days_applied : 0; ?></b> <em>day(s)</em>
                                                                                                                        <input type="hidden" name="total_approved" id="total_approved" value="<?php echo @$leave[0]->total_days_applied; ?>">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>

                                                                                                    </tr>

                                                                                            <?php } else { ?>

                                                                                                    <tr>
                                                                                                        <td style="padding: 5px; text-align:center;" colspan="2">No record.</td>
                                                                                                    </tr>

                                                                                            <?php } ?>

                                                                                        </table>
                                                                                </div>

                                                                                <input type="hidden" name="count_arr" value="<?php echo $i; ?>">



                                                                        </div>

                                                                        <div class="col-xs-12" id="submit_btns_div" style="<?php echo /*(validation_errors()) ? 'display:none;' :*/ 'display: inline-block;'; ?> text-align: right;">
                                                                                <input type="hidden" name="leave_id" value="<?php echo @$details[0]->leave_id; ?>">
                                                                                <input type="hidden" name="employee_id" class="employee_id" value="<?php echo @$leave[0]->employee_id; ?>">
                                                                                <input type="hidden" name="emp_idno" class="emp_idno" value="<?php echo @$leave[0]->emp_idno; ?>">
                                                                                <input type="hidden" name="emp_lname" class="emp_lname" value="<?php echo @$leave[0]->emp_lname; ?>">

                                                                                <br>
                                                                                <br>
                                                                                    <button class="btn btn-primary btn-lg " id="btn_receive" style="background-color: #3ACF93; border-color: #3ACF93; margin-right:10px;">
                                                                                        <i class="glyphicon glyphicon-check" style="padding-right:7px;"></i> Approve & Receive
                                                                                    </button>

                                                                                    <a href="<?php echo site_url('attendance/leave'); ?>" onclick="return confirm('Are you sure you want to go back?')">
                                                                                        <button class="btn btn-default" style="margin-right:10px;">
                                                                                            Cancel
                                                                                        </button>
                                                                                    </a>
                                                                                <br>
                                                                                <br>
                                                                        </div>

                                                                </form>
                                                                <!------------------------------------------------------>

                                                        </div>

                                                </section>

                                        </div>

                                </div>

                        </div>

                </div>
        </div>

</div>



        

<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

<script src="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo jquery('myscript/leave_receive.js'); ?>"></script>
<script type="text/javascript" src="<?php echo jquery('myscript/leave_form_details.js'); ?>"></script>

<script type="text/javascript">
        $(document).ready(function(){
                edit_date_url = "<?php echo site_url('leaves/edit_date') ?>";

            
//                $('#edit_btn').on('click',function(){
//                    
//                        $('#edit_btn').hide();
//                        $('#signatories_form').slideDown();
//                        $('#signatories_div').slideUp();
//                });
//            
//                $('#cancel_btn').on('click',function(){
//                    
//                        $('#edit_btn').slideDown();
//                        $('#signatories_form').slideUp();
//                        $('#signatories_div').slideDown();
//                });
                 
                
        });



</script>


<?= $this->endSection('footer_jscript') ?>
