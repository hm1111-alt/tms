
            <div class="datatable-top">
                    <div class="datatable-dropdown">
                        <label>
                            
                            <?php 
                            if(@$limit){} else { ?>
                                <?php $entry_per_page = @$entry_per_page ? $entry_per_page : 10; ?>
                                <select id="limit" class="datatable-selector">
                                    <option value="10" <?php echo ($entry_per_page==10) ? 'selected' : ''; ?>>10</option>
                                    <option value="25" <?php echo ($entry_per_page==25) ? 'selected' : ''; ?>>25</option>
                                    <option value="50" <?php echo ($entry_per_page==50) ? 'selected' : ''; ?>>50</option>
                                    <option value="100" <?php echo ($entry_per_page==100) ? 'selected' : ''; ?>>100</option>
                                    <option value="all" <?php echo ($entry_per_page=='all') ? 'selected' : ''; ?>>All</option>
                                </select> entries per page
                            <?php } ?>
                        </label>
                    </div>
                
                    <input type="hidden" id="order_by" 
                           value="<?php //if($this->uri->segment(2)==''||$this->uri->segment(2)=='employees') echo $this->session->userdata('employees_order_by'); ?>">
                    <input type="hidden" id="sort_by" 
                           value="<?php //if($this->uri->segment(2)==''||$this->uri->segment(2)=='employees') echo $this->session->userdata('employees_sort_by'); else echo 'desc'; ?>">
    
                    <div class="datatable-search">
                        <input name='search_event_list' id='search_event_list' class="datatable-input search" 
                               value=""
                               placeholder="Search..." type="search" title="Search within table" aria-controls="datatablesSimple">
                    </div>
            </div>

            <div class="datatable-container" id="load_result">

            </div>