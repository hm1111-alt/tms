<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Update Your Profile</h4>
                </div>
                <div class="card-body">
                    <?php if(session()->getFlashdata('warning')): ?>
                        <div class="alert alert-warning">
                            <?= session()->getFlashdata('warning') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" action="<?= current_url() ?>" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emp_fname" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="emp_fname" name="emp_fname" 
                                           value="<?= set_value('emp_fname', @$basic[0]->emp_fname) ?>" required>
                                    <?php if(isset($validation) && $validation->hasError('emp_fname')): ?>
                                        <div class="text-danger"><?= $validation->getError('emp_fname') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emp_lname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="emp_lname" name="emp_lname" 
                                           value="<?= set_value('emp_lname', @$basic[0]->emp_lname) ?>" required>
                                    <?php if(isset($validation) && $validation->hasError('emp_lname')): ?>
                                        <div class="text-danger"><?= $validation->getError('emp_lname') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emp_mi" class="form-label">Middle Initial</label>
                                    <input type="text" class="form-control" id="emp_mi" name="emp_mi" 
                                           value="<?= set_value('emp_mi', @$basic[0]->emp_mi) ?>" maxlength="2">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emp_extname" class="form-label">Extension Name</label>
                                    <input type="text" class="form-control" id="emp_extname" name="emp_extname" 
                                           value="<?= set_value('emp_extname', @$basic[0]->emp_extname) ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="emp_cpno" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="emp_cpno" name="emp_cpno" 
                                           value="<?= set_value('emp_cpno', @$basic[0]->emp_cpno) ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Profile
                            </button>
                            <a href="<?= site_url('dashboard') ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
