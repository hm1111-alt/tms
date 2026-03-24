
<table class="table table-hover " id="">
        <thead>
                <tr>
                        <th style='width:3%; text-align: right;'><label>#</label></th>
                        <th data-sortable="true" style="width:12%;">
                            <a class="sort datatable-sorter" id="emp_lname" title="Sort by Last Name">Employee Name</a>
                        </th>
                        <th style="width:6%;">
                            <a class="sort datatable-sorter" id="emp_idno" title="Sort by ID No.">ID #</a>
                        </th>
                        <th style="width:13%;" class="hidden-xs hidden-md hidden-sm">
                            <a class="sort datatable-sorter" id="position_name" title="Sort by Position">Position</a>
                        </th>
                        <th style="width:10%;">Leave Type</th>
                        <th style="width:10%;"><a class="sort datatable-sorter" id="added_date">Inclusive Dates</a></th>
                        <th style="width:5%;"><a class="sort datatable-sorter" id="total_days_applied" title="Sort by Date To">No. of days</a></th>

                            <th style="width:10%; text-align: center;"><a class="sort datatable-sorter" id="approved_by" title="Sort by approved by">Status</a></th>
                            <th style="width:10%;"><a class="sort datatable-sorter" id="approving_message" title="Sort by Remarks">Remarks</a></th>


                        <th style="width:7%; text-align: center;" class="hidden-xs"><a class="sort datatable-sorter" id="added_date" title="Sort">Submitted</a></th>


                        <th style="width:14%;"><label>Actions</label></th>
                </tr>
        </thead>
        <tbody>
                <?php
                $num = $details['aa'];
                $count0 = 0;
                if(@$records){
                foreach($records as $row){
                        $count0++; 
                        $num++; ?>
                        <tr class="odd gradeX" style="<?php if($status=='myleave' && @$row->is_cancelled==1){ echo 'color: #BBB;'; } ?>">
                                <td align='right' style=""><?php echo $num; ?></td>
                                <td>
                                    <!--<a href="<?php //echo site_url('attendance/leave/summary/'.@$row->emp_idno); ?>" class="employee_name" title="View Leave Report">-->
                                        <?php echo strtoupper(@$row->emp_lname).', '; 
                                        echo @$row->emp_fname.' '.@$row->emp_extname; 
                                        echo @$row->emp_mi!='' ? @$row->emp_mi.'. ' : @$row->emp_mname.' '; 
                                        //echo @$row->emp_extname; 
                                        ?>
                                    <!--</a>-->
                                    <?php /*if($this->session->userdata('access_level')==1 || $this->session->userdata('access_level')==2){ ?>
                                        <br>
                                        <a href="<?php echo site_url('attendance/credits/credits_view/'.@$row->employee_id.'/'.date('Y')); ?>" title="View Leave Card" target="_blank">
                                            <button class="btn btn-primary btn-xs" style="background-color: #428bca; border-color: #428bca;">Leave Card</button>
                                        </a>
                                    <?php }*/ ?>
                                </td>
                                <td><?php echo @$row->emp_idno; ?></td>
                                <td class="hidden-xs hidden-md hidden-sm"><?php echo @$row->position_name ? (@$row->position_abbr ? @$row->position_abbr : @$row->position_name) : '<na>---</na>'; ?></td>
                                <td><?php $type_ctr = 0;
                                    foreach(@$row->details as $det){ 
                                        $type_ctr++;
                                        echo $det->leave_type_name.'<br>';
                                    } ?>
                                </td>
                                <td>
                                                <?php 
                                                $total_days = @$row->total_days_applied;
                                                $prev_month = '';
                                                $year = '';
                                                $leave_str = '';
                                                $ctr = 0;
                                                $count = count($row->leave_dates);
                                                $leave_one_day = '';
                                                $prev_type = '';
                                                foreach($row->leave_dates as $dates){
                                                    $date_from = $dates->date_from;
                                                    $date_to = $dates->date_to;
                                                    
                                                    if($prev_type!=@$dates->type_abbr){
                                                        $prev_type=@$dates->type_abbr;
                                                        $leave_str .= $type_ctr>1 ? '('.@$dates->type_abbr.') ' : '';
                                                    }
                                                    
                                                    if($total_days<60){
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
                                                            $leave_str .= $date_to!='' ? substr($date_to,0,4).' ' : substr($date_from,0,4).' ';//$year.' ';
                                                        }
                                                    }else if($total_days==60){
                                                        $leave_str = date("M j, Y",strtotime($date_from)).' - '.date("M j, Y",strtotime($date_to)).' ';
                                                    } else {
                                                        $leave_str = date("M j",strtotime($date_from)).' - '.date("M j, Y",strtotime($date_to)).' ';
                                                    }

                                                } echo $leave_str; ?>
                                </td>
                                <td><?php echo @$row->total_days_applied; 

                                        $approves = @$row->approves ? @$row->approves : '';
                                        if(@$approves){
                                            echo '<br><br>';
                                            echo '<em>Approved ';
                                                echo @$approves[0]->vl_days_confirmed>0 ? '<b>'.@$approves[0]->vl_days_confirmed.'</b>VL, ' : '';
                                                echo @$approves[0]->vl_nopay_confirmed>0 ? '<b>'.@$approves[0]->vl_nopay_confirmed.'</b>VLnopay, ' : '';
                                                echo @$approves[0]->sl_days_confirmed>0 ? '<b>'.@$approves[0]->sl_days_confirmed.'</b>SL, ' : '';
                                                echo @$approves[0]->sl_nopay_confirmed>0 ? '<b>'.@$approves[0]->sl_nopay_confirmed.'</b>SLnopay, ' : '';
                                                echo @$approves[0]->slp_days_confirmed>0 ? '<b>'.@$approves[0]->slp_days_confirmed.'</b>SLP, ' : '';
                                            echo '</em>';
                                        }
                                        ?>
                                </td>


                                <?php if($status==2 || $status==3){ ?>
                                        <td align="center">
                                            <?php echo @$row->leave_status==3 ? '<b>APPROVED</b>' : 'Disapproved';
                                            if(@$row->approved_by!=''){ ?>
                                                <br><em style="font-size:smaller;">by <?php echo @$row->approved_byname; ?></em>
                                            <?php } ?>
                                            <br><em style="font-size:smaller;"><?php echo date('M. j, Y h:i A',strtotime(@$row->approving_dateupdated)); ?></em>
                                        </td>
                                        <!--<td><?php //echo @$row->approving_message ? nl2br(@$row->approving_message) : nl2br(@$row->recommending_message); ?></td>-->
                                    
                                <?php } else { ?>
                                    
                                        <td align="center" >
                                            <?php $leave_status = @$row->leave_status_name;
                                            if(@$row->is_cancelled==1){
                                                echo '<b style="color: #d43f3a;">Cancelled</b>';
                                            } else if(@$row->is_confirmed==1){
                                                echo '<b style="color: #169F85;">APPROVED. Received by HR.</b>';
                                            } else {
                                                echo '<b style="color: '.$row->leave_status_color.';">'.$leave_status.'</b>';
                                                if(@$row->approved_by!=''){ ?>
                                                    <!--<br><em style="font-size:smaller;">by <?php // echo @$row->approved_byname; ?></em>-->
                                                <?php }
                                            } ?>
                                        </td>
                                        
                                <?php } ?>


                                <td><?php echo @$row->approving_message; ?></td>

                                <td>
                                    <?php echo date('M. j, Y',strtotime(@$row->added_date));
                                    echo '<br>'.date('h:i A',strtotime(@$row->added_date));
                                    if(@$row->submitted_byid!=$row->employee_id && @$row->submitted_by!=''){
                                        echo '<br> <em style="font-size:smaller;">by '.@$row->submitted_by.'</em>';
                                    } ?>
                                </td>

                                <td class="td-actions">
                                    <a class="btn btn-primary btn-xs" href="<?php echo site_url('leaves/view/'.@$row->leave_refno); ?>" role="button">
                                        <i class="fas fa-search"></i> View
                                    </a>
                                    <a class="btn btn-warning btn-xs" href="<?php echo site_url('download_leave/'.@$row->leave_refno); ?>" role="button" target="_blank">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                    <?php /*
                                            <a class="btn btn-success btn-xs" href="<?php echo site_url('leaves/receive/'.@$row->leave_refno); ?>" role="button">
                                                <i class="fas fa-download"></i> Receive
                                            </a>
                                            <a href="<?php echo site_url('attendance/leave2/send_email_approved/'.@$row->id_leave); ?>" target="_blank" title="Send Email" onclick="return confirm('Are you sure you want to send?')">
                                                <button class="btn btn-info  btn-xs" style=" margin-top:3px;"><i class="icon-envelope"></i> Email Approved</button>
                                            </a>
                                     */ ?>
                                </td>
                        </tr>
                <?php }
                } else { ?>
                        <tr class="odd gradeX">
                            <td colspan="11" style="font-style: italic;">
                                <?php if(session()->get('leave_search')){ ?>
                                    Your search did not match any record.
                                    <br>Try different keywords or remove search filters.
                                <?php } else { ?>
                                    No record.
                                <?php } ?>
                            </td>
                        </tr>
                <?php } ?>
        </tbody>
</table>
                
                                 

<?php $details['num'] = $count0; ?>
<?= view('layout/mytable/my_table_pagination',$details); ?>