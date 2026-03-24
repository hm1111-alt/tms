
<!-- Bootstrap Modal -->
<div class="modal fade" id="picture_modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Update Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="post" role="form" name="upload_pic_form" enctype="multipart/form-data" action="<?php echo site_url('profilepicture'); //  ?>">
                    <div class="modal-body">
                            <!-- Content Goes Here -->
                            <div class="row">
                                <label class="col col-lg-4 right"><b>2x2 picture</b></label>
                                <div class="col col-lg-8 mb-2 mb-2">
                                    <?php if(@$validation && $validation->hasError('file_upload')){ ?>
                                        <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?php echo $validation->getError('file_upload'); ?></label></span>
                                    <?php } ?>

                                        <div class="row form-group preview_div upload_div" style="min-height: 130px; display: none;">
                                            <label class="col-form-label label-align" for="preview_photo">Preview
                                            </label>
                                            <div class="">
                                                <img id="preview_photo" src="#" alt="preview file" style="" />
                                            </div>
                                        </div>
                                        <div>
                                            <input id="page_to" name="page_to" type="hidden" value="<?php echo $class_name; ?>">

                                            <input type="file" name="file_upload" id="file_upload" class="form-control "  onchange="readURL1(this)" />
                                            <span style="font-style: italic; font-size: 11px; color: #7f7f7f;  top: 5px; left: 140px;">Format: JPG, JPEG, PNG.<br/>Filesize: 3.00 MB max.</span>
                                        </div> 
                                </div>
                            </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="submit_pic_btn">Update</button>
                    </div>
            </form>
            
        </div>
    </div>
</div>
                                                            
                                                            
<script type="text/javascript">
    

        $(document).ready(function(){

                $('#submit_pic_btn').on('click',function(){
                    
                        if($('#file_upload').val()==''){
                            alert('Please select a file.');
                        } else {
                            $("form[name='upload_pic_form']").submit();
                        }
                        //$("form[name='first_update_form']").submit();
                });
                
        });

                        
        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                var filsize = parseFloat(input.files[0].size);
                var maxsize = parseFloat(3145728) ; // 3mb = 3145728 , 5mb = 5242880

                if(filsize>maxsize){
                    alert('The file selected is larger than max size allowed.');
                    $('#file_upload').val('');
                    $('#preview_photo').html('');
                    $('.preview_div').slideUp();
                    $('#preview_photo').slideUp();
                } else {

                    var filenam = input.files[0].name;
                    var res = filenam.split('.');
                    var len = parseInt(res.length);
                    var len2 = len-1;
                    var extension = res[len2];

                    if(extension=='jpg' || extension=='jpeg' || extension=='png' || extension=='JPG' || extension=='JPEG' || extension=='PNG'){
                        reader.onload = function (e) {

                            $('.preview_div').slideDown();
                            $('#preview_photo').slideDown();
                            $('#preview_photo')
                                .attr('src', e.target.result)
                                .width(200)
                                .height(auto);
                        };
                    } else {
                        alert('The file type of selected file is invalid.');
                        $('#file_upload').val('');
                        $('#preview_photo').html('');
                        $('.preview_div').slideUp();
                        $('#preview_photo').slideUp();
                    }

                    reader.readAsDataURL(input.files[0]);
                }
           }
        }
    </script>

