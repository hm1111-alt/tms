<?php 
// Get the request service
$request = \Config\Services::request();
?>

<style>
    red {
        color: red;
        padding-left:10px;
    }
</style>


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

                        <td style="padding: 5px;">
                            <b><?php echo @$d->subtotal_days_applied; ?> </b>
                        </td>
                            
                    </tr>
                    
                    <tr>
                        <td colspan="4" style="padding:10px 10px 20px 30px;">
                            
                                <h4 class="boldtext" style="margin-left:-10px;">File Attachments <em style="font-size: smaller;">(for <?= @$d->leave_type_name; ?>)</em>:</h4>
                                <?php $warning_message = '';
                                if(@$d->leave_type_id==2 && @$d->subtotal_days_applied>5){ // sick leave
                                    $warning_message .= 'If filed exceeding five (5) days, application shall be accompanied by a medical certificate. 
                                                In case medical consultation was not availed of, an affidavit should be executed by an applicant.';
                                } else if(@$d->leave_type_id==12){ // vawc
                                    $warning_message .= 'VAWC leave shall be accompanied by any of the following supporting documents.';
                                }
                                
                                if($warning_message!=''){ ?>
                                    <div class="card mb-1 bg-warning">
                                        <div class="card-body">
                                            <p class="mb-0">
                                                <i class="fas fa-triangle-exclamation"></i> 
                                                <?= $warning_message; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php } ?>
                                
                                
                                <?php $files = @$d->files;
                                if(@$files){ ?>
                                        <table style="width: 100%;" border="1" style="">
                                            <tr>
                                                <td style="width: 30%;padding: 5px;"><b>File type</b></td>
                                                <td style="width: 30%;padding: 5px;"><b>Attachment</b></td>
                                                <td style="width: 20%;padding: 5px;"><b>Remarks</b></td>
                                                <td style="width: 20%;padding: 5px;"><b>Actions</b></td>

                                            </tr>
                                            <?php foreach($files as $fil){  ?>
                                                <tr>
                                                    <td style="padding-left:10px;">
                                                        <b><?php echo @$fil->filetype_name; ?></b>
                                                        <?php echo @$fil->filetype_details!='' ? '<br><em>'.@$fil->filetype_details.'</em>' : ''; ?>
                                                        <?php echo @$fil->filetype_required==1 ? '<br><br><red>* Required</red>' : ''; ?>
                                                    </td>
                                                    <td>
                                                        <?php if(@$fil->id_leave_file!=''){ ?>
                                                        
                                                                <a class="preview" id="<?php echo $fil->id_leave_file; ?>" title="Click to View file" style="cursor: pointer; font-weight: bold;">
                                                                    <?php echo $fil->file_name; //'file_'.$f; // ?>
                                                                    <br><em style="font-size: smaller; font-weight: normal;">(Preview file)</em>
                                                                </a>
                                                        
                                                        <?php } else { ?>
                                                                <em>No file uploaded.</em>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?= @$fil->file_details!='' ? $fil->file_details : '---' ?>
                                                    </td>
                                                    <td>
                                                        <?php //if(@$fil->id_leave_file!=''){ ?>
                                                        
                                                                <button type="button" class="btn btn-danger btn-xs delete_file_btn text-white mb-1" 
                                                                        id="<?php echo $fil->id_filetype; ?>" leave_detail="<?php echo $d->id_leave_detail; ?>" 
                                                                        leave_type="<?php echo $d->leave_type_id; ?>"  
                                                                        filetype_name="<?php echo $fil->filetype_name; ?>"  
                                                                        leave_type_name="<?php echo $d->leave_type_name; ?>"  
                                                                        refno="<?php echo $leave[0]->leave_refno; ?>"  
                                                                        data-bs-toggle="modal" data-bs-target="#file_modal" role="button">Delete file</button>
                                                        
                                                        <?php //} else { ?>
                                                        
                                                                <button type="button" class="btn btn-info btn-xs upload_file_btn text-white" 
                                                                        id="<?php echo $fil->id_filetype; ?>" leave_detail="<?php echo $d->id_leave_detail; ?>" 
                                                                        leave_type="<?php echo $d->leave_type_id; ?>"  
                                                                        filetype_name="<?php echo $fil->filetype_name; ?>"  
                                                                        leave_type_name="<?php echo $d->leave_type_name; ?>"  
                                                                        refno="<?php echo $leave[0]->leave_refno; ?>"  
                                                                        data-bs-toggle="modal" data-bs-target="#file_modal" role="button">Upload file</button>
                                                                
                                                        <?php //} ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                <?php } else { ?>
                                    <!---->
                                <?php } ?>
                        </td>
                    </tr>
                
            <?php } /* ?>
                <tr>
                    <td style="padding: 5px; text-align: right; padding-top:20px;" colspan="3">No. of Working Days Applied For:</td>
                    <td style="padding: 5px; padding-top:20px; font-weight: bold;" colspan="2"><?php echo @$leave[0]->total_days_applied ? @$leave[0]->total_days_applied : 0; ?> day(s)</td>
                </tr>
            <?php */
            
            } else { ?>
                
                <tr>
                    <td style="padding: 5px; text-align:center;" colspan="6">No record.</td>
                </tr>
            <?php } ?>
                
        </table>


<script type="text/javascript">
        $(document).ready(function(){
                        $('.upload_file_btn').on('click',function(){ 
                                $('#myModalLabel').html('Upload Attachment File');
                                //$('#delete_file').show();
                                $('#lbl_leave_type').html($(this).attr('leave_type_name'));
                                $('#lbl_filetype_name').html($(this).attr('filetype_name'));
                                
                                $('#detail_id').val($(this).attr('leave_detail'));
                                $('#emp_idno').val($(this).attr('refno'));
                                $('#leave_type_id').val($(this).attr('leave_type'));
                                $('#filetype_id').val($(this).attr('id'));
                                
                        });
                        
                        $('.upload_file_btna').on('click',function(){ 
                                $('#myModalLabel').html('Preview E-signature');
                                $('#submit_pic_btn').hide();
                                $('#modal_emp_fullname').html($(this).attr('emp_name'));
                                $('#modal_head_title').html($(this).attr('head_title'));
                                $('.upload_div').show();
                                $('#delete_file').show();
                                $('.upload_file_div').hide();
                                //$('#preview_photo').attr('src','<?php //echo uploads('esigns'); ?>/' + $(this).attr('filename'));
                                $('#signature_id').val($(this).attr('id'));
                                $('#modal_msg').html('')
                        });
        });

        

</script>

