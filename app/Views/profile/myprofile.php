<?php $request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>


<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <!--<a href="<?php echo site_url('holidays/add'); ?>" class="btn btn-light text-warning"  role="button" style="">-->
                        <a class="btn btn-light text-warning"  role="button" onclick="alert('This feature is under development.')">
                            <i class="fas fa-print" style=""></i>
                            <div style="color: #999;">Print PDS</div>
                        </a>
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
            
            .swiper-container { 
                width: 100%; 
                height: 400px; 
                margin: auto; 
            }
            .swiper-slide { 
                display: flex; 
                justify-content: center; 
                align-items: center; 
                font-size: 24px; 
                background: #f0f0f0; 
            }
    
            .memo_div:hover {
                /*background-color: #007B2E !important;*/
                background-color: #f5c850 !important;
                color: white;
            }
    
            .memo_div.active {
/*                background-color: #009638;*/
/*                background-color: #ffd700;*/
                background-color: var(--clsu-gold);
                color: #FFF;
            }
    
            .memo_div p {
                margin-bottom: 5px;
            }
    
            .memo_div {
                border-top: 1px solid #D8D8D8; 
                cursor: pointer;
                padding: 10px;
            }
            
            div.leave_credits div {
                font-size: 1.2rem;
                /*color: #009638;*/
                color: #009638;
                font-weight: 500;
            }
            
            div.leave_credits div div {
                border-radius: 5px;
                /*background-color: #8D5116;*/
                background-color: #007b3e;
                color: white;
                font-size: 2.3rem;
                margin-right:5px;
                width: 100%;
                font-weight: normal;
            }
            
            .pds_tabs.active {
                background-color: #009639; 
            }
            
            .pds_tabs:hover a h6{
                /*font-size: 0.9rem !important;*/
                font-weight: bold !important;
            }
            
            .pds_tabs:hover {
                background-color: #76b68f; 
            }
            
            .pds_tabs {
                background-color: #909090; 
                min-height: 60px; 
                cursor: pointer;
            }
        
            na {
                color: gray;
                font-style: italic;
                font-weight: normal;
            }
        </style>
        <div style="min-height: 50vh">

                <form method="post" role="form" name="employee_edit_form" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); //echo site_url('attendance/holidays/submit'); //  ?>">

                        <!--<h2 class="mt-2 mb-4">Edit Employee</h2>-->

                        <div class="row">

                                <div class="col mb-4 col-lg-4">

                                        <div class="card mb-4 col-lg-12">


                                                <div class="card-body">

                                                        <!--<h2 class="h2-class ">My Profile</h2>-->

                                                        <div class="form-group mb-4 mt-2 px-4"  style="text-align: center;">

                                                        <!--------------------------------------------------------->

                                                                <div id="profilepix" class="profilepix2">

																		 
                                                                        <?php 
                                                                        if($_SERVER['REMOTE_ADDR']=='::1'){
                                                                            if(@$basic[0]->profile_picture_loc=='portal' || @$basic[0]->profile_picture_loc==''){
                                                                                $filedir = 'http://localhost/employeeportal/public/assets/images/profiles/'.@$basic[0]->profile_picture;
                                                                            } else {
                                                                                $filedir = 'http://localhost/hrmisv2/public/assets/images/profiles/'.@$basic[0]->profile_picture;
                                                                            }
                                                                        } else {
                                                                            if(@$basic[0]->profile_picture_loc=='portal' || @$basic[0]->profile_picture_loc==''){
                                                                                $filedir = 'https://e-portal2.clsu.edu.ph/public/assets/images/profiles/'.@$basic[0]->profile_picture; 
                                                                            } else {
                                                                                $filedir = 'https://hrmis2.clsu.edu.ph/public/assets/images/profiles/'.@$basic[0]->profile_picture; 
                                                                            }
                                                                        } ?>
                                                                    
                                                                        <?php if(@file_get_contents($filedir) && @$basic[0]->profile_picture!=''){ ?>

                                                                            <img src="<?php echo $filedir; ?>" alt="">

                                                                        <?php } else { ?>

                                                                            <img src="<?php echo @$basic[0]->emp_sex=='Male' ? images('profiles/profile-man.png') : images('profiles/profile-woman.png'); ?>" alt="">

                                                                        <?php } ?>


                                                                        <a title="Update Profile Picture" style="cursor: pointer;"  data-bs-toggle="modal" data-bs-target="#picture_modal">
                                                                            <div id="change_dp_btn">
                                                                                <div class="">
                                                                                    <i class="fa-solid fa-camera"></i>
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                </div>


                                                                <div class="row">
                                                                    <label class=" col col-lg-12 mt-2" style="font-size: larger; font-weight: bold;">
                                                                        <h3><?php echo strtoupper($basic[0]->emp_fullname); ?></h3>
                                                                    </label>
                                                                </div>

                                                                <div class="row">
                                                                    <label class=" col col-lg-12">
                                                                        <?php //echo @$basic[0]->emp_idno; ?>
                                                                        <?php echo strtoupper(@$basic[0]->emp_position_name); ?>
                                                                    </label>
                                                                </div>

                                                                <div class="row">
                                                                    <label class=" col col-lg-12">
                                                                        <?php //echo @$basic[0]->emp_idno; ?>
                                                                        <?php echo @$basic[0]->emp_class_name; ?>
                                                                        <input type="hidden" id="class_name" value="<?php echo @$basic[0]->emp_class_name; ?>">
                                                                    </label>
                                                                </div>

                                                                <div class="row">
                                                                    <label class=" col col-lg-12">
                                                                        <a href="mailto:<?php echo @$basic[0]->emp_email_official; ?>" target="_blank">
                                                                            <?php echo @$basic[0]->emp_email_official; ?>
                                                                        </a>
                                                                    </label>
                                                                </div>

                                                                <?php if(@$basic[0]->emp_email_personal){ ?>
                                                                    <div class="row">
                                                                        <label class=" col col-lg-12">
                                                                            <?php echo @$basic[0]->emp_email_personal; ?>
                                                                        </label>
                                                                    </div>
                                                                <?php } ?>

                                                                <div class="row">
                                                                    <label class=" col col-lg-12">
                                                                            <?php echo @$basic[0]->emp_idno; ?>
                                                                    </label>
                                                                </div>


                                                        </div>

                                                </div>

                                        </div>

                                        <div class="card mb-4 col-lg-12">
                                                <!--<div class="card-header bg-success text-white" style="background-color: #8D5116;">
                                                    <i class="fas fa-briefcase"></i> Employment
                                                </div>-->

                                                <div class="card-body " style="overflow: hidden;">

                                                        <div class="form-group mb-2 px-1"  style="">
                                                                <label class="">Original date of appointment</label>
                                                                <div class="px-3 fw-bold">
                                                                    <?php echo @$basic[0]->emp_date_hired!='' ? date('F j, Y',strtotime(@$basic[0]->emp_date_hired)) : '---'; ?>
                                                                </div>
                                                        </div>
                                                    
                                                        <div class="form-group mb-2 px-1"  style="">
                                                                <label class="">Length of service</label>
                                                                <div class="px-3 fw-bold">
                                                                    <?php if(@$basic[0]->emp_date_hired!=''){ 

                                                                        $date1 = date('Y-m-d',strtotime($basic[0]->emp_date_hired));
                                                                        $date2 = date('Y-m-d');

                                                                        $diff = abs(strtotime($date2) - strtotime($date1));

                                                                        $total_days = floor($diff / (60*60*24));
                                                                        $years = floor($diff / (365*60*60*24));
                                                                        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                                                                        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

                                                                        if($years>0){
                                                                            echo '<b>';
                                                                            echo $years>1 ? $years.' years' : $years.' year' ;
                                                                            echo '</b>';
                                                                            echo ', ';
                                                                        }
                                                                        
                                                                        if($months>0){
                                                                            echo $years==0&&$months>0 ? '<b>' : '';
                                                                            echo $months>1 ? $months.' months' : $months.' month' ;
                                                                            echo $years==0&&$months>0 ? '</b>, ' : ', ';
                                                                        }
                                                                        echo $days>1 ? $days.' days' : $days.' day';
                                                                        
                                                                        /*echo '<br><em style="font-weight: normal;">('.$total_days;
                                                                        echo $total_days>1 ? ' days' : ' day';
                                                                        echo ' in total)</em>';
                                                                         */
                                                                        
                                                                    } ?>
                                                                </div>
                                                        </div>
														
                                                        <div class="form-group mb-2 px-1"  style="">
                                                                <label class="">Date of Last Promotion</label>
                                                                <div class="px-3 fw-bold">
                                                                    <?php echo @$basic[0]->last_promotion!='' ? date('F j, Y',strtotime(@$basic[0]->last_promotion)) : '---'; ?>
                                                                </div>
                                                        </div>

                                                        <div class="form-group mb-2 px-1"  style="">
                                                                <label class="">Status</label>
                                                                <div class="px-3 fw-bold">
                                                                    <?php echo @$basic[0]->emp_status2; ?>
                                                                </div>
                                                        </div>
                                                    
                                                        <div class="form-group mb-2 px-1"  style="">
                                                                <label class="">Class</label>
                                                                <div class="px-3 fw-bold">
                                                                    <?php echo @$basic[0]->emp_class_name; ?>
                                                                </div>
                                                        </div>
                                                    
                                                        <div class="form-group mb-4 px-1"  style="">
                                                                <label class="">Office</label>
                                                                <div class="px-3 ">
                                                                    <?php if(@$basic[0]->unit_name!=''){
                                                                        echo '<span class="fw-bold">';
                                                                            echo strtoupper(@$basic[0]->unit_name);
                                                                            echo @$basic[0]->unit_abbr!='' ? ' ('.@$basic[0]->unit_abbr.')' : ''; 
                                                                        echo '</span><br>';
                                                                    }
                                                                    
                                                                    if(@$basic[0]->unit_name=='' && @$basic[0]->division_name!=''){
                                                                        echo '<span class="fw-bold">';
                                                                            echo strtoupper(@$basic[0]->division_name);
                                                                            echo @$basic[0]->division_abbr!='' ? ' ('.@$basic[0]->division_abbr.')' : ''; 
                                                                        echo '</span><br>';
                                                                    } else if(@$basic[0]->division_name!=''){
                                                                            echo @$basic[0]->division_name;
                                                                            echo @$basic[0]->division_abbr!='' ? ' ('.@$basic[0]->division_abbr.')' : ''; 
                                                                        echo '<br>';
                                                                    }
                                                                    
                                                                    if(@$basic[0]->unit_name=='' && @$basic[0]->division_name==''){
                                                                        echo '<span class="fw-bold">';
                                                                            echo strtoupper(@$basic[0]->office_name);
                                                                            echo @$basic[0]->office_abbr!=' (' ? @$basic[0]->office_abbr.')' : ''; 
                                                                        echo '</span>';
                                                                    } else {
                                                                        echo @$basic[0]->office_name;
                                                                        echo @$basic[0]->office_abbr!='' ? ' ('.@$basic[0]->office_abbr.')' : ''; 
                                                                    }
                                                                    ?>
                                                                </div>
                                                        </div>
                                                    
                                                </div>
                                        </div>

                                </div>

                                <div class="col mb-4 col-lg-8">

                                    
                                        <div class="row mb-3">
                                            
                                                <?php $tab_active = 9;
                                                foreach($pages as $page){ ?>
                                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6 mb-1" style="">
                                                        <div class="p-2 text-white text-center rounded pds_tabs <?= $page->id_page==$tab_active ? 'active' : ''; ?>" id="<?= $page->id_page; ?>"  page_class="<?= $page->class_name; ?>" style="">
                                                            <a style="color: white; text-decoration: none;" class="mt-3 tile-link align-items-center flex-column text-center rounded-2">
                                                                <div class="sb-nav-link-icon"><i class="fas <?= $page->menu_icon; ?>" style="height: 1.5rem;"></i></div>
                                                                <h6 style="font-weight: 400; font-size: 0.8rem;"><?= $page->page_name; ?></h6>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                        </div>
                                    
                                    
                                        <div class="card mb-4 col-lg-12">

                                                <div class="card-body" id="pds_tab_content">

                                                </div>

                                        </div>

                                </div>


                        </div>

                </form>
        </div>


<?= $this->include('profile/modal_picture_form') ?>


<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

        <script type="text/javascript">
                $(document).ready(function(){
                    

                        $('.pds_tabs').on('click',function(){
                                
                                var page_id = $(this).attr('id');
                                var page_class = $(this).attr('page_class');
                                
                                //alert(page_id);
                                load_tab(page_id,page_class);
                                $('.pds_tabs').removeClass('active');
                                $(this).addClass('active');
                                
                        });
                        
                        load_tab(9,'personal');
                        
                        function load_tab(page_id,page_class)
                        {
                                $('#pds_tab_content').html('<na>Loading page... Please wait.</na>');
                                
                                var post_data={};
                                post_data['page_id'] = page_id;
                                post_data['page_class'] = page_class;
                                $.ajax({
                                        url: '<?php echo site_url('myprofile/load_') ?>'+page_class,
                                        type: 'POST',
                                        data: post_data,
                                        success:function(result){
                                            $('#pds_tab_content').html(result);
                                            
                                            if(page_class!='personal'){
                                                $("#pds_tab_content").slideDown(1000,function(){
                                                    $('html, body').animate({
                                                        scrollTop: parseInt($("#pds_tab_content").offset().top)-90
                                                    }, 500);
                                                });
                                            }
                                        }
                                });
                            
                        }
                        

                });
                
                
        </script>

<?= $this->endSection('footer_jscript') ?>
        