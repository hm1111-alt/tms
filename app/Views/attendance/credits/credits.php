<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?php echo site_url('credits/add'); ?>" class="btn btn-light text-success"  role="button" style="">
                            <i class="fas fa-plus-circle" style=""></i>
                            <div style="color: #999;">Add</div>
                        </a>
                    </li>
                    <li style="">
                        <a href="<?php echo site_url('credits/award'); ?>" class="btn btn-light text-success"  role="button" style="">
                            <i class="fas fa-leaf" style=""></i>
                            <div style="color: #999;">Award</div>
                        </a>
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>

<style>
</style>

        
<div style="min-height: 50vh">

        <div class="card mb-4" style="border-top: 0px;">
                <div class="card-body">
                        <?php //$this->include('layout/mytable/my_table_body') ?>

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

                                <input type="hidden" id="order_by"  value="<?php echo session()->get('credits_order_by') ? session()->get('credits_order_by') : 'emp_lname'; ?>">
                                <input type="hidden" id="sort_by"  value="<?php echo session()->get('credits_sort_by') ? session()->get('credits_sort_by') : 'asc'; ?>">

                                <div class="datatable-search">
                                    <input name='search_event_list' id='search_event_list' class="datatable-input search <?php echo session()->get('credits_search') ? 'notempty' : ''; ?>" 
                                           value="<?php echo session()->get('credits_search') ? session()->get('credits_search') : ''; ?>"
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

        <script src="<?php echo base_url('public/assets/js/myscript/my_table.js'); ?>"></script>

        <script type="text/javascript">
                $(document).ready(function(){
                    
                        load_table_url = '<?php echo site_url('credits/get_all_credits') ?>';

                        //load_tab($('#employee_status').val());
                        load_listevent('','1');

                        $('#search_event_list').on('change',function(){
                                load_listevent('','1');
                        });
                        
                        $('#record_status').on('click',function(){ 
                                load_listevent('','1');
                        });

                });
        </script>

<?= $this->endSection('footer_jscript') ?>