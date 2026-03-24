<?php
// Get the request service
$request = \Config\Services::request(); ?>

<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
    <div class="mt-4">
            <ul class="page_title_button" style="list-style: none; float:right;">
                    <li style="">
                        <a href="<?php echo site_url('/leaves'); ?>" class="btn btn-light text-secondary"  role="button" onclick="return confirm('There are unsaved changes. Are you sure you want to leave?')">
                            <i class="fas fa-arrow-circle-left" style=""></i>
                            <div style="color: #999;">Back to List</div>
                        </a>
                    </li>
                    <li style="">
                        <a href="<?php echo site_url('/leaves'); ?>" class="btn btn-light text-success"  role="button" onclick="return confirm('There are unsaved changes. Are you sure you want to leave?')">
                            <i class="fas fa-file-text" style=""></i>
                            <div style="color: #999;">My Leave Card</div>
                        </a>
                    </li>
                    <li style="">
                        <a href="<?php echo site_url('/leaves/cancel_draft'); ?>" class="btn btn-light text-danger"  role="button" onclick="return confirm('Are you sure you want to cancel this leave? This cannot be undone.')">
                            <i class="fas fa-ban" style=""></i>
                            <div style="color: #999;">Cancel</div>
                        </a>
                    </li>
                    
            </ul>
    </div>
<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>

<script type="text/javascript">
        $(function() {
            
        });
</script>


        <div style="min-height: 50vh">

                <div class="card mb-4 col-lg-12">
                    <?= $this->include('attendance/leaves/leave_form_progress') ?>
                </div>
            
                <div class="row">
                        <div class="col mb-4 col-lg-2">

                        </div>
                    
                        <div class="col mb-4 col-lg-8">
                                <div class="card mb-4">
                                        <!--<div class="card-header">
                                            <i class="fas fa-table me-1"></i>
                                            DataTable Example
                                        </div>-->

                                        <div class="card-body">
                                            
                                            
                                            <div class="alert alert-warning" id="warning" role="alert">
                                                    <h4 style="padding-bottom:0px">This leave application is currently in draft status.</h4>
                                                    <h6 style="padding-top:0px">Please scroll to the <b>bottom</b> of the page and click the <b style="/*border: 1px solid black; border-radius: 3px;*/ padding: 5px;">Submit</b> button to confirm.</h6>
                                            </div>
                                            
                                            <iframe style="width:100%; min-height: 1000px;" src="<?= site_url('download_leave/'.@$leave[0]->leave_refno) ?>"></iframe>
                                                

                                                        <div class="col col-lg-12" id="submit_btns_div" style="<?php echo /*(validation_errors()) ? 'display:none;' :*/ 'display: inline-block;'; ?> text-align: right;">

                                                                <!--<div class="alert alert-warning" id="warning" role="alert">
                                                                        Email notification feature is currently not working. <br>
                                                                        Don't worry, your leave application will still be saved.  <br>
                                                                        Click <b>Submit</b> button to continue.
                                                                </div>-->

                                                                <?php if($request->uri->getSegment(2)!='download_form'){ ?>

                                                                        <a href="<?php echo site_url('leaves/add_signatories'); ?>" onclick="return confirm('Are you sure you want to go back?')" title="Go back to details">
                                                                            <button class="btn btn-default " id="<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="margin-right:10px;">
                                                                                <i class="glyphicon glyphicon-arrow-left" style="padding-right:7px;"></i> Back
                                                                            </button>
                                                                        </a>

                                                                        <a href="<?php echo site_url('leaves/submit_leave'); ?>" onclick="return confirm('Are you sure you want to submit?')" title="Submit leave">
                                                                            <button class="btn btn-primary btn-lg " id="<?php //echo @$details[0]->id_leave_detail.'_'.@$details[0]->leave_type_id; ?>" style="background-color: #3ACF93; border-color: #3ACF93; margin-right:10px;">
                                                                                <i class="glyphicon glyphicon-send" style="padding-right:7px;"></i> Submit
                                                                            </button>
                                                                        </a>

                                                                <?php } else { ?>

                                                                        <a href="<?php echo site_url('attendance/leave'); ?>">
                                                                            <button class="btn btn-default" style="margin-right:10px;">
                                                                                Close
                                                                            </button>
                                                                        </a>

                                                                        <a href="<?php echo site_url('download_leave/'.@$leave[0]->leave_refno); ?>" target="_blank" title="Print leave">
                                                                            <button class="btn btn-primary btn-lg " style="background-color: #FF7e47; border-color: #FF7e47; margin-right:10px;">
                                                                                <i class="glyphicon glyphicon-print" style="padding-right:7px;"></i> Print
                                                                            </button>
                                                                        </a>

                                                                <?php } ?>

                                                            <br>
                                                            <br>
                                                            <br>
                                                            <br>
                                                        </div>
                                        </div>
                                        
                                </div>
                                        
                        </div>
                                        
                </div>
                                        
        </div>


<section id="main-content">
	<section class="wrapper">
		<div class="row">
                        
                        <div class="col-lg-6">


                                <section class="panel">

                                        <!------------------------------------------------------>

                                </section>

                        </div>

                        <div class="col-lg-3"></div>
                                
		</div>
	</section>
</section>

<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>


<?= $this->endSection('footer_jscript') ?>