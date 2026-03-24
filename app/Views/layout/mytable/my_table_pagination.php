
<?php 

?>
<div class="datatable-bottom">
    <div class="datatable-info"><?php 
        if($event_count>10) {
            echo 'Showing '.(($aa)+1).' to '.(($aa)+$num).' of '.$event_count.' entries'; 
        } else if($event_count==1) {
            echo 'Showing only '.$event_count.' entry'; 
        } else if($event_count==0) {
            echo 'Showing 0 entry'; 
        } else if($event_count<=10) {
            echo 'Showing '.$event_count.' entries'; 
        } else {
            echo 'Showing no entry';
        } ?>
    </div>
    
            <input type="hidden" id="max_page" value="<?php echo $max_page; ?>">
            <input type="hidden" id="cur_page" value="<?php echo $page; ?>">
            
    <nav class="datatable-pagination">
        <ul class="datatable-pagination-list">
            
            <?php for($i=1; $i<=$max_page; $i++){ ?>
                <li class="datatable-pagination-list-item <?php if($page==1) echo 'datatable-disabled'; ?>">
                    <a data-page="1" class="datatable-pagination-list-item-link page_button">First</a>
                </li>
                <li class="datatable-pagination-list-item <?php if($page==1) echo 'datatable-disabled'; ?>">
                    <a data-page="1" class="datatable-pagination-list-item-link page_button">Previous</a>
                </li>
                
                    <?php 
                    for($i=1; $i<=$max_page; $i++){
                        if($page<=3 && $i<=5){ ?>
                            <li class="datatable-pagination-list-item <?php if($page==$i) echo 'datatable-active'; ?>">
                                <a data-page="<?php echo $i; ?>" class="datatable-pagination-list-item-link page_button"><?php echo $i; ?></a>
                            </li>
                        <?php } else if(($page==$max_page || $page>=$max_page-2) && $i>($max_page-5)){ ?>
                            <li class="datatable-pagination-list-item <?php if($page==$i) echo 'datatable-active'; ?>">
                                <a data-page="<?php echo $i; ?>" class="datatable-pagination-list-item-link page_button"><?php echo $i; ?></a>
                            </li>
                        <?php } else if($page>3){
                            if($i>=($page-2) && $i<=($page+2)){ ?>
                                <li class="datatable-pagination-list-item <?php if($page==$i) echo 'datatable-active'; ?>">
                                    <a data-page="<?php echo $i; ?>" class="datatable-pagination-list-item-link page_button"><?php echo $i; ?></a>
                                </li>
                        <?php }
                        }
                    } ?>
                        
                <li class="datatable-pagination-list-item <?php if($page==$i) echo 'datatable-active'; else if($page==$max_page) echo 'datatable-disabled'; ?>">
                    <a data-page="1" class="datatable-pagination-list-item-link page_button">Next</a>
                </li>
                <li class="datatable-pagination-list-item <?php if($page==$i) echo 'datatable-active'; else if($page==$max_page) echo 'datatable-disabled'; ?>">
                    <a data-page="1" class="datatable-pagination-list-item-link page_button">Last</a>
                </li>
                
            <?php } ?>
        </ul>
    </nav>
</div>


<script src="<?= base_url('public/assets/js/myscript/my_table.js'); ?>"></script>