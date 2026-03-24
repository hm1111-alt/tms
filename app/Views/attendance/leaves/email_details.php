
    <table style="width: 500px;" border="1" cellspacing="0" cellpadding="0" style="">
            <tr>
                <td style="width:20%;padding: 5px; font-weight: bold;">Type of Leave</td>
                <td style="width:20%;padding: 5px; font-weight: bold;">Location</td>
                <td style="width:20%;padding: 5px; font-weight: bold;">Details</td>
                <td style="width:30%;padding: 5px; font-weight: bold;">Inclusive Dates</td>
                <td style="width:10%;padding: 5px; font-weight: bold;">Total Days</td>
            </tr>
            
            <?php if(@$details){
                    foreach($details as $d){ ?>
            
                    <tr>
                        <td style="padding: 5px;"><?php echo @$d->leave_type_id!=0 ? @$d->leave_type_name : 'Others'; ?></td>
                        <td style="padding: 5px;">
                            <?php echo @$d->leave_location!='' ? @$d->leave_location : '---'; ?>
                        </td>
                        <td style="padding: 5px;">
                            <?php if(@$d->leave_type_id==0){
                                echo $d->leave_type_others;
                            } else { 
                                echo @$d->leave_purpose!='' ? @$d->leave_purpose.'<br>' : '';
                                echo @$d->leave_details!='' ? @$d->leave_details.'<br>' : '';
                            }?>
                        </td>

                        <td style="padding: 5px;" >
                            <ul style="padding-left: 5px;">
                                <?php if(@$d->dates){
                                    foreach(@$d->dates as $dat){ ?>
                                        <li style="padding-left: 2px;">
                                            <?php echo date('M. j', strtotime($dat->date_from));
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
                                            if($dat->date_to_ishalf==1){
                                                echo ' (AM)';
                                            } else if($dat->date_to_ishalf==2){
                                                echo ' (PM)';
                                            } else if(@$dat->date_to!=''){
                                                echo ' (WD)';
                                            }
                                            //echo '<br>&emsp; - <b>'.$dat->nodays.' day(s)</b>';
                                            echo '<br>&nbsp;<b>'.$dat->nodays.' day(s)</b>';
                                            
                                            ?>
                                        </li>
                                <?php }
                                } ?>
                            </ul>
                            
                        </td>

                        <td style="padding: 5px;">
                            <b><?php echo @$d->subtotal_days_applied; ?> </b>
                        </td>
                            
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
    
        <div style="padding-top:10px; padding-left:30px;">
                                                    
                <h4 style="font-size:14px;">Signatories</h4>

                <div style="margin-left:30px;width: 90%;">
                    <table style="">
                        <tr>
                            <td valign="top" style="padding-right:20px;">Certification of Leave Credits:</td>
                            <td><?php if(@$signatories[0]->certifiedby!=''){
                                    echo '<b>'.@$signatories[0]->cert_name.'</b>';
                                    echo @$signatories[0]->cert_designation_name!='' ? '<br><em>'.@$signatories[0]->cert_designation_name.'</em>' : '<br><em>'.@$signatories[0]->cert_position_name.'</em>';
                                } else echo '<b>Jonathan T. Gurion</b><br><em>HRMO</em>'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-top:10px;">Recommended By:</td>
                            <td style="padding-top:10px;"><?php if(@$signatories[0]->recommending_name!=''){
                                    echo '<b>'.@$signatories[0]->recommending_name.'</b>';
                                    echo '<br><em>'.@$signatories[0]->recommending_designation.'</em>';
                                } else echo '---'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="padding-top:10px;">Approved By:</td>
                            <td style="padding-top:10px;"><?php if(@$signatories[0]->approving_name!=''){
                                    echo '<b>'.@$signatories[0]->approving_name.'</b>';
                                    echo '<br><em>'.@$signatories[0]->approving_designation.'</em>';
                                } ?>
                            </td>
                        </tr>
                    </table>

                </div>
        </div>