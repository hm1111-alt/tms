
<style type="text/css">

        table tbody tr td  {
            padding-top:5px !important;
            padding-bottom:5px !important;
        }

</style>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="form-horizontal tasi-form">

            <h3 class="boldtext">Logs (<?php echo $year; ?>)</h3>

            <?php /*<div class="form-group">

                    <div class="col-sm-12" style="clear:both;">
                        <label class="col-sm-4 control-label"><b>Name</b></label>
                        <div class="col-sm-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                            <?php echo @$employee[0]->emp_fname.' ';
                            echo @$employee[0]->emp_mi!='' ? @$employee[0]->emp_mi.'. ' : '';
                            echo @$employee[0]->emp_lname.' '.@$employee[0]->emp_extname; ?>
                            <input type="hidden" name="employee_name" value="<?php echo @$employee[0]->emp_fname.' '.@$employee[0]->emp_mname[0].'. '.@$employee[0]->emp_lname.' '.@$employee[0]->emp_extname; ?>" >
                        </div>
                    </div>

                    <div class="col-sm-12" style="clear:both;">
                        <label class="col-sm-4 control-label"><b>Date of appointment</b></label>
                        <div class="col-sm-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                            <?php echo @$appointment[0]->effectivity_date ? date('F j, Y',strtotime(@$appointment[0]->effectivity_date)) : '---'; ?>
                        </div>
                    </div>

                    <div class="col-sm-12" style="clear:both;">
                        <label class="col-sm-4 control-label"><b>Position</b></label>
                        <div class="col-sm-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                            <?php echo @$employee[0]->position_name; ?>
                        </div>
                    </div>

                    <div class="col-sm-12" style="clear:both;">
                        <label class="col-sm-4 control-label"><b>Status of Appointment</b></label>
                        <div class="col-sm-8 " style="padding-bottom: 10px; font-size: larger; font-weight: bold;">
                            <?php echo @$employee[0]->status_name; ?>
                        </div>
                    </div>

                    <div class="col-sm-12" style="clear:both;">
                        <label class="col-sm-4 control-label">Remarks</label>
                        <div class="col-sm-8" id="" style="padding-bottom: 10px;">
                            <textarea name="remarks" id="remarks" class="form-control" maxlength="150"><?php echo set_value('remarks') ? set_value('remarks') : @$details[0]->remarks; ?></textarea>
                        </div>
                    </div>
                     
            </div>*/ ?>

            <div class="form-group" >

                    <div class="col-sm-12" style="clear:both;">

                            <table class="table-hover" border="1" style="width:100%;">
                                <tr>
                                    <td>Date</td>
                                    <td align="center">VACATION</td>
                                    <td align="center">SICK</td>
                                    <td align="center">SPL</td>
                                    <td align="center">Maternity</td>
                                    <td align="center">Paternity</td>
                                    <td align="center">Remarks</td>
                                    <td align="center">Actions</td>
                                </tr>
                                <tr style="font-weight: bold;">
                                    <td>Balance forwarded as of <?php echo @$balance_forward[0]->balance_forward_date; ?></td>
                                    <td><?php echo @$balance_forward[0]->vl; ?></td>
                                    <td><?php echo @$balance_forward[0]->sl; ?></td>
                                    <td><?php echo @$balance_forward[0]->slp; ?></td>
                                    <td><?php echo @$balance_forward[0]->ml; ?></td>
                                    <td><?php echo @$balance_forward[0]->pl; ?></td>
                                    <td><?php echo @$balance_forward[0]->log_remarks; ?></td>
                                    
                                    <td><?php 
                                        if(@$balance_forward[0]->balance_forward_date!=''){
                                            $forwarded_year = date('Y',strtotime(@$balance_forward[0]->balance_forward_date));
                                            $thisyear = date('Y');
                                            $lastyear = intval($thisyear)-1;
                                            //if($access->edit && intval($forwarded_year)==$lastyear){
                                            /*if(intval($forwarded_year)==$lastyear){ ?>
                                                <a href="<?php echo site_url('credits/edit_balance/'.@$balance_forward[0]->id_credit_log); ?>">
                                                    <button class="btn btn-success btn-xs edit_fwa" ><i class="icon-pencil"></i> Edit</button>
                                                </a>
                                        <?php }*/
                                        } ?>
                                    </td>
                                </tr>
                                <?php
                                $total_vl = @$balance_forward[0]->vl;
                                $total_sl = @$balance_forward[0]->sl;
                                $total_slp = @$balance_forward[0]->slp;
                                $total_ml = @$balance_forward[0]->ml;
                                $total_pl = @$balance_forward[0]->pl;
                                
                                if(@$logs){
                                    foreach($logs as $log){
                                        if(@$log->earned_credits==''){
                                            $total_vl = $total_vl - @$log->vl;
                                            $total_sl = $total_sl - @$log->sl;
                                            $total_slp = $total_slp - @$log->slp;
                                        } else {
                                            $total_vl = $total_vl + @$log->vl;
                                            $total_sl = $total_sl + @$log->sl;
                                            $total_slp = $total_slp + @$log->slp;
                                        }
                                        $total_ml = $total_ml - @$log->ml;
                                        $total_pl = $total_pl - @$log->pl;
                                        ?>
                                    <tr>
                                        <td><?php echo $log->log_date_added; ?></td>
                                        <td><?php echo @$log->earned_credits!='' ? '+ ' : '';
                                            echo @$log->vl>0 ? @$log->vl : ''; ?>
                                        </td>
                                        <td><?php echo @$log->earned_credits!='' ? '+ ' : '';
                                            echo @$log->sl>0 ? @$log->sl : ''; ?>
                                        </td>
                                        <td><?php echo @$log->slp>0 ? @$log->slp : ''; ?></td>
                                        <td><?php echo @$log->ml>0 ? @$log->ml : ''; ?></td>
                                        <td><?php echo @$log->pl>0 ? @$log->pl : ''; ?></td>
                                        
                                        <td align="center">
                                            <?php if(@$log->earned_credits!=''){
                                                echo '<br><em><b>'.date('F',strtotime($log->earned_month)).'</b> earned credits</em>';
                                            } 
                                            if(@$log->leave_refno!=''){
                                                $dates = '';

                                                foreach(@$log->leave_dates as $dat){ ?>
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
                                                <?php } ?>
                                                    
                                                <br>
                                                
                                                <a href="<?php echo site_url('leaves/view/'.@$log->leave_refno); ?>" target="_blank">
                                                    <button class="btn btn-primary btn-xs " > View leave</button>
                                                </a>
                                                
                                                <?php //if($access->edit){ ?>
                                                    <a href="<?php echo site_url('credits/cancel_received/'.@$log->leave_refno.'/'.@$log->employee_id.'/'.$year); ?>" title="Cancel" onclick="return confirm('Are you sure you want to cancel? This cannot be undone.')">
                                                        <button class="btn btn-danger btn-xs "  style="margin-top:3px;"> Cancel Received</button>
                                                    </a>
                                            
                                                <?php //} ?>
                                            <?php }
                                            
                                            if(@$log->log_only==1){
                                                echo $log->log_remarks;
                                                //if($access->edit){ ?>
                                                    <br>
                                                    <a href="<?php echo site_url('credits/edit_log/'.$log->employee_id.'/'.$log->id_credit_log); ?>">
                                                        <button class="btn btn-success btn-xs">Edit Log</button>
                                                    </a>
                                                    <br>
                                                    <a href="<?php echo site_url('credits/delete_log/'.$log->employee_id.'/'.$log->id_credit_log); ?>" onclick="return confirm('Are you sure you want to delete this log? This cannot be undone.')">
                                                        <button class="btn btn-danger btn-xs" style="margin-top:3px;">Delete Log</button>
                                                    </a>
                                                <?php //}
                                            } ?>
                                        </td>
                                        <td></td>
                                    </tr>
                                <?php }
                                } ?>
                                    
                                <tr style="font-weight: bold;">
                                    <td align="right" style="padding-right: 10px;">Total</td>
                                    <td><?php echo $total_vl; ?></td>
                                    <td><?php echo $total_sl; ?></td>
                                    <td><?php echo $total_slp; ?></td>
                                    <td><?php echo $total_ml; ?></td>
                                    <td><?php echo $total_pl; ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        
                        <br>
                        <br>
                        <br>

                        
                    </div>
            </div>


            <?php /*
            <span style="font-style: italic; float:right;">Updating this will reset the '<b>Leave Credits</b>' of employee and will add a new record in 'Leave Credits Logs'.</span>

            <br>
            <br>
            <button type="submit" class="btn btn-primary btn-lg" name="importsubmit" id="" style="display: inline-block; margin-top: -10px; float: right;">Submit</button>
             * 
             */ ?>


    </div>

</div>
