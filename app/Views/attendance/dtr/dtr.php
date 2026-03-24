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

        <div class="card mb-4 col-lg-6">

                <div class="card-body">
                    

<!--                        <div class="datatable-container" id="load_result">
                            <em>This module is currently under development.</em>
                        </div>-->
                    

                        <h2><?php echo date('F').' '.date('Y'); ?></h2>

                        <div class="datatable-container" id="load_result">
                            <?php $year = date('Y');
                            $month = date('m');
                            $days = cal_days_in_month(1,intval($month),intval($year));
                            ?>
                                <table id="dtr_table" class="w-100" border="1" style="">
                                    <thead>
                                            <tr>
                                                <td rowspan="2">Date</td>
                                                <td colspan="2">Morning</td>
                                                <td colspan="2">Afternoon</td>
                                                <td rowspan="2">Total Hours</td>
                                                <td rowspan="2">Undertime</td>
                                                <td rowspan="2">Remarks</td>
                                            </tr>
                                            <tr>
                                                <td>In</td>
                                                <td>Out</td>
                                                <td>In</td>
                                                <td>Out</td>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <?php for($i=1;$i<=$days;$i++){
                                            $thisdate = $i>9 ? $year.'-'.$month.'-'.$i : $year.'-'.$month.'-0'.$i;
                                            $day = date('N',strtotime($thisdate));
                                            ?>
                                            <tr style="<?php echo $day==6 || $day==7 ? 'background-color: #e5e5e5;' : ''; ?>">
                                                <td><?php echo $i; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php if($day==6) echo 'Saturday';
                                                    else if($day==7) echo 'Sunday'; ?>
                                                </td>

                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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