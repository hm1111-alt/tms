
<?= $this->include('layout/header') ?>
    
<?= $this->include('layout/sidebar') ?>


<div id="layoutSidenav_content">

        <div class="container-fluid px-3">

                <div class="row" style="background-color: #FFF; border-radius: 0.375rem; margin:1rem 0 1rem 0;">
                    <div class="col-xl-8">
                        <h1 class="mt-2"><?php echo $module_name; ?></h1>
                    </div>

                </div>

                <?= $this->include('layout/messages') ?>


                <div class="row" style="">

                        <?php if(@$children){
                            foreach(@$children as $page){ ?>
                    
                                    <div class="col col-lg-4 mb-4">

                            
                                        <a href="<?= site_url($page->parent_class_name.'/'.$page->class_name); ?>" style="text-decoration: none;">
                                            <div class="card rounded-3 bg-success ">
                                                <div class="card-body text-light">
                                                    <h5 class="card-title"><?= $page->page_name2 ?></h5>
                                                    <p class="card-text"></p>
                                                </div>
                                            </div>
                                        </a>
                            
                                    </div>
                            <?php }
                        } ?>
                </div>

                
                
                <div class="card mb-4">
                    <div class="card-body">
                        <!--<em>This module is currently under development.</em>-->
                        <iframe src="https://radiis.clsu.edu.ph/" style="width:100%; height:600px;"></iframe>
                    </div>
                </div>
        </div>



        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <!--<div class="d-flex align-items-center justify-content-between small">-->
                <div class="align-items-center justify-content-between small">
                    <div class="text-muted" style="text-align: right;">&copy; 2024 CLSU-HRMO. All rights reserved. 
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



        <script src="<?php echo js('myscript/my_table.js'); ?>"></script>

        <script type="text/javascript">
                $(document).ready(function(){
                    

                });
        </script>
