
            
<!-- Bootstrap Modal -->
<div class="modal fade" id="file_modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Upload Attachment File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
                <form id="file_form" name="file_form" data-parsley-validate class="form-horizontal input_mask" method="post" action="<?php echo site_url('leaves/upload_file'); ?>" enctype="multipart/form-data" >

                        <?php //foreach($file_details as $row){ ?>

                                <div class="modal-body">


                                    <div class="row">
                                        <label class="col col-lg-4 right"><b>Leave Type</b></label>
                                        <div class="col col-lg-8 mb-2" id="lbl_leave_type">
                                            <?php //echo $leave_type; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <label class="col-lg-4 right"><b>File type</b></label>
                                        <div class="col col-lg-8 mb-2" id="lbl_filetype_name">
                                            (Attachment file)
                                        </div>
                                    </div>
                                    
                                    <div class="row">

                                        <label class="col-lg-4 right"><b>Upload File</b></label>
                                        <div class="col col-lg-8 mb-2">

                                                <div class="row form-group preview_div upload_div" style="min-height: 130px; display: none;">
                                                    <label class="col-form-label label-align" for="preview_photo">Preview
                                                    </label>
                                                    <div class="">
                                                        <img id="preview_photo" src="#" alt="preview file" style="" />
                                                    </div>
                                                </div>
                                            
                                                <input id="detail_id" name="detail_id" type="hidden" value="<?php echo @$detail_id; ?>">
                                                <input id="leave_id" name="leave_id" type="hidden" value="<?php echo @$leave_id; ?>">
                                                <input id="leave_type_id" name="leave_type_id" type="hidden" value="<?php echo @$leave_type_id; ?>">
                                                <input id="emp_idno" name="emp_idno" type="hidden" value="<?php echo @$refno; ?>">
                                                <input id="filetype_id" name="filetype_id" type="hidden" value="<?php echo @$filetype_id; ?>">
                                                <!--<input id="file_prefix" name="file_prefix" type="hidden" value="">-->
                                                <!--<input id="landing_page" name="landing_page" type="hidden" value="<?php echo @$landing_page; ?>">-->

                                                <div class="upload_file_div">
                                                    <input type="file" name="file_upload" id="file_upload" class="form-control "  onchange="readURL1(this)" />
                                                    <span style="font-style: italic; font-size: 11px; color: #7f7f7f;  top: 5px; left: 140px;">Format: PDF, JPG, JPEG, PNG.<br/>Filesize: 10.00 MB max.</span>
                                                </div> 
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        
                                        <label class="col-lg-4 right"><b>Remarks</b></label>
                                        <div class="col col-lg-8 mb-2">
                                            <input type="text" name="file_details" class="form-control" placeholder="Remarks" maxlength="150">
                                        </div>

                                        <!--<label class="col-lg-4 control-label"><b>File name</b></label>
                                        <div class="col-lg-8"col-lg-8>
                                            <label id="new_filename"></label>
                                        </div>-->
                                    </div>
                                    
                                </div>


                                <div class="modal-footer">
                                        <button type="button" id="btn-upload" class="btn btn-primary" style="">
                                                <span class="glyphicon glyphicon-upload"></span> <span id="save_btn_lbl">Upload</span>
                                        </button>
                                        <button type="button" id="cancel" class="btn page_button" data-bs-dismiss="modal">Close</button>
                                </div>

                        <?php //} ?>
                    
                </form>
            
        </div>
    </div>
</div>
                 
    
<script type="text/javascript">
    
        $(document).ready(function(){
                    
                $('#btn-upload').on('click',function(){
                    
                        if($('#file_upload').val()==''){
                            alert('Please select a file.');
                        } else {
                            $('#file_modal').modal('hide');
                            $("form[name='file_form']").submit();
                        }
                });
        
        });
        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                var filsize = parseFloat(input.files[0].size);
                var maxsize = parseFloat(10485760) ; // 3mb = 3145728 , 5mb = 5242880 , 10mb - 10485760 

                var filenam = input.files[0].name;
                var res = filenam.split('.');
                var len = parseInt(res.length);
                var len2 = len-1;
                var extension = res[len2];
                    
                if(filsize>maxsize){
                    alert('The file selected is larger than max size allowed.');
                    $('#file_upload').val('');
                    $('#preview_photo').html('');
                    $('.preview_div').slideUp();
                    $('#preview_photo').slideUp();
                    
                } else if(extension!='jpg' && extension!='jpeg' && extension!='JPG' && extension!='JPEG' && extension!='pdf' && extension!='PDF' && extension!='png' && extension!='PNG'){
                    
                    alert('Please select jpg,jpeg, or pdf file only.');
                    $('#file_upload').val('');
                    $('#preview_photo').html('');
                    $('.preview_div').slideUp();
                    $('#preview_photo').slideUp();
                    
                } else {

                    if(extension=='jpg' || extension=='jpeg' || extension=='JPG' || extension=='JPEG' || extension=='png' || extension=='PNG'){
                        reader.onload = function (e) {

                            $('.preview_div').slideDown();
                            $('#preview_photo').slideDown();
                            $('#preview_photo')
                                .attr('src', e.target.result)
                                .width(200)
                                .height(auto);
                        };
                    } else {
                        $('#preview_photo').html('');
                        $('.preview_div').slideUp();
                        $('#preview_photo').slideUp();
                    }

                    reader.readAsDataURL(input.files[0]);
                }
           }
        }
        
</script>
