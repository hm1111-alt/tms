
<?= $this->include('layout/header') ?>
    
<?= $this->include('layout/sidebar') ?>


        <div id="layoutSidenav_content">
                
                <div class="container-fluid px-3">
                    
                    <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                        <div class="col-xl-8">
                            <h1 class="<?php echo @$page->page_parent==0 ? 'mt-4 mb-3' : 'mt-2'; ?>">
                                <?php echo @$page->page_name2; //@$page->page_parent==0 ? @$page->page_name2 : @$page->parent_page_name2;
                                //echo $module_name; ?>
                            </h1>
                            
                            <?php if(@$page->page_parent!=0){ ?>
                                <ol class="breadcrumb mb-3">
                                    <li class="breadcrumb-item">
                                            <a href="<?= site_url(@$page->parent_class_name); ?>"><?= @$page->parent_page_name2; ?></a>
                                    </li>

                                    <?php if(@$module_function==''){ ?>
                                        <li class="breadcrumb-item active"><?= @$page->page_name2; ?></li>
                                    <?php } else { ?>
                                
                                        <li class="breadcrumb-item">
                                                <a href="<?= site_url(@$page->class_name); ?>"><?= @$page->page_name2; ?></a>
                                        </li>
                                    <?php }
                                    if(@$module_sub2!=''){ ?>
                                        <li class="breadcrumb-item active"><?= @$module_sub2; ?></li>
                                    <?php } ?>
                                    
                                </ol>
                            <?php } else if(@$module_sub2!=''){ ?>
                                <ol class="breadcrumb mb-3">
                                    <li class="breadcrumb-item active"><?= @$module_sub2; ?></li>
                                </ol>
                            <?php } ?>
                        </div>
                        <div class="col-xl-4">
                            <?= $this->renderSection('header_actions') ?>
                        </div>
                        
                    </div>

                    <?= $this->include('layout/messages') ?>
                 
                    <?= $this->renderSection('content') ?>
                
                    <!--<div class="card mb-4"><div class="card-body">When scrolling, the navigation stays at the top of the page. This is the end of the static navigation demo.</div></div>-->
                </div>
            
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <!--<div class="d-flex align-items-center justify-content-between small">-->
                        <div class="align-items-center justify-content-between small">
                            <div class="text-muted" style="text-align: right;">&copy; 2025 CLSU. All rights reserved. 
                                <br>Powered by <span class="text-success">Management Information System Office (CLSU-MISO)</span>.
                            </div>
                            <!--<div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>-->
                        </div>
                    </div>
                </footer>
        </div>
        <!----end--of--layoutSidenav_content---->

<?= $this->include('layout/footer') ?>

<?= $this->renderSection('footer_jscript') ?>