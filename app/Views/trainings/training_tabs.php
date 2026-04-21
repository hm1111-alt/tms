<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
<div class="mt-4">
    <ul class="page_title_button" style="list-style: none; float:right;">
        <li>
            <a href="#" class="btn btn-success" role="button" onclick="showAddTrainingModal(); return false;">
                <i class="fas fa-plus-circle"></i>
                <div style="color:#000">Add Training</div>
            </a>
        </li>
        <li class="ms-2">
            <a href="<?= site_url('services/requests') ?>" class="btn btn-light" role="button">
                <i class="fas fa-arrow-circle-left"></i>
                <div style="color:#000">Back to Requests</div>
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
.datatable-top {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    border: 1px solid #dee2e6;
}

.datatable-selector {
    padding: 5px 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}

.datatable-input.search {
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    min-width: 250px;
}
</style>

<div style="min-height:50vh">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="card-title mb-0">Learning and Development (L&D) Interventions / Training Programs</h4>
                    <p class="mb-0 mt-2 text-muted">View your approved training programs and pending training requests</p>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="trainingTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="true">
                                Training Programs Attended
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">
                                <i class="fas fa-clock me-2"></i>View Pending Trainings
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-4" id="trainingTabContent">
                        <div class="tab-pane fade show active" id="approved" role="tabpanel" aria-labelledby="approved-tab">
                            <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="datatable-dropdown">
                                        <label>
                                            <?php $entry_per_page = 10; ?>
                                            <select id="limit_approved" class="datatable-selector">
                                                <option value="10" <?= ($entry_per_page==10)?'selected':''; ?>>10</option>
                                                <option value="25" <?= ($entry_per_page==25)?'selected':''; ?>>25</option>
                                                <option value="50" <?= ($entry_per_page==50)?'selected':''; ?>>50</option>
                                                <option value="100" <?= ($entry_per_page==100)?'selected':''; ?>>100</option>
                                                <option value="all" <?= ($entry_per_page=='all')?'selected':''; ?>>All</option>
                                            </select> entries per page
                                        </label>
                                    </div>
                                </div>

                                <div class="datatable-search ms-auto">
                                    <input name="search_approved" id="search_approved" class="datatable-input search" 
                                        value=""
                                        placeholder="Search approved trainings..." type="search">
                                </div>

                                <input type="hidden" id="order_by_approved" value="addeddate">
                                <input type="hidden" id="sort_by_approved" value="desc">
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="datatablesApproved">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Title of Seminar/Conference/Workshop/Short Courses</th>
                                            <th>Employee</th>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Number of Hours</th>
                                            <th>Type of Training</th>
                                            <th>Venue</th>
                                            <th>Sponsored By/Conducted By</th>
                                            <th>Certificate</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="approved_result">
                                        <tr>
                                            <td colspan="11" class="text-center">Loading approved training records...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php 
                            try {
                                echo view('layout/mytable/my_table_pagination');
                            } catch (Exception $e) {
                              
                            }
                            ?>
                        </div>

                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                            <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    <div class="datatable-dropdown">
                                        <label>
                                            <?php $entry_per_page = 10; ?>
                                            <select id="limit_pending" class="datatable-selector">
                                                <option value="10" <?= ($entry_per_page==10)?'selected':''; ?>>10</option>
                                                <option value="25" <?= ($entry_per_page==25)?'selected':''; ?>>25</option>
                                                <option value="50" <?= ($entry_per_page==50)?'selected':''; ?>>50</option>
                                                <option value="100" <?= ($entry_per_page==100)?'selected':''; ?>>100</option>
                                                <option value="all" <?= ($entry_per_page=='all')?'selected':''; ?>>All</option>
                                            </select> entries per page
                                        </label>
                                    </div>
                                </div>

                                <div class="datatable-search ms-auto">
                                    <input name="search_pending" id="search_pending" class="datatable-input search" 
                                        value=""
                                        placeholder="Search pending trainings..." type="search">
                                </div>

                                <input type="hidden" id="order_by_pending" value="addeddate">
                                <input type="hidden" id="sort_by_pending" value="desc">
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="datatablesPending">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Title of Seminar/Conference/Workshop/Short Courses</th>
                                            <th>Employee</th>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Number of Hours</th>
                                            <th>Type of Training</th>
                                            <th>Venue</th>
                                            <th>Sponsored By/Conducted By</th>
                                            <th>Certificate</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pending_result">
                                        <tr>
                                            <td colspan="12" class="text-center">Loading pending training records...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <?php 
                            try {
                                echo view('layout/mytable/my_table_pagination');
                            } catch (Exception $e) {

                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="trainingDetailModal" tabindex="-1" aria-labelledby="trainingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trainingDetailModalLabel">Training Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="trainingDetailContent">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>
<script src="<?php echo js('myscript/my_table.js'); ?>"></script>
<script>
var approved_url, pending_url;

$(document).ready(function() {
    approved_url = '<?php echo site_url('trainings/load_approved_trainings') ?>';
    pending_url = '<?php echo site_url('trainings/load_pending_trainings') ?>';
    
    console.log('Initializing training tables...');
    console.log('Approved URL:', approved_url);
    console.log('Pending URL:', pending_url);
    
    loadApprovedTrainings();

    $('#approved-tab').on('click', function() {
        loadApprovedTrainings();
    });
    
    $('#pending-tab').on('click', function() {
        loadPendingTrainings();
    });
    
    $(document).on('change', '#limit_approved', function(){
        console.log('Limit changed to:', $(this).val());
        loadApprovedTable(1);
    });
    
    $(document).on('keyup', '#search_approved', function(){
        console.log('Search updated:', $(this).val());
        loadApprovedTable(1);
    });
    
    $(document).on('change', '#limit_pending', function(){
        console.log('Limit changed to:', $(this).val());
        loadPendingTable(1);
    });
    
    $(document).on('keyup', '#search_pending', function(){
        console.log('Search updated:', $(this).val());
        loadPendingTable(1);
    });
    
    window.loadPendingTable = loadPendingTable;
    window.loadApprovedTable = loadApprovedTable;
    window.bindPaginationEvents = bindPaginationEvents;
    
    function loadApprovedTable(page) {
        console.log('=== Loading Approved Table ===');
        console.log('Page:', page);
        console.log('Limit element exists:', $('#limit_approved').length > 0);
        console.log('Search element exists:', $('#search_approved').length > 0);
        
        var limit = $('#limit_approved').val();
        var search = $('#search_approved').val();
        var order_by = $('#order_by_approved').val();
        var sort_by = $('#sort_by_approved').val();
        
        console.log('Values - Limit:', limit, 'Search:', search, 'Order:', order_by, 'Sort:', sort_by);
        
        $.post(approved_url, {
            limit: limit,
            page: page,
            search: search,
            order_by: order_by,
            sort_by: sort_by
        }, function(data) {
            console.log('Approved table data loaded successfully');
            updateTableContent('#approved_result', data, 'approved');
        }).fail(function(xhr, status, error) {
            console.error('Error loading approved trainings:', error);
            $('#approved_result').html('<tr><td colspan="11" class="text-center">No training records found or unable to load data</td></tr>');
        });
    }
    
    function loadPendingTable(page) {
        var limit = $('#limit_pending').val();
        var search = $('#search_pending').val();
        var order_by = $('#order_by_pending').val();
        var sort_by = $('#sort_by_pending').val();
        
        $.post(pending_url, {
            limit: limit,
            page: page,
            search: search,
            order_by: order_by,
            sort_by: sort_by
        }, function(data) {
            updateTableContent('#pending_result', data, 'pending');
        }).fail(function(xhr, status, error) {
            $('#pending_result').html('<tr><td colspan="12" class="text-center">No pending training records found or unable to load data</td></tr>');
        });
    }
    
    function updateTableContent(selector, data, type) {
        $(selector).html(data);

        bindPaginationEvents(type);
    }
    }
    
    function bindPaginationEvents(type) {
        $('#' + type + ' .page_button').off('click').on('click', function(){
            var page = 1;
            var maxElement = $('#max_' + type + '_page').length > 0 ? $('#max_' + type + '_page') : $('#max_page');
            var currentElement = $('#current_' + type + '_page').length > 0 ? $('#current_' + type + '_page') : $('#cur_page');
            
            var max = parseInt(maxElement.val());
            
            if($(this).html() == 'Last') page = maxElement.val();
            else if($(this).html() == 'First') page = 1;
            else if($(this).html() == 'Next') page = parseInt(currentElement.val()) + 1;
            else if($(this).html() == 'Previous') page = parseInt(currentElement.val()) - 1;
            else page = $(this).html();
            
            if(page <= max && page != 0) {
                if(type === 'approved') {
                    loadApprovedTable(page);
                } else {
                    loadPendingTable(page);
                }
            }
        });
    }
    
    function loadApprovedTrainings() {
        loadApprovedTable(1);
    }
    
    function loadPendingTrainings() {
        loadPendingTable(1);
    }
});

function viewTraining(trainingId) {
    $.ajax({
        url: '<?php echo site_url("trainings/view/"); ?>' + trainingId,
        success: function(response) {
            $('#trainingDetailContent').html(response);
            $('#trainingDetailModal').modal('show');
        },
        error: function(xhr, status, error) {
            alert('Error loading training details: ' + error);
        }
    });
}

function viewPendingTraining(trainingId) {
    $.ajax({
        url: '<?php echo site_url("trainings/view/"); ?>' + trainingId,
        success: function(response) {
            $('#trainingDetailContent').html(response);
            $('#trainingDetailModal').modal('show');
        },
        error: function(xhr, status, error) {
            alert('Error loading training details: ' + error);
        }
    });
}

function showAddTrainingModal() {
    $('#addTrainingModal').modal('show');
}
</script>

<div class="modal fade" id="addTrainingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Training</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Add training functionality is currently under development.</p>
                <p>Please submit a service request for adding new trainings.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="<?= site_url('services/requests') ?>" class="btn btn-primary">Go to Service Requests</a>
            </div>
        </div>
    </div>
</div>
</script>
<?= $this->endSection('footer_jscript') ?>