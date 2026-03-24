
<!-- Load Mask Overlay -->
<div class="load-mask" id="loadmask">
  <div class="text-center">
    <div class="spinner-border text-light" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mask-loading-text">Loading, please wait...</div>
  </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="card mb-4 bg-success text-white">
        <div class="card-body">
            <p class="mb-0">
                <!--This is a <code>success</code> message. For sample only.-->
                <i class="fas fa-circle-check"></i> 
                <?= session()->getFlashdata('success') ?>
            </p>
        </div>
    </div>
<?php endif; ?>


<?php //echo $this->session->flashdata('login_error'); ?>

<?php if (session()->getFlashdata('warning')): ?>
    <div class="card mb-4 bg-warning">
        <div class="card-body">
            <p class="mb-0">
                <!--This is a <code>warning</code> message. For sample only.-->
                <i class="fas fa-triangle-exclamation"></i> 
                <?= session()->getFlashdata('warning') ?>
            </p>
        </div>
    </div>
<?php endif; ?>

<?php if (@$warning!=''): ?>
    <div class="card mb-4 bg-warning">
        <div class="card-body">
            <p class="mb-0">
                <!--This is a <code>warning</code> message. For sample only.-->
                <i class="fas fa-triangle-exclamation"></i> 
                <?= @$warning; ?>
            </p>
        </div>
    </div>
<?php endif; ?>


<?php if (session()->getFlashdata('error')): ?>
    <div class="card mb-4 bg-danger text-white">
        <div class="card-body">
            <p class="mb-0">
                <!--This is an error message. For sample only.-->
                <i class="fas fa-triangle-exclamation"></i> 
                <?= session()->getFlashdata('error') ?>
            </p>
        </div>
    </div>
<?php endif; ?>

<?php if (@$error!=''): ?>
    <div class="card mb-4 bg-danger text-white">
        <div class="card-body">
            <p class="mb-0">
                <!--This is an error message. For sample only.-->
                <i class="fas fa-triangle-exclamation"></i> 
                <?= @$error ?>
            </p>
        </div>
    </div>
<?php endif; ?>