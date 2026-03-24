<?php $request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?php echo site_url('employees/view/'); ?>" class="btn btn-light text-danger"  role="button" style="" onclick="return confirm('All unsaved changes will be lost if you leave now. Are you sure you want to leave?')">
                            <i class="fas fa-ban" style=""></i>
                            <div style="color: #999;">Cancel</div>
                        </a>
                    </li>
                    <li style="">
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>

        <style>
            .form-group div.col-lg-12:hover{
                background-color: #e1e8f1;
            }
        </style>
    
        <!-- Bootstrap Datepicker CSS -->
        <link rel="stylesheet" href="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.css'); ?>">
        
        <script type="text/javascript">
                $(document).ready(function(){
                        $('.datepicker').datepicker({
                            format: 'mm/dd/yyyy',
                            autoclose: true,
                            todayHighlight: true,
                            beforeShowDay: function(date) {
                                
                            },
                        });
                    

                });
        </script>

<div style="min-height: 50vh">

        <form method="post" role="form" name="employee_edit_form" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); //echo site_url('attendance/holidays/submit'); //  ?>">

                <h2 class="mt-2 mb-4">Edit Employee</h2>
                
                <?= $this->include('records/employees/form_basic') ?>
                
                
        </form>
</div>
        
        

<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>

<script src="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>
    <!--<script src="<?php // echo jquery('myscript/employee_form.js'); ?>"></script>-->

    <script type="text/javascript">
            $(document).ready(function(){

                    $('.save_button1').on('click',function(){
                            $("#employee_edit_form").attr("action", "<?php echo $request->getUri()->getPath(); ?>");
                            $("#employee_edit_form").mask('Saving... Please wait and don\'t close this window.')
                            $('form[name=employee_edit_form]').submit();
                    });

                    $('#save_button2').on('click',function(){
                            $("#employee_edit_form").attr("action", "<?php echo $request->getUri()->getPath(); ?>/2");
                            $("#employee_edit_form").mask('Saving... Please wait and don\'t close this window.')
                            $('form[name=employee_edit_form]').submit();
                    });

                    $('input[type="checkbox"].checkbox').checkbox({
                        buttonStyle: 'btn-base',
                        buttonStyleChecked: 'btn-success',
                        checkedClass: 'icon-check',
                        uncheckedClass: 'icon-check-empty'
                    });

                    search_position = "<?php echo site_url('employees/search_position'); ?>";
                    search_designation = "<?php echo site_url('employees/search_designation'); ?>";
                    search_city= "<?php echo site_url('employees/search_city'); ?>";
                    search_province= "<?php echo site_url('employees/search_province'); ?>";



            });

    </script>


<?= $this->endSection('footer_jscript') ?>