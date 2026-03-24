<?php $request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?= site_url('holidays'); ?>" class="btn btn-light"  role="button" style="">
                            <i class="fas fa-arrow-circle-left" style=""></i>
                            <div style="color: #999;">Back</div>
                        </a>
                    </li>
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>
  
  <style>
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

        <div class="card mb-4 col-lg-4">

                <div class="card-body">
                    
                            <h2 class="mt-2 mb-4">Add Holiday</h2>
                    

                            <div class="form-horizontal tasi-form" id="holiday_form">

                                    <form method="post" role="form" name="holiday_form" enctype="multipart/form-data" action="<?php echo $request->getUri()->getPath(); //echo site_url('attendance/holidays/submit'); //  ?>">

                                            <div class=" col-lg-12 col-md-12">

                                                    <div class="form-group mb-2">
                                                        <label for="holiday_date">* Date</label>
                                                        <?php if (@$validation && $validation->hasError('holiday_date')){ ?>
                                                            <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('holiday_date'); ?></label></span>
                                                        <?php } ?>
                                                        <div class="datepicker-wrapper <?php echo (@$validation && $validation->hasError('holiday_date')) ? 'has-error' : '' ?> ">
                                                            <?php $set_date = set_value('holiday_date') ? set_value('holiday_date') : @$details[0]->holiday_date; ?>
                                                            <input type="text" name="holiday_date" class="form-control datepicker" id="holiday_date" value="<?= $set_date!='' ? date('m/d/Y',strtotime($set_date)) : ''; ?>" autocomplete="off" readonly >
                                                            <span class="calendar-icon"><i class="fa fa-regular fa-calendar-days"></i></span>
                                                        </div>
                                                            
                                                        <input  type="hidden" name="holiday_id"  value="<?= @$details[0]->id_holiday; ?>">
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label for="holiday_name">* Title</label>
                                                        <?php if (@$validation && $validation->hasError('holiday_name')){ ?>
                                                            <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('holiday_name'); ?></label></span>
                                                        <?php } ?>
                                                        <input type="text" name="holiday_name" class="form-control" id="holiday_name" maxlength="100" value="<?= set_value('holiday_name') ? set_value('holiday_name') : @$details[0]->holiday_name; ?>">
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label for="category">* Category</label>
                                                        <?php if (@$validation && $validation->hasError('category')){ ?>
                                                            <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('category'); ?></label></span>
                                                        <?php } ?>
                                                        <select name="category" id="category" class="form-control" style="min-width: 100px;">
                                                            <?php 
                                                            $set_category = set_value('category') ? set_value('category') : @$details[0]->holiday_category_id;
                                                            foreach($categories as $cat){ ?>
                                                                <option value="<?php echo $cat->id_holiday_category; ?>" <?= $set_category==$cat->id_holiday_category ? 'selected' : ''; ?>>
                                                                    <?php echo $cat->holiday_category_name; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label for="coverage">* Coverage</label>
                                                        <?php if (@$validation && $validation->hasError('coverage')){ ?>
                                                            <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('coverage'); ?></label></span>
                                                        <?php } ?>
                                                        <select name="coverage" id="coverage" class="form-control" style="min-width: 100px;">
                                                            <?php 
                                                            $set_coverage = set_value('coverage') ? set_value('coverage') : @$details[0]->holiday_coverage_id;
                                                            foreach($coverages as $cor){ ?>
                                                                <option value="<?php echo $cor->id_holiday_coverage; ?>" <?= $set_coverage==$cor->id_holiday_coverage ? 'selected' : ''; ?>>
                                                                    <?php echo $cor->holiday_coverage_name; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group mb-2">
                                                        <label for="holiday_remarks">Remarks</label>
                                                        <?php if (@$validation && $validation->hasError('holiday_remarks')){ ?>
                                                            <span class="error-message"><i class="fas fa-triangle-exclamation"></i> <label class="control-label"><?= $validation->getError('holiday_remarks'); ?></label></span>
                                                        <?php } ?>
                                                        <input type="text" name="holiday_remarks" id="holiday_remarks" class="form-control" maxlength="150" value="<?= set_value('holiday_remarks') ? set_value('holiday_remarks') : @$details[0]->holiday_remarks; ?>">
                                                    </div>

                                                    <div class="form-group col-lg-12 col-md-12" style="text-align: right;">

                                                            <button type="submit" id="save" class="btn btn-lg btn-success " role="button">Save</button>
                                                            <button type="button" id="cancel" class="btn btn-light" role="button">Cancel</button>
                                                    </div>
                                            </div>

                                    </form>
                            </div>
                </div>
        </div>
</div>

<?= $this->endSection('content') ?>


<?= $this->section('footer_jscript') ?>
        
<!-- Bootstrap 5 JS and Popper -->
<!--<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>-->

<!-- Bootstrap Datepicker JS -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>-->
<script src="<?= assets('bootstrap/bootstrap-datepicker/bootstrap-datepicker.min.js'); ?>"></script>

        <script src="<?php echo base_url('public/assets/js/myscript/my_table.js'); ?>"></script>

        <script type="text/javascript">
                $(document).ready(function(){
                    

                });
        </script>

<?= $this->endSection('footer_jscript') ?>