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

<script type="text/javascript">
        $(function() {
            
        });
</script>


        <div style="min-height: 50vh">

                <div class="row">
                    
                        <div class="col mb-4 col-lg-6">
                            
                                <div class="card mb-4">

                                        <div class="card-body">
                                                <h2 class="mt-4 mb-4">
                                                        Application for Leave
                                                        <a href="<?php echo site_url('download_leave/'.@$leave[0]->leave_refno); ?>" target="_blank" title="Print leave">
                                                            <button class="btn btn-primary btn-lg " style="background-color: #FF7e47; border-color: #FF7e47; margin-right:10px;">
                                                                <i class="fas fa-print" style="padding-right:7px;"></i> Print
                                                            </button>
                                                        </a>
                                                </h2>
                                                
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

                                                                                    <?php if(@$leave[0]->is_confirmed==0 
                                                                                                && (session()->get('access_level')==1 || session()->get('access_level')==2)
                                                                                        ){ ?>
                                                                                            <a href="<?php echo site_url('attendance/leave/receive/'.@$leave[0]->leave_refno); ?>" style="margin-left:20px;">
                                                                                                <button class="btn btn-success btn-xs " title="Approve and Receive" >
                                                                                                    <span class="glyphicon glyphicon-download-alt leave-glph"></span> Receive
                                                                                                </button>
                                                                                            </a>
                                                                                    <?php } else if(@$leave[0]->is_confirmed==1 
                                                                                                && (session()->get('access_level')==1 || session()->get('access_level')==2)
                                                                                        ){ ?>
                                                                                            <a href="<?php echo site_url('attendance/credits/cancel_received/'.@$leave[0]->leave_refno.'/'.@$leave[0]->employee_id.'/'.date('Y',strtotime(@$leave[0]->added_date))); ?>" style="margin-left:20px;">
                                                                                                <button class="btn btn-danger btn-xs " title="Cancel Received" >
                                                                                                    <span class="glyphicon glyphicon-download-alt leave-glph"></span> Cancel Received
                                                                                                </button>
                                                                                            </a>
                                                                                    <?php } ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    
                                                                </div>

                                                </div>
                                            
                                                <div class="container mt-2 mb-2">

                                                        <section class="panel">

                                                                <div class="panel-body" >


                                                                        <!------------------------------------------------------>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                                <h4 class="boldtext" style="">DETAILS OF APPLICATION</h4>

                                                                                <div class="col-sm-12" style="margin-left:30px;">
                                                                                        <table style="width: 90%;" border="1" style="">
                                                                                            <tr>
                                                                                                <td style="width:30%;padding: 5px; font-weight: bold;">Type of Leave to be availed of</td>
                                                                                                <td style="width:40%;padding: 5px; font-weight: bold;">Details of Leave</td>
                                                                                                <td style="width:30%;padding: 5px; font-weight: bold;">Details of Leave</td>
                                                                                            </tr>

                                                                                            <?php if(@$details){
                                                                                                foreach($details as $d){ ?>

                                                                                                    <tr>
                                                                                                        <td style="padding: 5px;"><?php 
                                                                                                            if(@$d->for_monetization==1){
                                                                                                                echo 'Monetization';
                                                                                                            } else {
                                                                                                                echo @$d->leave_type_id!=0 ? @$d->leave_type_name : 'Others';
                                                                                                            } ?>
                                                                                                        </td>
                                                                                                        <td style="padding: 5px;">
                                                                                                            <?php if(@$d->for_monetization==1){
                                                                                                                echo @$d->leave_details;
                                                                                                            } else if(@$d->for_terminal==1){
                                                                                                                echo 'Php '.number_format(@$d->monetize_amount, 2, '.', ',').'';
                                                                                                            } else if(@$d->leave_type_id==0){
                                                                                                                echo $d->leave_type_others;
                                                                                                            } else { 
                                                                                                                echo @$d->leave_location!='' ? @$d->leave_location.'<br>' : '';
                                                                                                                echo @$d->leave_purpose!='' ? @$d->leave_purpose.'<br>' : '';
                                                                                                                echo @$d->leave_details!='' ? @$d->leave_details.'<br>' : '';
                                                                                                            }?>
                                                                                                        </td>

                                                                                                        <td style="padding: 5px;" >
                                                                                                            <ul>
                                                                                                                <?php if($request->uri->getSegment(3)=='edit'){
                                                                                                                    $edit = 1;
                                                                                                                } else if($request->uri->getSegment(3)=='receive'){
                                                                                                                    $edit = 2;
                                                                                                                } else $edit = 0;


                                                                                                                if(@$d->for_monetization==1){
                                                                                                                    echo  '<b>'.@$d->subtotal_days_applied.' days</b> - (Php '.number_format(@$d->monetize_amount, 2, '.', ',').')';
                                                                                                                } else if(@$d->for_terminal==1){
                                                                                                                    echo  '<b>'.date('F j, Y',strtotime($d->separation_date)).'</b>';
                                                                                                                } else if(@$d->dates){
                                                                                                                    foreach(@$d->dates as $dat){ ?>
                                                                                                                        <li>
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

                                                                                                                        </li>
                                                                                                                <?php }
                                                                                                                } ?>
                                                                                                            </ul>


                                                                                                        </td>


                                                                                                    </tr>

                                                                                            <?php } ?>
                                                                                                <tr>
                                                                                                    <td style="padding: 5px; text-align: right; padding-top:20px;" colspan="2">No. of Working Days Applied For:</td>
                                                                                                    <td style="padding: 5px; padding-top:20px; font-weight: bold;" colspan="2"><?php echo @$leave[0]->total_days_applied ? @$leave[0]->total_days_applied : 0; ?> day(s)</td>
                                                                                                </tr>
                                                                                            <?php }  ?>

                                                                                        </table>
                                                                                </div>
                                                                        </div>

                                                                        <!------------------------------------------------------>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:20px; padding-left:30px;">
                                                                                <h4 class="boldtext" style="font-size:14px;">Number of Working days applied for</h4>

                                                                                <div class="row" style="margin-left:30px;">

                                                                                        <!--<span style="padding-left: 20px;">NUMBER OF WORKING DAYS APPLIED FOR</span>-->

                                                                                        <div style="padding-top:5px; margin-left:20px;" class="col col-lg-12 leave_details_div">
                                                                                                <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                                                                                    <tr>
                                                                                                        <td class="bottom" style="padding-left: 20px;">
                                                                                                            <b><?php echo @$leave[0]->total_days_applied; ?> day(s)</b>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td class="" style="padding-top:5px;">
                                                                                                            INCLUSIVE DATES
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                        <td class="bottom" style="padding-left: 20px; font-weight: bold;">


                                                                                                <?php 
                                                                                                $total_days = @$details[0]->total_days_applied;
                                                                                                $prev_month = '';
                                                                                                $year = '';
                                                                                                $leave_str = '';
                                                                                                $ctr = 0;
                                                                                                $count = count($leave_dates);
                                                                                                $leave_one_day = '';
                                                                                                foreach($leave_dates as $dates){
                                                                                                    $date_from = $dates->date_from;
                                                                                                    $date_to = $dates->date_to;

                                                                                                    //if($total_days<60){
                                                                                                        $new_month =  substr($date_from,5,2);
                                                                                                        $new_year = substr($date_from,0,4);
                                                                                                        if($prev_month==""){
                                                                                                            $prev_month = $new_month;
                                                                                                            $leave_str .= date("M",strtotime($date_from)).' ';
                                                                                                        }

                                                                                                        if($prev_month!=$new_month){
                                                                                                            $leave_str .= date("M",strtotime($date_from)).' ';
                                                                                                        }

                                                                                                        if($date_from==$date_to || @$date_to==''){
                                                                                                             //$leave_str .= substr($date_from,8,2);
                                                                                                             $leave_str .= date('j',strtotime($date_from));
                                                                                                             if($dates->date_from_ishalf==1){
                                                                                                                 $leave_str .= 'am';
                                                                                                             } else if($dates->date_from_ishalf==2){
                                                                                                                 $leave_str .= 'pm';
                                                                                                             }
                                                                                                             $leave_str .= ', ';
                                                                                                             $leave_one_day = $date_from;
                                                                                                             $ctr++;
                                                                                                         } else {
                                                                                                            //$leave_str .= substr($date_from,8,2);
                                                                                                            $leave_str .= date('j',strtotime($date_from));
                                                                                                            if($dates->date_from_ishalf==1){
                                                                                                                $leave_str .= 'am';
                                                                                                            } else if($dates->date_from_ishalf==2){
                                                                                                                $leave_str .= 'pm';
                                                                                                            }
                                                                                                            /*if($dates->date_from_ishalf!=0){
                                                                                                                $leave_str .= 'hd';
                                                                                                            } */
                                                                                                            if($date_from==$date_to){
                                                                                                                $leave_str .= ', ';
                                                                                                            //} else if($dates->nodays>1){
                                                                                                            } else  {
                                                                                                                //$leave_str .= '-'.substr($date_to,8,2);
                                                                                                                if(date('m',strtotime($date_to))==date('m',strtotime($date_from))){
                                                                                                                    $leave_str .= '-'.date('j',strtotime($date_to));
                                                                                                                } else {
                                                                                                                    $leave_str .= '-'.date('M j',strtotime($date_to));
                                                                                                                }
                                                                                                                if($dates->date_to_ishalf==1){
                                                                                                                    $leave_str .= 'am';
                                                                                                                } else if($dates->date_to_ishalf==2){
                                                                                                                    $leave_str .= 'pm';
                                                                                                                }
                                                                                                                /*if($dates->date_to_ishalf!=0){
                                                                                                                    $leave_str .= 'hd';
                                                                                                                }*/
                                                                                                                $leave_str .= ', ';
                                                                                                             }
                                                                                                             $ctr++;
                                                                                                         }

                                                                                                        if($year==""){
                                                                                                            $year = $new_year;
                                                                                                            //$leave_str .= date("Y",strtotime($date_from)).' ';
                                                                                                        }
                                                                                                        if($ctr==$count){
                                                                                                            //$leave_str .= date("Y",strtotime($year)).' ';
                                                                                                                                                                        $leave_str .= $year.' ';
                                                                                                        }
                                                                                                    /*}else if($total_days==60){
                                                                                                        $leave_str = date("M j, Y",strtotime($date_from)).' - '.date("M j, Y",strtotime($date_to)).' ';
                                                                                                    } else {
                                                                                                        $leave_str = date("M j",strtotime($date_from)).' - '.date("M j, Y",strtotime($date_to)).' ';
                                                                                                    }*/

                                                                                                } echo $leave_str; ?>

                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>

                                                                                        </div>

                                                                                </div>


                                                                        </div>

                                                                        <!------------------------------------------------------>
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:20px; padding-left:30px;">


                                                                                <h4 class="boldtext" style="font-size: 14px;">Leave Credits</h4>

                                                                                <div class="row" style="">
                                                                                        <label class="col col-lg-3 control-label" style="text-align: right; padding-top:0px; font-size: 13px;">As of </label>
                                                                                        <div class="col col-lg-9">
                                                                                            <span style="text-decoration: underline; font-weight: bold;" id="asofdate"><?php echo @$leave[0]->leave_credit_asofdate!='' ? date('F j, Y',strtotime(@$leave[0]->leave_credit_asofdate)) : ''; ?></span>
                                                                                        </div>
                                                                                </div>

                                                                                <div class="col col-lg-12" style="display: inline-block;margin-left:30px">

                                                                                        <table style="width: 90%;" border="1" style="">
                                                                                            <tr>
                                                                                                <td style="width:22%;"></td>
                                                                                                <td style="width:26%; text-align: center; padding: 5px; font-weight: bold;">Vacation Leave</td>
                                                                                                <td style="width:26%; text-align: center; padding: 5px; font-weight: bold;">Sick Leave</td>
                                                                                                <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">SLP</td>
                                                                                                <td style="width:16%; text-align: center; padding: 5px; font-weight: bold;">Service credit</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td style="padding: 5px;">Total Earned</td>
                                                                                                <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofvl">
                                                                                                    <?php echo @$leave[0]->leave_credit_asofvl; ?>
                                                                                                </td>
                                                                                                <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofsl">
                                                                                                    <?php echo @$leave[0]->leave_credit_asofsl; ?>
                                                                                                </td>
                                                                                                <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofslp">
                                                                                                    <?php echo @$leave[0]->leave_credit_asofslp; ?>
                                                                                                </td>
                                                                                                <td style="text-align: center; padding: 5px; font-weight: bold;" id="asofslp">
                                                                                                    <?php echo @$leave[0]->leave_credit_asofservice; ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td style="padding: 5px;">Less this application</td>
                                                                                                <td style="text-align: center; padding: 5px;"><?php echo @$leave[0]->totalvl_days_applied ? @$leave[0]->totalvl_days_applied : ''; ?></td>
                                                                                                <td style="text-align: center; padding: 5px;"><?php echo @$leave[0]->totalsl_days_applied ? @$leave[0]->totalsl_days_applied : ''; ?></td>
                                                                                                <td style="text-align: center; padding: 5px;"><?php echo @$leave[0]->totalslp_days_applied ? @$leave[0]->totalslp_days_applied : ''; ?></td>
                                                                                                <td style="text-align: center; padding: 5px;"><?php echo @$leave[0]->totalservice_days_applied ? @$leave[0]->totalservice_days_applied : ''; ?></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td style="padding: 5px;">Balance</td>
                                                                                                <td style="text-align: center; padding: 5px;"></td>
                                                                                                <td style="text-align: center; padding: 5px;"></td>
                                                                                                <td style="text-align: center; padding: 5px;"></td>
                                                                                                <td style="text-align: center; padding: 5px;"></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                </div>



                                                                        </div>

                                                                        <!------------------------------------------------------>
                                                                        <div class="col-lg-12 col-md-12" style="padding-top:10px; padding-left:30px;">

                                                                                <h4 class="boldtext" style="font-size:14px;">Signatories</h4>

                                                                                <div id="signatories_div"  class="col col-lg-12" style="<?php echo (@$signatories && !validation_errors()) ? 'display: inline-block;' : 'display:none;'; ?>">
                                                                                        <div class="col col-lg-12" style="margin-left:30px;">
                                                                                            <table style="width: 90%;">
                                                                                                <tr>
                                                                                                    <td valign="top" style="padding-right:20px; width: 30%;">Certification of Leave Credits:</td>
                                                                                                    <td style="width: 70%;"><?php if(@$signatories[0]->certifiedby!=''){
                                                                                                            echo '<b>'.@$signatories[0]->cert_name.'</b>';
                                                                                                            echo @$signatories[0]->cert_designation_name!='' ? '<br><em>'.@$signatories[0]->cert_designation_name.'</em>' : '<br><em>'.@$signatories[0]->cert_position_name.'</em>';
                                                                                                        } else {
                                                                                                            //echo '---';
                                                                                                            echo '<b>Jonathan T. Gurion</b><br>Chief, HRMO';
                                                                                                        } ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td valign="top" style="padding-top:10px;">Recommended By:</td>
                                                                                                    <td style="padding-top:10px;"><?php if(@$signatories[0]->recommending_name!=''){
                                                                                                            echo '<b>'.@$signatories[0]->recommending_name.'</b>';
                                                                                                            echo '<br><em>'.@$signatories[0]->recommending_designation.'</em>';
                                                                                                        } else echo '---'; 
                                                                                                        
                                                                                                        if(@$signatories[0]->recommending2_name!=''){
                                                                                                            echo '<br><b>'.@$signatories[0]->recommending2_name.'</b>';
                                                                                                            echo '<br><em>'.@$signatories[0]->recommending2_designation.'</em>';
                                                                                                        } 
                                                                                                        
                                                                                                        if(@$signatories[0]->recommending3_name!=''){
                                                                                                            echo '<br><b>'.@$signatories[0]->recommending3_name.'</b>';
                                                                                                            echo '<br><em>'.@$signatories[0]->recommending3_designation.'</em>';
                                                                                                        } 
                                                                                                        ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <?php /*if(@$signatories[0]->recommending_name!=''){ ?>
                                                                                                    <tr>
                                                                                                        <td valign="top" style="padding-top:10px; text-align: right; font-style: italic; padding-right: 10px;">Action:</td>
                                                                                                        <td style="padding-top:10px;"><?php 
                                                                                                            if(@$signatories[0]->recommending_status==3){
                                                                                                                echo '<b style="color: #337ab7;">Approved</b>';
                                                                                                            } else if(@$signatories[0]->recommending_status==2){
                                                                                                                echo '<b style="color: darkred;">Disapproved</b>';
                                                                                                            } else {
                                                                                                                echo '<em>Pending</em>';
                                                                                                            }
                                                                                                            //echo @$signatories[0]->recommending_status==3 ? '<b>APPROVED</b>' : '<b style="color: darkred;">Disapproved</b>';
                                                                                                            echo '<br><em>'.@$signatories[0]->recommending_message.'</em>';
                                                                                                             ?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                <?php }*/ ?>
                                                                                                <tr>
                                                                                                    <td valign="top" style="padding-top:10px;">Approved By:</td>
                                                                                                    <td style="padding-top:10px;"><?php if(@$signatories[0]->approving_name!=''){
                                                                                                            echo '<b>'.@$signatories[0]->approving_name.'</b>';
                                                                                                            echo '<br><em>'.@$signatories[0]->approving_designation.'</em>';
                                                                                                        } ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                
                                                                                                <?php /*
                                                                                                    <tr>
                                                                                                        <td valign="top" style="padding-top:10px; text-align: right; font-style: italic; padding-right: 10px;">Action:</td>
                                                                                                        <td style="padding-top:10px;"><?php 
                                                                                                            if($signatories[0]->approving_status==3){
                                                                                                                echo '<b style="color: #337ab7;">Approved</b>';
                                                                                                            } else if($signatories[0]->approving_status==2){
                                                                                                                echo '<b style="color: darkred;">Disapproved</b>';
                                                                                                            } else {
                                                                                                                echo '<em>Pending</em>';
                                                                                                            }
                                                                                                            echo '<br><em>'.@$signatories[0]->approving_message.'</em>';
                                                                                                             ?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                 */ ?>
                                                                                            </table>

                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>

                                                                <!------------------------------------------------------>

                                                        </section>

                                                </div>
                                            
                                
                                        </div>
                                </div>

                        </div>
                        
                        <div class="col mb-4 col-lg-6">
                            
                                <div class="card mb-4">

                                        <div class="card-body">

                                                <div class="container mb-2" >

                                                        <!------------------------------------------------------>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:10px;">

                                                                <h4 class="boldtext" style="">Attachment Files</h4>

                                                                <div id=""  class="col-xs-12" style="display: inline-block; padding-left:30px; width: 100%;">

                                                                        <input type="hidden" id="leave_refno_val" value="<?php echo $leave[0]->leave_refno; ?>">
                                                                        <input type="hidden" id="leave_id_val" value="<?php echo $leave[0]->id_leave; ?>">
                                                                        <table style="width: 95%;" border="1" style="">
                                                                            <tr>
                                                                                <td style="width:20%; text-align: center; padding: 5px; font-weight: bold;">File</td>
                                                                                <td style="width:20%; text-align: center; padding: 5px; font-weight: bold;">Filename</td>
                                                                                <td style="width:20%; text-align: center; padding: 5px; font-weight: bold;">Details</td>
                                                                                <td style="width:15%; text-align: center; padding: 5px; font-weight: bold;">Date</td>
                                                                                <td style="width:15%; text-align: center; padding: 5px; font-weight: bold;">Uploaded by</td>
                                                                                <td style="width:10%; text-align: center; padding: 5px; font-weight: bold;">Actions</td>
                                                                            </tr>
                                                                            <?php foreach($details as $det){
                                                                            $files = $det->files;
                                                                            foreach($files as $fil){ ?>
                                                                                <tr>
                                                                                    <td style="text-align: center; padding: 5px;"><b><?php echo @$fil->filetype_name; ?></b><br>
                                                                                        <em><?php echo @$fil->filetype_details; ?></em>
                                                                                    </td>

                                                                                    <td style="text-align: center; padding: 5px;"><?php //echo $fil->file_name; ?>
                                                                                        <a class="preview" id="<?php echo $fil->id_leave_file; ?>" title="Click to View file" style="cursor: pointer; font-weight: bold;">
                                                                                            <?php if(@$fil->id_leave_file!=''){
                                                                                                echo $fil->file_name; ?>
                                                                                                <br><em style="font-size: smaller; font-weight: normal;">(Preview file)</em>
                                                                                            <?php } ?>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td style="text-align: center; padding: 5px;"><?php echo @$fil->file_details ? $fil->file_details : '---'; ?></td>
                                                                                    <td style="text-align: center; padding: 5px;">
                                                                                        <?php if(@$fil->id_leave_file!=''){
                                                                                            echo date('F j, Y',strtotime($fil->file_addeddate));
                                                                                            echo '<br>'.date('h:i a',strtotime($fil->file_addeddate));
                                                                                        } ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; padding: 5px;">
                                                                                        <?php echo @$fil->fname=='' ? @$fil->username : $fil->fname.' '.$fil->lname;
                                                                                        /*if(session()->get('access_level')==1){
                                                                                            echo '<em><br>'.$log->log_ip_address;
                                                                                            echo '<br>'.$log->log_comp.'</em>';
                                                                                        }*/ ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; padding: 5px;">
                                                                                        <?php if(@$fil->id_leave_file!=''){ ?>
                                                                                            <a class="preview" id="<?php echo $fil->id_leave_file; ?>" title="Click to View file" style="cursor: pointer; font-weight: bold; ">
                                                                                                <button type="button" id="btn-preview" class="btn btn-xs btn-primary" style="margin-bottom: 3px;">
                                                                                                    <span class="glyphicon glyphicon-search"></span> Preview
                                                                                                </button>
                                                                                            </a>
                                                                                            <a href="<?php echo base_url($location.$fil->file_name); ?>" target="_blank">
                                                                                                <button type="button" id="btn-download" class="btn btn-xs btn-warning" style="margin-bottom: 3px;">
                                                                                                    <span class="glyphicon glyphicon-download"></span> <span id="save_btn_lbl">Download</span>
                                                                                                </button>
                                                                                            </a>
                                                                                            <a href="<?php echo site_url('attendance/leave/delete_file/'.$fil->id_leave_file.'/view/'.$leave[0]->leave_refno); ?>" onclick="return confirm('Are you sure you want to delete? This cannot be undone.')">
                                                                                                <button type="button" id="" class="btn btn-xs btn-danger" style="margin-bottom: 3px;">
                                                                                                    <span class="glyphicon glyphicon-trash"></span> <span id="">Delete</span>
                                                                                                </button>
                                                                                            </a>
                                                                                        <?php } else if(session()->get('access_level')==1){ ?>
                                                                                            <button type="button" class="btn btn-info btn-xs upload_btn" id="<?php echo $det->id_leave_detail; ?>" leave_type="<?php echo $fil->leave_type_name; ?>" filetype_id="<?php echo $fil->id_filetype; ?>" leave_type_id="<?php echo $det->leave_type_id; ?>" style="background-color: #41CAC0; border-color: #41CAC0; padding:0 10px 0 10px;">Upload file</button>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php }
                                                                            } ?>
                                                                        </table>
                                                                </div>
                                                        </div>
                                                </div>


                                                <div class="container mb-2" >

                                                        <!------------------------------------------------------>
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top:10px;">

                                                                <h4 class="boldtext" style="">Logs</h4>

                                                                <div id=""  class="col-xs-12" style="display: inline-block; padding-left:30px; width: 100%;">

                                                                        <table style="width: 95%;" border="1" style="">
                                                                            <tr>
                                                                                <td style="width:25%; text-align: center; padding: 5px; font-weight: bold;">Date</td>
                                                                                <td style="width:25%; text-align: center; padding: 5px; font-weight: bold;">Action</td>
                                                                                <td style="width:25%; text-align: center; padding: 5px; font-weight: bold;">User</td>
                                                                                <td style="width:25%; text-align: center; padding: 5px; font-weight: bold;">Remarks</td>
                                                                            </tr>
                                                                            <?php foreach($logs as $log){ ?>
                                                                                <tr>
                                                                                    <td style="text-align: center; padding: 5px;">
                                                                                        <?php echo date('F j, Y',strtotime($log->log_date));
                                                                                        echo '<br>'.date('h:i a',strtotime($log->log_date));
                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; padding: 5px;"><?php echo $log->log_action; ?></td>
                                                                                    <td style="text-align: center; padding: 5px;">
                                                                                        <?php echo $log->log_user==1 ? 'Administrator' : $log->log_user_name;
                                                                                        if(session()->get('access_level')==1){
                                                                                            echo '<em><br>'.$log->log_ip_address;
                                                                                            echo '<br>'.$log->log_comp.'</em>';
                                                                                        } ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; padding: 5px;"><?php echo @$log->log_remarks ? $log->log_remarks : '---'; ?></td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        </table>
                                                                </div>
                                                        </div>
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
            
                $('#cancel_btn').on('click',function(){
                    
                        $('#edit_btn').slideDown();
                        $('#signatories_form').slideUp();
                        $('#signatories_div').slideDown();
                });
                

                var file_preview_url = "<?php echo site_url('attendance/leave/preview_file') ?>";
                
                $('.preview').on('click',function(){
                        $('#main-content').mask('Loading... Please wait.');
                        var post_data={};
                        post_data['file_id'] = $(this).attr('id');
                        $.ajax({
                                url: file_preview_url,
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                    $('#main-content').unmask();
                                    $('#samplediv').html(result);
                                    $('#samplediv').lightbox_me({
                                        closeClick: true, 
                                        closeSelector: "#cancel"
                                    });
                                }
                        });
                });
                
                $('.upload_btn').on('click',function(){
                        $('#main-content').mask('Loading form... Please wait.');
                        var post_data={};
                        post_data['detail_id'] = $(this).attr('id');
                        post_data['leave_type'] = $(this).attr('leave_type');
                        post_data['leave_type_id'] = $(this).attr('leave_type_id');
                        post_data['filetype_id'] = $(this).attr('filetype_id');
                        post_data['landing_page'] = 'view';
                        post_data['refno'] = $('#leave_refno_val').val();
                        post_data['leave_id'] = $('#leave_id_val').val();
                        
                        var upload_url = "<?php echo site_url('attendance/leave/upload_form'); ?>";
                        
                        $.ajax({
                                url: upload_url,
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                    $('#main-content').unmask();
                                    $('#samplediv').html(result);
                                    $('#samplediv').lightbox_me({
                                        closeClick: true, 
                                        closeSelector: "#cancel"
                                    });
                                }
                        });
                });



                
        });



</script>



<?= $this->endSection('footer_jscript') ?>