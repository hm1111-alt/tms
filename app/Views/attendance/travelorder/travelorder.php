<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <!--<a href="<?php echo site_url('holidays/add'); ?>" class="btn btn-light text-success"  role="button" style="">
                            <i class="fas fa-plus-circle" style=""></i>
                            <div style="color: #999;">Add</div>
                        </a>-->
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>

<style>
</style>

        
<div style="min-height: 50vh">

        <div class="card mb-4">

                <div class="card-body">
                    

                        <div class="datatable-container" id="load_result">
                            <em>This module is currently under development.</em>
                        </div>
                </div>
        </div>
</div>

<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

        <script src="<?php echo js('myscript/my_table.js'); ?>"></script>

        <script type="text/javascript">
                $(document).ready(function(){
//                    
//                        load_table_url = '<?php // echo site_url('holidays/get_all_holidays') ?>';
//
//                        //load_tab($('#employee_status').val());
//                        load_listevent('','1');
//
//                        $('#search_event_list').on('change',function(){
//                                load_listevent('','1');
//                        });
//                        
//                        $('#record_status').on('click',function(){ 
//                                load_listevent('','1');
//                        });

                });
        </script>

<?= $this->endSection('footer_jscript') ?>