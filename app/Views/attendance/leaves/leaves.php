<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?php echo site_url('leaves/filing'); ?>" class="btn btn-light text-success"  role="button" style="">
                            <i class="fas fa-plus-circle" style=""></i>
                            <div style="color: #999;">File Leave</div>
                        </a>
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>

    <style>
        ul.nav-tabs {
            border-bottom: 1px solid #d2d2d2;
        }
        ul.nav-tabs li.nav-item a {
            cursor: pointer;
            color: #667fa0;
        }
        ul.nav-tabs li.nav-item a.active {
            cursor: default;
        }
        
    </style>


    <div style="min-height: 50vh">

                                <?php $active_status = session()->get('leave_status') ? session()->get('leave_status') : 1;
                                ?>
        
                                <?php //$set_year = @$this->session->userdata('leave_year') ? $this->session->userdata('leave_year') : 'all'; ?>
                                <!--<input type="hidden" id="record_status" value="<?php // echo @$this->session->userdata('leave_status') ? @$this->session->userdata('leave_status') : '1'; echo '_'.$set_year; ?>">this is for leave status-->
                                <input type="hidden" id="record_status" value="<?= $active_status; ?>"><!--this is for leave status-->
                                
                                <ul class="nav nav-tabs" style="margin-left: 5px; margin-right: 5px;">
                                    <?php if(session()->get('emp_idno')!=''){ ?>
                                        <li class="nav-item" id="myleave"><a class="nav-link">ALL LEAVE</a></li>
                                    <?php } 
                                    
                                    foreach($statuses as $status){
                                        if($status->is_disapproved==1){
                                            $disapproved_id = $status->id_status;
                                            $disapproved_name = $status->status_name;
                                        } /*else { ?>
                                            <li class="nav-item" id="<?= $status->id_status ?>">
                                                <a class="nav-link <?= $active_status==$status->id_status ? 'active' : ''; ?>">
                                                    <?= $status->is_approved==1 ? '<b>'.$status->status_name.'</b>' : $status->status_name; ?>
                                                </a>
                                            </li>
                                    <?php }*/
                                    } ?>
                                        
                                            <li class="nav-item" id="1">
                                                <a class="nav-link <?= $active_status==1 ? 'active' : ''; ?>">Pending</a>
                                            </li>
                                            <li class="nav-item" id="recommended">
                                                <a class="nav-link <?= $active_status=='recommended' ? 'active' : ''; ?>">Recommended</a>
                                            </li>
                                            <li class="nav-item" id="confirmed">
                                                <a class="nav-link <?= $active_status=='confirmed' ? 'active' : ''; ?>">HR Certified</a>
                                            </li>
                                            <li class="nav-item" id="3"><a class="nav-link <?= $active_status==3 ? 'active' : ''; ?>"><b>Approved</b></a></li>
                                        <!--<li class="nav-item" id="1"><a class="nav-link active">Pending</a></li>-->
                                        
                                        
                                        <li class="nav-item <?= $active_status==$disapproved_id ? 'active' : ''; ?>" id="<?= $disapproved_id ?>" style="margin-left:50px;"><a class="nav-link"><?= $disapproved_name ?></a></li>
                                        <li class="nav-item <?= $active_status=='cancelled' ? 'active' : ''; ?>" id="cancelled"><a class="nav-link">Cancelled</a></li>
                                        
                                        <!--<li class="nav-item" id="archived"><a class="nav-link">Archived</a></li>-->
                                </ul>
                              
                                <!--<ul class="nav nav-tabs" style="margin-left: 5px; margin-right: 5px;">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="#">Active</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Dropdown</a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">Link</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                    </li>
                              </ul>-->
            <div class="card mb-4" style="border-top: 0px;">
                    <!--<div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        DataTable Example
                    </div>-->

                    <div class="card-body">
                        <?php // $this->include('layout/mytable/my_table_body') ?>

                            <div class="datatable-top">
                                    <div class="datatable-dropdown">
                                        <label>

                                            <?php 
                                            if(@$limit){} else { ?>
                                                <?php $entry_per_page = @$entry_per_page ? $entry_per_page : 10; ?>
                                                <select id="limit" class="datatable-selector">
                                                    <option value="10" <?php echo ($entry_per_page==10) ? 'selected' : ''; ?>>10</option>
                                                    <option value="25" <?php echo ($entry_per_page==25) ? 'selected' : ''; ?>>25</option>
                                                    <option value="50" <?php echo ($entry_per_page==50) ? 'selected' : ''; ?>>50</option>
                                                    <option value="100" <?php echo ($entry_per_page==100) ? 'selected' : ''; ?>>100</option>
                                                    <option value="all" <?php echo ($entry_per_page=='all') ? 'selected' : ''; ?>>All</option>
                                                </select> entries per page
                                            <?php } ?>
                                        </label>
                                    </div>

                                    <input type="hidden" id="order_by"  value="<?php echo session()->get('leave_order_by') ? session()->get('leave_order_by') : 'emp_lname'; ?>">
                                    <input type="hidden" id="sort_by"  value="<?php echo session()->get('leave_sort_by') ? session()->get('leave_sort_by') : 'asc'; ?>">

                                    <div class="datatable-search">
                                        <input name='search_event_list' id='search_event_list' class="datatable-input search <?php echo session()->get('leave_search') ? 'notempty' : ''; ?>" 
                                               value="<?php echo session()->get('leave_search') ? session()->get('leave_search') : ''; ?>"
                                               placeholder="Search..." type="search" title="Search within table" aria-controls="datatablesSimple">
                                    </div>
                            </div>

                            <div class="datatable-container" id="load_result">

                            </div>
                    </div>
            </div>
    </div>

<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

        <script src="<?php echo js('myscript/my_table.js'); ?>"></script>

        <script type="text/javascript">
                $(document).ready(function(){
                    
                        load_table_url = '<?php echo site_url('leaves/get_leaves') ?>';

                        //load_tab($('#employee_status').val());
                        load_listevent('','1');

                        $('#search_event_list').on('change',function(){
                                load_listevent('','1');
                        });
                        
                        
                
                        $("ul.nav-tabs li").on('click',function(){
                                var record_status = $('#record_status').val();
                                //var arr = record_status.split("_");
                                //var leave_stat = arr[0];
                                //var leave_year = arr[1];

                                //$('#record_status').val($(this).attr('id')+'_'+leave_year);
                                $('#record_status').val($(this).attr('id'));
                                load_tab($(this).attr("id"));
                        });

                        $("#leave_year").on('change',function(){
                                var employee_status = $('#employee_status').val();
                                var arr = employee_status.split("_");
                                var leave_stat = arr[0];
                                //var leave_year = arr[1];
                                $('#record_status').val(leave_stat+'_'+$(this).val());
                                load_tab(leave_stat);
                        });

                        function load_tab(tab){

        //                        if(tab!='credits'){
        //                            $('#order_by').val('');
        //                            $('#sort_by').val('');
        //                        }

                                var arr = tab.split("_");

                                $("ul.nav-tabs li a.active").attr("class","nav-link");
                                $("li#"+tab+" a").attr("class","nav-link active");
                                //$("li#"+arr[0]+" a.").attr("class","nav-link active");

                                load_listevent('','1');
                        }

                });
        </script>

<?= $this->endSection('footer_jscript') ?>