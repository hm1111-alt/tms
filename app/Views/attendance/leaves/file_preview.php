
<?php if(@$details){
foreach($details as $row){ ?>
        <h3 class="boldtext">
            <span style="font-size:90%;">Attached File</span> &emsp;
        </h3>


        <div class="form-horizontal tasi-form">

                <div class="form-group" style="padding-left:20px; padding-right:20px;">

                        <?php /*
                        <div class="col-sm-12" style="clear: both;">
                            <label class="col-sm-3 control-label">Year</label>
                            <div class="col-sm-9" style="padding-top: 2px;">
                                <?php echo @$row->leave_yearfrom.' - '.@$row->leave_yearto; ?>
                            </div>
                        </div>
                    
                        <div class="col-sm-12" style="clear: both;">
                            <label class="col-sm-3 control-label">Employee</label>
                            <div class="col-sm-9" style="padding-top: 2px;">
                                <?php echo @$row->emp_fullname; ?>
                            </div>
                        </div>
                         * 
                         */ ?>
                        
                        <div class="col-sm-12" style="clear: both;">
                            <label class="col-sm-3 control-label">File name</label>
                            <div class="col-sm-9" style="padding-top: 2px;">
                                <?php echo @$row->file_name ? @$row->file_name : '---'; ?>
                            </div>
                        </div>

                        <div class="col-sm-12" style="clear: both;">
                            <label class="col-sm-3 control-label">Details</label>
                            <div class="col-sm-9" style="padding-top: 2px;">
                                <?php echo @$row->file_details ? @$row->file_details : '---'; ?>
                            </div>
                        </div>

                        <div class="col-sm-12" style="clear: both;">
                            <label class="col-sm-3 control-label">Date Added</label>
                            <div class="col-sm-9" style="padding-top: 2px;">
                                <?php echo @$row->file_addeddate!='' ? date('F j, Y h:i A',strtotime(@$row->file_addeddate)) : '---'; ?>
                            </div>
                        </div>

                        <?php /*if(@$row->caw_file_name!=''){ ?>
                            <br>
                            <br>
                            <embed src="<?php echo base_url('uploads/trainings/caws/'.@$row->caw_file_name); ?>"  style="border: 1px solid #BBB; width: 100%; height: 500px;">
                        <?php }*/ ?>
                            
                        <?php if(@$row->file_name!=''){ 
                            $filename_arr = explode('.',@$row->file_name);
                            $arr_count = count($filename_arr);
                            $ext = $filename_arr[$arr_count-1];
                            if($ext=='jpg' || $ext=='JPG' || $ext=='jpeg' || $ext=='JPEG' || $ext=='png' || $ext=='PNG'){  ?>
                                <br>
                                <br>
                                <embed src="<?php echo base_url($location.@$row->file_name); ?>"  style="border: 1px solid #BBB; width: 100%; margin-top:20px;">
                        <?php } else { ?>
                            <br>
                            <br>
                            <embed src="<?php echo base_url($location.@$row->file_name); ?>"  style="border: 1px solid #BBB; width: 100%; height: 500px; margin-top:20px;">
                        <?php }
                        } ?>
                </div>

                <div class="form-group col-lg-12 col-md-12" style="text-align: right;">
                    
                        <?php if(@$row->file_name==''){ ?>
                            <button type="button" class="btn btn-info" id="upload_btn" style="background-color: #41CAC0; border-color: #41CAC0; padding:0 10px 0 10px;">Upload</button>
                        <?php } else {
                            if($access->print==1){ ?>
                                <a href="<?php echo base_url($location.@$row->file_name); ?>" target="_blank">
                                    <button type="button" id="btn-download" class="btn btn-success" style="">
                                        <span class="glyphicon glyphicon-download"></span> <span id="save_btn_lbl">Download</span>
                                    </button>
                                </a>
                            <?php } 
                            //if($access->edit==1){ ?>
                                <a href="<?php echo site_url('attendance/leave/delete_file/'.@$row->id_leave_file); ?>" title="Delete File" onclick="return confirm('Are you sure you want to delete? This cannot be undone.')">
                                    <button type="button" class="btn btn-danger" id="" style="background-color: #d9534f; border-color: #d43f3a;">Delete</button>
                                </a>
                        <?php //}
                        } ?>
                    
                        <button type="button" id="cancel" class="btn page_button">Close</button>
                </div>
                <br>

        </div>
<?php }
} ?>

