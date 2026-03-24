<?php 
// Get the request service
$request = \Config\Services::request();
?>

        <table style="width: 95%;" border="1" style="">
            <tr>
                <td style="width:15%;padding: 5px; font-weight: bold;">Type of Leave</td>
                <td style="width:22%;padding: 5px; font-weight: bold;"><?php echo @$details[0]->for_monetization==1 ? 'Purpose' : 'Details'; ?></td>
                <td style="width:30%;padding: 5px; font-weight: bold;">
                    <?php if(@$details[0]->for_monetization==1){
                        echo 'Monetization';
                    } else if(@$details[0]->for_terminal==1){
                        echo 'Separation Date';
                    } else echo 'Inclusive Dates'; ?>
                </td>
                <td style="width:10%;padding: 5px; font-weight: bold;">Total Days</td>
                <?php if(@$sick_withfile==1){ ?>
                    <td style="padding: 5px; font-weight: bold;">Attach. file </td>
                <?php } ?>
                <?php if($request->uri->getSegment(2)!='add_signatories' && $request->uri->getSegment(2)!='add_files'){ ?>
                    <td style="width:10%;padding: 5px; font-weight: bold;">Delete </td>
                <?php } ?>
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

                                            <?php if(session()->get('access_level')==1 || (session()->get('empid')==@$leave[0]->employee_id && @$leave[0]->leave_status=='')){ ?>
                                                 - <button class="btn btn-success btn-xs edit_date_btn " id="<?php echo $dat->id_leave_date; ?>"  style="cursor: pointer;" title="edit date">
                                                    <em>edit</em>
                                                </button>
                                            <?php } ?>
                                            
                                            <?php 
                                            if(count(@$d->dates)>1 && $request->uri->getSegment(2)!='add_signatories' && $request->uri->getSegment(2)!='add_files'){ ?>
                                                - 
                                                <a href="<?php echo site_url('leaves/delete_date/'.$dat->id_leave_date.'/'.$edit) ?>"  onclick="return confirm('Are you sure you want to remove this date? This cannot be undone.')" style="color: #eb5448;" title="Remove date">
                                                    <em>remove</em>
                                                </a>
                                            <?php } ?>
                                        </li>
                                <?php }
                                } ?>
                            </ul>
                            
                            <?php if($request->uri->getSegment(2)!='add_signatories' && $request->uri->getSegment(2)!='add_files' && @$d->for_monetization!=1 && @$d->for_terminal!=1 && @$d->leave_type_onedate!=1){ ?>
                                <button class="btn btn-primary btn-xs add_date_btn" id="<?php echo @$d->id_leave_detail.'_'.@$d->leave_type_id; ?>" style="background-color: #428bca; border-color: #428bca;">
                                    <i class="icon-plus-sign"></i> Add Date
                                </button>
                            <?php } ?>
                            
                        </td>

                        <td style="padding: 5px;">
                            <b><?php echo @$d->subtotal_days_applied; ?> </b>
                        </td>
                            
                        <?php if(@$sick_withfile==1){ ?>
                            <td style="padding: 5px; <?php echo @$d->files ? '' : 'background-color: red;'; ?>">
                                <?php $files = @$d->files;
                                if(@$files){ $f = 0;
                                foreach($files as $fil){ $f++;  ?>
                                        <a class="preview" id="<?php echo $fil->id_leave_file; ?>" title="Click to View file" style="cursor: pointer; font-weight: bold;">
                                            <?php echo 'file_'.$f; //$fil->file_name; ?>
                                            <br><em style="font-size: smaller; font-weight: normal;">(Preview file)</em>
                                        </a>
                                        <br>
                                <?php }
                                } else { ?>
                                    <button type="button" class="btn btn-info btn-xs upload_btn" id="<?php echo $d->id_leave_detail; ?>" leave_type="<?php echo $d->leave_type_name; ?>" style="background-color: #41CAC0; border-color: #41CAC0; padding:0 10px 0 10px;">Upload file</button>
                                <?php } ?>
                            </td>
                        <?php } ?>
                            
                        <?php // if($request->uri->getSegment(3)!='add_signatories' && $request->uri->getSegment(3)!='edit' && $request->uri->getSegment(3)!='receive'){ ?>
                        <?php if($request->uri->getSegment(2)!='add_signatories' && $request->uri->getSegment(2)!='add_files'){ ?>
                            <td style="padding: 5px; text-align: center">
                                <a href="<?php echo site_url('leaves/delete_detail/'.$d->id_leave_detail.'/'.$edit); ?>" title="Delete" onclick="return confirm('Are you sure you want to delete? This cannot be undone.')">
                                    <span class="btn btn-danger btn-xs" style="background-color: #eb5448; border-color: #eb5448;"><i class="icon-trash"></i> Delete Leave</span>
                                </a>
                            </td>
                        <?php } ?>
                    </tr>
                
            <?php } ?>
                <tr>
                    <td style="padding: 5px; text-align: right; padding-top:20px;" colspan="3">No. of Working Days Applied For:</td>
                    <td style="padding: 5px; padding-top:20px; font-weight: bold;" colspan="2"><?php echo @$leave[0]->total_days_applied ? @$leave[0]->total_days_applied : 0; ?> day(s)</td>
                </tr>
            <?php } else { ?>
                
                <tr>
                    <td style="padding: 5px; text-align:center;" colspan="6">No record.</td>
                </tr>
            <?php } ?>
                
        </table>