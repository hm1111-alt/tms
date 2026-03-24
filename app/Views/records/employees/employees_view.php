<?php $request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?= site_url('employees'); ?>" class="btn btn-light text-default"  role="button" style="">
                            <i class="fas fa-arrow-circle-left" style=""></i>
                            <div style="color: #999;">Back to List</div>
                        </a>
                    </li>
                    <li style="">
                        <a href="<?php echo site_url('employees/edit/'.$request->uri->getSegment(3)); ?>" class="btn btn-light text-success"  role="button">
                            <i class="fas fa-pencil" style=""></i>
                            <div style="color: #999;">Edit</div>
                        </a>
                    </li>
                    <li style="">
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>


<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
                                <?php //$access = json_decode(modules::run('settings/user_types/get_class_access')); ?>
                                
                                <ul class="page_title_button" style="">
                                    
                                    
                                    <?php if(@$access->print){ ?>
                                        
                                        <li style="">
                                            <?php if($request->uri->getSegment(1)=='employees'){
                                                $download_url = site_url('employees/download/'.@$basic[0]->id_employee);
                                            } else if($request->uri->getSegment(1)=='myprofile'){
                                                $download_url = site_url('myprofile/download/'.@$basic[0]->id_employee);
                                            } ?>
                                            <a href="<?php echo $download_url; ?>" class="print" target="_blank">
                                                <span class="glyphicon glyphicon-print"></span>
                                                <div style="color: #999;">Print</div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>

                                <ul>
                                    <li>
                                        

                                    </li>
                                </ul>
                        </div>
                        
                    
                        <aside class="profile-nav col-lg-3 col-md-9 hidden-sm hidden-xs">
                                <section class="panel">
                    
                                        <?php $this->include('records/employees/view_side'); ?>

                                </section>
                        </aside>

                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" id="tabs_div">
                            
                                <input type="hidden" id="load_tab" value="<?php echo session()->get('employees_tab') ? session()->get('employees_tab') : 'tab_personal'; ?>">

                                <?php //$menu = json_decode(modules::run('dashboard/employees_menu')); ?>
                            
                                <ul class="nav nav-tabs" role="tablist">
                                    <li id="tab_personal"><a>Personal</a></li>
                                    <?php /*foreach($menu as $men){ ?>
                                        <li id="<?php echo $men->menu_id; ?>"><a <?php echo $men->page_name=='Others' ? 'href="#" id="others_a"' : ''; ?>><?php echo $men->page_name; ?></a></li>
                                    <?php }*/ ?>
                                        
                                    <!--<li id="tab_personal"><a>Personal</a></li>
                                    <li id="tab_family"><a>Family</a></li>
                                    <li id="tab_educational"><a>Educational</a></li>
                                    <li id="tab_eligibility"><a>Eligibility</a></li>
                                    <li id="tab_work_experience"><a>Work Experience</a></li>
                                    <li id="tab_voluntarywork"><a>Voluntary Work</a></li>
                                    <li id="tab_training"><a>Training</a></li>
                                    <li id="tab_others"><a href="#" id="others_a">Others</a></li>-->
                                </ul>

                                <div id="tabs_view">
                                </div>
                        </div>
                </div>
	</section>
</section>


<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

<script type="text/javascript">
        
        $(document).ready(function(){
                
                //load_tab('tab_personal');
                load_tab($('#load_tab').val());
                
                $("ul.nav-tabs li").on('click',function(){
                        load_tab($(this).attr("id"));
                });
                
                function load_tab(tab){
                        
                        $('#tabs_div').mask('Loading... Please wait.');
                        
                        $("ul.nav-tabs li.active").attr("class","");
                        $("#"+tab+"").attr("class","active");
                        var post_data={};
                        post_data['employee_id'] = $('#employee_id').val();
                        post_data['emp_idno'] = $('#emp_idno').val();
                        $.ajax({
                                url: '<?php echo site_url('employees'); ?>/' + tab,
                                type: 'POST',
                                data: post_data,
                                success:function(result){
                                        $("#tabs_view").html('');
                                        $("#tabs_view").html(result);
                                        $('#tabs_div').unmask()
                                }
                        });
                }
        });
</script>

<?= $this->endSection('footer_jscript') ?>