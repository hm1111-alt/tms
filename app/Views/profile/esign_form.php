<?php $request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>


<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <!--
                        <a class="btn btn-light text-warning"  role="button" onclick="alert('This feature is under development.')">
                            <i class="fas fa-print" style=""></i>
                            <div style="color: #999;">Print PDS</div>
                        </a>-->
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>
        
        <style>
            .form-group div.form-fields{
                padding-top: 5px;
            }
            .form-group div.form-fields:hover{
                background-color: #e1f1e7;
            }
            #profilepix {
                -webkit-border-radius: 50%;
                /*border: 10px solid #4cb9e0;*/
                display: inline-block;
                color: #6b6861;
            }
            #profilepix img {
                width: 100%;
                height: auto;
                border-radius: 50%;
                -webkit-border-radius: 50%;
            }
            div#profilepix a div#change_dp_btn {
                position: relative;
            }
            div#profilepix a div#change_dp_btn div {
                font-size: 22px;
                position: absolute;
                float: right;
                height: 40px;
                width: 40px;
                padding: 3px;
                bottom: 25px;
                right: 25px;
                border-radius: 50%;
                color: #1d1f23;
                background-color: #CCCCCC;
                opacity: 0.8;
                transform: translate(50%, 50%);
                text-align: center;
            }
            
        </style>


        <div style="min-height: 50vh">

                <form method="post" role="form" name="first_update_form" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); //echo site_url('attendance/holidays/submit'); //  ?>">

                        <div class="row">

                                <div class="col mb-4 col-lg-6">

                                        <div class="card mb-4 col-lg-12">


                                                <div class="card-body">

                                                        <h2 class="h2-class ">E-signature</h2>

                                                        <div class="form-group  mb-4 mt-4 px-4">

                                                                <div class="row">
                                                                    <label class="col col-lg-4"><b>Sample file</b></label>
                                                                    <div class="col col-lg-8 mb-2 mb-2">
                                                                            <div class="row form-group preview_div upload_div" style="min-height: 130px;">
                                                                                <label class="col-form-label label-align" for="preview_photo">Preview
                                                                                </label>
                                                                                <div class="">
                                                                                    <img id="" src="<?= images('esign-testing.jpg') ?>" alt="preview file" style="border: 2px solid #CECECE; width:300px;" />
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                </div>

                                                        </div>
                                                        
                                                        <div class="form-group  mb-4 mt-4 px-4">

                                                                <div class="row">
                                                                    <label class="col col-lg-4"><b>Your E-signature</b></label>
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
                                                                                <input id="file_id" name="file_id" type="hidden" value="<?php echo @$basic[0]->id_file; ?>">
                                                                                <input id="file_folder" name="file_folder" type="hidden" value="<?php echo @$basic[0]->file_folder; ?>">
                                                                                <input id="employee_id" name="employee_id" type="hidden" value="<?php echo @$basic[0]->id_employee; ?>">
                                                                                <input id="emp_idno" name="emp_idno" type="hidden" value="<?php echo @$basic[0]->emp_idno; ?>">

                                                                                <input type="file" name="file_upload" id="file_upload" class="form-control "  onchange="readURL1(this)" />
                                                                                <span style="font-style: italic; font-size: 11px; color: #7f7f7f;  top: 5px; left: 140px;">Format: JPG, JPEG, PNG.<br/>Filesize: 3.00 MB max.</span>
                                                                            </div> 
                                                                    </div>
                                                                </div>

                                                        </div>
                                                </div>
                                        </div>

                                        <div class="form-group" style="padding-bottom: 30px;">

                                                <div class="col-lg-12" style="text-align: right;">
                                                    <?php /*<a href="<?php echo site_url('employees/view/'.$request->uri->getSegment(3)); ?>" onclick="return confirm('All unsaved changes will be lost if you leave now. Are you sure you want to leave?')" class="ban-circle">
                                                        <button type="button" id="cancel" class="btn-light btn cancel_btn" style="margin-left:15px;float: right; background-color: #e9ecef;">Cancel</button>
                                                    </a>
                                                     */ ?>
                                                    <button type="button" class="btn btn-success btn-lg save_button1" id="submit_btn" style="display: inline-block; padding-left: 50px; padding-right: 50px;">Update</button>
                                                </div>

                                        </div>

                                </div>


                        </div>



                </form>
        </div>


        


<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

<script type="text/javascript">
    

        $(document).ready(function(){


                $('#submit_btn').on('click',function(){
                    
                        if($('#file_upload').val()==''){
                            alert('Please upload your e-signature.');
                        } else {
                            $("form[name='first_update_form']").submit();
                        }
                        //$("form[name='first_update_form']").submit();
                });
                
                
//-----------------------------------------------------------------
//-----------------------------------------------------------------

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


<?= $this->endSection('footer_jscript') ?>
        