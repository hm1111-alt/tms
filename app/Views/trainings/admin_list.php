<?= $this->extend('layout/dashboard_admin') ?>

<?= $this->section('content') ?>

<style>
    /* Modern Tab Design */
    .modern-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        padding: 0;
        margin-bottom: 1.5rem;
    }
    
    .modern-tab {
        padding: 0.75rem 1.5rem;
        border: 2px solid #e3e6f0;
        border-radius: 0.5rem;
        background: white;
        color: #6c757d;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-tab:hover {
        border-color: #198754;
        color: #198754;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(25, 135, 84, 0.15);
    }
    
    .modern-tab.active {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        border-color: #198754;
        color: white;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }
    
    .modern-tab .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Modern Table Design */
    .modern-table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        overflow: hidden;
    }
    
    .modern-table {
        margin-bottom: 0;
    }
    
    .modern-table thead {
        background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
        border-bottom: 2px solid #198754;
    }
    
    .modern-table thead th {
        padding: 1rem 0.75rem;
        font-weight: 600;
        color: #2c3e50;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
        white-space: nowrap;
    }
    
    .modern-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .modern-table tbody tr:hover {
        background-color: #f8f9fc;
        transform: scale(1.005);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .modern-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border: none;
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-block;
    }
    
    .status-upcoming {
        background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
        color: white;
    }
    
    .status-open {
        background: linear-gradient(135deg, #198754 0%, #146c43 100%);
        color: white;
    }
    
    .status-ongoing {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
    }
    
    .status-completed {
        background: linear-gradient(135deg, #6c757d 0%, #565e64 100%);
        color: white;
    }
    
    .status-closed {
        background: linear-gradient(135deg, #ffc107 0%, #cc9a06 100%);
        color: #000;
    }
    
    .action-btn {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-section .form-control,
    .filter-section .form-select {
        border: 2px solid #e3e6f0;
        border-radius: 0.375rem;
        padding: 0.625rem 1rem;
        transition: all 0.2s ease;
    }
    
    .filter-section .form-control:focus,
    .filter-section .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }
    
    .filter-section label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Ensure table doesn't stretch card wider than container */
    #trainingsTable {
        width: 100% !important;
        max-width: 100%;
    }
    
    /* Ensure table cells don't stretch unnecessarily */
    #trainingsTable th,
    #trainingsTable td {
        white-space: nowrap;
    }
    
    /* Make training name column flexible */
    #trainingsTable th:nth-child(2),
    #trainingsTable td:nth-child(2) {
        white-space: normal;
        min-width: 200px;
        max-width: 300px;
    }
</style>

<!-- Removed custom navbar CSS - using default from dashboard_admin layout -->

<div class="row" style="background-color: #FFF; border-radius: 0.375rem; padding: 2rem 1.5rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); margin-top: 1rem; margin-bottom: 1rem;">
    <div class="col-xl-8">
        <h1 class="mt-2 mb-3">Manage Trainings</h1>
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Trainings</li>
        </ol>
    </div>
    <div class="col-xl-4">
        <div class="text-end mt-4">
            <ul class="page_title_button" style="list-style: none; margin: 0;">
                <li>
                    <a href="<?= site_url('trainings/add') ?>" class="btn btn-light">
                        <i class="fas fa-plus-circle"></i>
                        <div class="text-muted">Add Training</div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

                    <?= $this->include('layout/messages') ?>
                    
                    <!-- Modern Filter Tabs -->
                    <div class="modern-tabs" id="trainingTabs">
                        <button class="modern-tab active" data-filter="all">
                            <i class="fas fa-list"></i> All Trainings
                            <span class="badge bg-light text-dark"><?= count($trainings) ?></span>
                        </button>
                        <button class="modern-tab" data-filter="upcoming">
                            <i class="fas fa-calendar"></i> Upcoming
                            <span class="badge bg-light text-dark" id="upcoming-count">0</span>
                        </button>
                        <button class="modern-tab" data-filter="open">
                            <i class="fas fa-door-open"></i> Open
                            <span class="badge bg-light text-dark" id="open-count">0</span>
                        </button>
                        <button class="modern-tab" data-filter="ongoing">
                            <i class="fas fa-play-circle"></i> Ongoing
                            <span class="badge bg-light text-dark" id="ongoing-count">0</span>
                        </button>
                        <button class="modern-tab" data-filter="completed">
                            <i class="fas fa-check-circle"></i> Completed
                            <span class="badge bg-light text-dark" id="completed-count">0</span>
                        </button>
                    </div>
                    
                    <!-- Search and Category Filter -->
                    <div class="filter-section">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="searchInput">
                                    <i class="fas fa-search text-success"></i> Search Trainings
                                </label>
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="Search by training name, facilitator, or venue...">
                            </div>
                            <div class="col-md-6">
                                <label for="filterCategory">
                                    <i class="fas fa-tags text-success"></i> Filter by Category
                                </label>
                                <select class="form-select" id="filterCategory">
                                    <option value="">All Categories</option>
                                    <?php 
                                        $categories = [];
                                        foreach($trainings as $training) {
                                            if(!empty($training['training_category_name'])) {
                                                $categories[] = $training['training_category_name'];
                                            }
                                        }
                                        $categories = array_unique($categories);
                                        sort($categories);
                                        foreach($categories as $cat):
                                    ?>
                                        <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 d-flex justify-content-between align-items-center">
                                <button class="btn btn-sm btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-redo"></i> Clear Filters
                                </button>
                                <span class="text-muted" id="resultCount">
                                    <i class="fas fa-info-circle"></i> Showing <strong><?= count($trainings) ?></strong> trainings
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modern Training Table -->
                    <div class="modern-table-container">
                        <div class="datatable-top">
                            <div class="datatable-dropdown">
                                <label>
                                    <select id="limit" class="datatable-selector">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="all">All</option>
                                    </select> entries per page
                                </label>
                            </div>
                        </div>
                        
                        <div class="datatable-container">
                            <div class="table-responsive">
                                <table class="table modern-table" id="trainingsTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;" class="text-center">#</th>
                                            <th>Training Name</th>
                                            <th>Category</th>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Status</th>
                                            <th style="width: 150px;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="training_result">
                                        <?php 
                                        $counter = 1;
                                        foreach($trainings as $training): 
                                            $status = strtolower($training['status_name'] ?? '');
                                            $status_class = 'status-' . $status;
                                        ?>
                                            <tr class="training-row" 
                                                data-status="<?= esc($status) ?>"
                                                data-category="<?= esc(strtolower($training['training_category_name'] ?? '')) ?>"
                                                data-name="<?= esc(strtolower($training['training_name'])) ?>"
                                                data-facilitator="<?= esc(strtolower($training['training_facilitator'] ?? '')) ?>"
                                                data-venue="<?= esc(strtolower($training['training_venue'] ?? '')) ?>">
                                                <td class="text-center"><strong><?= $counter++ ?></strong></td>
                                                <td>
                                                    <strong class="text-dark"><?= esc($training['training_name']) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        <?= esc($training['training_category_name'] ?? 'N/A') ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar-day text-muted me-1"></i>
                                                    <?= date('M d, Y', strtotime($training['training_datefrom'])) ?>
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar-check text-muted me-1"></i>
                                                    <?= date('M d, Y', strtotime($training['training_dateto'])) ?>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?= esc($status_class) ?>">
                                                        <?= esc(ucfirst($training['status_name'] ?? 'N/A')) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        <a href="<?= site_url('trainings/view/' . $training['id_training']) ?>" 
                                                           class="btn btn-sm btn-outline-primary action-btn" 
                                                           title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?= site_url('trainings/edit/' . $training['id_training']) ?>" 
                                                           class="btn btn-sm btn-outline-warning action-btn" 
                                                           title="Edit Training">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger action-btn delete-training" 
                                                                data-training-id="<?= $training['id_training'] ?>"
                                                                data-training-name="<?= esc($training['training_name']) ?>"
                                                                title="Delete Training">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <?php 
                        $pagination_details = [
                            'aa' => 0,
                            'num' => count($trainings),
                            'event_count' => count($trainings),
                            'max_page' => 1,
                            'page' => 1
                        ];
                        // Don't load my_table_pagination.js, we'll handle it ourselves
                        ?>
                        
                        <div class="datatable-bottom">
                            <div class="datatable-info" id="pagination-info">
                                Showing 1 to 10 of <?= count($trainings) ?> entries
                            </div>
                            
                            <input type="hidden" id="max_page" value="1">
                            <input type="hidden" id="cur_page" value="1">
                            
                            <nav class="datatable-pagination">
                                <ul class="datatable-pagination-list" id="pagination-list">
                                    <!-- Pagination will be generated by JavaScript -->
                                </ul>
                            </nav>
                        </div>
                    </div>
            </main>
        </div>
    </div>
    
    <?= $this->section('scripts') ?>
    
    <!-- Add Multiple Sessions Modal -->
    <div class="modal fade" id="addMultipleSessionsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Multiple Sessions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal-training-id">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Add multiple sessions for this training. Click "Add Row" to add more sessions.
                    </div>
                    
                    <div id="sessions-container">
                        <div class="session-row mb-3 p-3 border rounded">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label class="form-label small">Date</label>
                                    <input type="date" class="form-control form-control-sm session-date" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">Start Time</label>
                                    <input type="time" class="form-control form-control-sm start-time" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">End Time</label>
                                    <input type="time" class="form-control form-control-sm end-time" required>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-danger remove-session-row w-100">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-session-row">
                        <i class="fas fa-plus"></i> Add Another Row
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-multiple-sessions">
                        <i class="fas fa-save"></i> Save All Sessions
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Load SweetAlert2 from CDN
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(script);
        
        $(document).ready(function() {
            // Store current active tab
            let currentTab = 'all';
            
            // Count trainings by status
            function countByStatus() {
                const counts = {
                    upcoming: 0,
                    open: 0,
                    ongoing: 0,
                    completed: 0
                };
                
                $('.training-row').each(function() {
                    const status = $(this).data('status').toLowerCase();
                    
                    if (status === 'upcoming') {
                        counts.upcoming++;
                    } else if (status === 'open' || status === 'active') {
                        counts.open++;
                    } else if (status === 'ongoing') {
                        counts.ongoing++;
                    } else if (status === 'completed') {
                        counts.completed++;
                    }
                });
                
                // Update badge counts
                $('#upcoming-count').text(counts.upcoming);
                $('#open-count').text(counts.open);
                $('#ongoing-count').text(counts.ongoing);
                $('#completed-count').text(counts.completed);
            }
            
            // Filter function - combines status tab, search, and category filter
            function filterTrainings() {
                const searchTerm = $('#searchInput').val().toLowerCase();
                const categoryFilter = $('#filterCategory').val().toLowerCase();
                let visibleCount = 0;
                
                $('.training-row').each(function() {
                    const row = $(this);
                    const status = row.data('status');
                    const category = row.data('category');
                    const name = row.data('name');
                    const facilitator = row.data('facilitator');
                    const venue = row.data('venue');
                    
                    // Check if row matches current tab status filter
                    let statusMatch = true;
                    if (currentTab === 'upcoming' && status !== 'upcoming') {
                        statusMatch = false;
                    } else if (currentTab === 'open' && status !== 'open' && status !== 'active') {
                        statusMatch = false;
                    } else if (currentTab === 'ongoing' && status !== 'ongoing') {
                        statusMatch = false;
                    } else if (currentTab === 'completed' && status !== 'completed') {
                        statusMatch = false;
                    }
                    
                    // Check category filter
                    const categoryMatch = categoryFilter === '' || category.includes(categoryFilter);
                    
                    // Check search term (searches in name, facilitator, and venue)
                    const searchMatch = searchTerm === '' || 
                                       name.includes(searchTerm) ||
                                       facilitator.includes(searchTerm) ||
                                       venue.includes(searchTerm);
                    
                    // Show/hide based on all filters
                    if (statusMatch && categoryMatch && searchMatch) {
                        row.show();
                        visibleCount++;
                    } else {
                        row.hide();
                    }
                });
                
                // Update result count
                $('#resultCount').html(`<i class="fas fa-info-circle"></i> Showing <strong>${visibleCount}</strong> trainings`);
            }
            
            // Initialize counts
            countByStatus();
            
            // Modern Tab click handlers
            $('.modern-tab').on('click', function() {
                // Remove active class from all tabs
                $('.modern-tab').removeClass('active');
                // Add active class to clicked tab
                $(this).addClass('active');
                // Update current tab
                currentTab = $(this).data('filter');
                // Apply filters
                filterTrainings();
            });
            
            // Search input handler
            $('#searchInput').on('input', filterTrainings);
            
            // Category filter handler
            $('#filterCategory').on('change', filterTrainings);
            
            // Clear filters function
            window.clearFilters = function() {
                $('#searchInput').val('');
                $('#filterCategory').val('');
                currentTab = 'all';
                $('.modern-tab').removeClass('active');
                $('.modern-tab[data-filter="all"]').addClass('active');
                filterTrainings();
            };
            
            // Toggle registration open/close
            $(document).on('click', '.toggle-registration', function() {
                const btn = $(this);
                const trainingId = btn.data('training-id');
                const isOpen = btn.data('is-open') == 1;
                const action = isOpen ? 'close' : 'open';
                
                // Confirm action
                Swal.fire({
                    title: 'Confirm ' + (isOpen ? 'Close' : 'Open') + ' Registration?',
                    html: `Are you sure you want to ${action} registration for this training?<br><br>
                           <small>This will ${isOpen ? 'prevent' : 'allow'} users from enrolling.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, ' + (isOpen ? 'Close' : 'Open') + ' it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Updating...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // AJAX request to update registration status
                        $.ajax({
                            url: '<?= site_url('trainings/toggle_registration') ?>',
                            type: 'POST',
                            data: {
                                training_id: trainingId,
                                action: action
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Registration has been ' + (isOpen ? 'closed' : 'opened') + '.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Update button appearance and row status based on response
                                        const newStatus = response.new_status;
                                        const row = btn.closest('tr');
                                        
                                        // Update status badge in the table
                                        if (newStatus === 'open') {
                                            row.find('td:nth-child(5) .badge').removeClass('bg-secondary').addClass('bg-success').text('open');
                                        } else if (newStatus === 'closed') {
                                            row.find('td:nth-child(5) .badge').removeClass('bg-success').addClass('bg-secondary').text('closed');
                                        }
                                        
                                        // Update or remove toggle button based on new status
                                        if (newStatus === 'open') {
                                            // Change to "Close Registration" button
                                            btn.removeClass('btn-outline-danger').addClass('btn-success');
                                            btn.find('i').removeClass('fa-lock').addClass('fa-lock-open');
                                            btn.find('span').text(' Close');
                                            btn.attr('title', 'Close Registration');
                                            btn.data('is-open', 1);
                                        } else if (newStatus === 'closed') {
                                            // Change to "Open Registration" button
                                            btn.removeClass('btn-success').addClass('btn-outline-danger');
                                            btn.find('i').removeClass('fa-lock-open').addClass('fa-lock');
                                            btn.find('span').text(' Open');
                                            btn.attr('title', 'Open Registration');
                                            btn.data('is-open', 0);
                                        }
                                        
                                        // Refresh filters to update tab counts
                                        filterTrainings();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to update registration status.'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Start training - change status to ongoing
            $(document).on('click', '.start-training', function() {
                const btn = $(this);
                const trainingId = btn.data('training-id');
                
                // Confirm action
                Swal.fire({
                    title: 'Start Training?',
                    html: `Are you sure you want to start this training?<br><br>
                           <small>This will change the status to <strong>Ongoing</strong>.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, start it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Starting...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // AJAX request to update training status to ongoing
                        $.ajax({
                            url: '<?= site_url('trainings/start_training') ?>',
                            type: 'POST',
                            data: {
                                training_id: trainingId
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: 'Training is now ongoing.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Update status badge to ongoing
                                        const row = btn.closest('tr');
                                        
                                        // Update data-status attribute for filtering
                                        row.attr('data-status', 'ongoing');
                                        
                                        // Update status badge
                                        row.find('td:nth-child(5) .badge')
                                            .removeClass('bg-success')
                                            .addClass('bg-warning')
                                            .text('ongoing');
                                        
                                        // Remove the start button since training is now ongoing
                                        btn.remove();
                                        
                                        // Hide the row if we're on Open tab (move it to Ongoing tab)
                                        if (currentTab === 'open') {
                                            row.hide();
                                        } else if (currentTab === 'ongoing') {
                                            row.show();
                                        }
                                        
                                        // Recount and update badges
                                        countByStatus();
                                        
                                        // Update result count
                                        filterTrainings();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to start training.'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Manage sessions button - redirect to sessions management page
            $(document).on('click', '.manage-sessions', function() {
                const trainingId = $(this).data('training-id');
                window.location.href = '<?= site_url('trainings/sessions/') ?>' + trainingId;
            });
            
            // Delete training button
            $(document).on('click', '.delete-training', function() {
                const btn = $(this);
                const trainingId = btn.data('training-id');
                const trainingName = btn.data('training-name');
                
                // Confirm delete action
                Swal.fire({
                    title: 'Delete Training?',
                    html: '<p>Are you sure you want to delete <strong>' + trainingName + '</strong>?</p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fas fa-trash"></i> Yes, Delete It!',
                    cancelButtonText: '<i class="fas fa-times"></i> Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the training',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // AJAX request to delete training
                        $.ajax({
                            url: '<?= site_url('trainings/delete/') ?>' + trainingId,
                            type: 'POST',
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message || 'Training has been deleted successfully.',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Remove the row from the table
                                        btn.closest('tr').remove();
                                        
                                        // Refresh counts and filters
                                        countByStatus();
                                        filterTrainings();
                                        initializePagination();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Failed to delete training.'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
            
            // Add multiple sessions button
            $(document).on('click', '.add-multiple-sessions', function() {
                const trainingId = $(this).data('training-id');
                const trainingName = $(this).data('training-name');
                
                $('#modal-training-id').val(trainingId);
                $('#addMultipleSessionsModal .modal-title').html('<i class="fas fa-plus-circle me-2"></i>Add Sessions - ' + trainingName);
                $('#addMultipleSessionsModal').modal('show');
            });
            
            // Add another session row
            $('#add-session-row').on('click', function() {
                const newRow = `
                    <div class="session-row mb-3 p-3 border rounded">
                        <div class="row g-2">
                            <div class="col-md-3">
                                <label class="form-label small">Date</label>
                                <input type="date" class="form-control form-control-sm session-date" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">Start Time</label>
                                <input type="time" class="form-control form-control-sm start-time" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small">End Time</label>
                                <input type="time" class="form-control form-control-sm end-time" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-danger remove-session-row w-100">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#sessions-container').append(newRow);
            });
            
            // Remove session row
            $(document).on('click', '.remove-session-row', function() {
                if ($('.session-row').length > 1) {
                    $(this).closest('.session-row').remove();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Remove',
                        text: 'You must have at least one session.'
                    });
                }
            });
            
            // Save multiple sessions
            $('#save-multiple-sessions').on('click', function() {
                const trainingId = $('#modal-training-id').val();
                const sessions = [];
                let hasError = false;
                
                $('.session-row').each(function() {
                    const date = $(this).find('.session-date').val();
                    const startTime = $(this).find('.start-time').val();
                    const endTime = $(this).find('.end-time').val();
                    
                    if (!date || !startTime || !endTime) {
                        hasError = true;
                        return false; // break the loop
                    }
                    
                    sessions.push({
                        session_date: date,
                        start_time: startTime,
                        end_time: endTime
                    });
                });
                
                if (hasError) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please fill in all fields for each session.'
                    });
                    return;
                }
                
                if (sessions.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Sessions',
                        text: 'Please add at least one session.'
                    });
                    return;
                }
                
                // Confirm save
                Swal.fire({
                    title: 'Save ' + sessions.length + ' Session(s)?',
                    html: `You are about to create <strong>${sessions.length}</strong> session(s) for this training.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, save them!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Saving...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        $.ajax({
                            url: '<?= site_url('trainings/save_multiple_sessions') ?>',
                            type: 'POST',
                            data: {
                                training_id: trainingId,
                                sessions: JSON.stringify(sessions)
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        $('#addMultipleSessionsModal').modal('hide');
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'An error occurred. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
            
        });
    
    // Handle tab color switching
    $(document).ready(function() {
        const activeColor = '#1cc88a'; // Green for active tab
        const inactiveColor = '#6c757d'; // Gray for inactive tabs
        
        // Set initial colors based on active tab
        $('.nav-link.active').css('color', activeColor);
        
        // Handle tab click
        $('button[data-bs-toggle="tab"]').on('click', function() {
            // Reset all tabs to gray and remove border
            $('.nav-link').css('color', inactiveColor).css('border-bottom', 'none');
            
            // Set clicked tab to green with border
            $(this).css('color', activeColor).css('border-bottom', '3px solid ' + activeColor);
        });
        
        // Fix navbar dropdown - ensure Bootstrap dropdowns are initialized
        $(document).ready(function() {
            // Initialize all dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });
        
        // Pagination functionality
        let currentPage = 1;
        let entriesPerPage = 10;
        let allRows = [];
        
        function initializePagination() {
            allRows = $('.training-row').toArray();
            updateTableDisplay();
        }
        
        function updateTableDisplay() {
            const totalRows = allRows.length;
            const totalPages = entriesPerPage === 'all' ? 1 : Math.ceil(totalRows / entriesPerPage);
            
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;
            
            // Hide all rows first
            $('.training-row').hide();
            
            // Show only rows for current page
            if (entriesPerPage === 'all') {
                $('.training-row').show();
                $('#pagination-info').text(`Showing 1 to ${totalRows} of ${totalRows} entries`);
            } else {
                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = Math.min(startIndex + entriesPerPage, totalRows);
                
                for (let i = startIndex; i < endIndex; i++) {
                    $(allRows[i]).show();
                }
                
                $('#pagination-info').text(`Showing ${startIndex + 1} to ${endIndex} of ${totalRows} entries`);
            }
            
            // Update hidden inputs
            $('#max_page').val(totalPages);
            $('#cur_page').val(currentPage);
            
            // Generate pagination buttons
            generatePaginationButtons(totalPages);
        }
        
        function generatePaginationButtons(totalPages) {
            let html = '';
            
            // First button
            html += `<li class="datatable-pagination-list-item ${currentPage === 1 ? 'datatable-disabled' : ''}">
                        <a href="#" data-action="first" class="datatable-pagination-list-item-link page_button">First</a>
                     </li>`;
            
            // Previous button
            html += `<li class="datatable-pagination-list-item ${currentPage === 1 ? 'datatable-disabled' : ''}">
                        <a href="#" data-action="prev" class="datatable-pagination-list-item-link page_button">Previous</a>
                     </li>`;
            
            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);
            
            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="datatable-pagination-list-item ${currentPage === i ? 'datatable-active' : ''}">
                            <a href="#" data-page="${i}" class="datatable-pagination-list-item-link page_button">${i}</a>
                         </li>`;
            }
            
            // Next button
            html += `<li class="datatable-pagination-list-item ${currentPage === totalPages ? 'datatable-disabled' : ''}">
                        <a href="#" data-action="next" class="datatable-pagination-list-item-link page_button">Next</a>
                     </li>`;
            
            // Last button
            html += `<li class="datatable-pagination-list-item ${currentPage === totalPages ? 'datatable-disabled' : ''}">
                        <a href="#" data-action="last" class="datatable-pagination-list-item-link page_button">Last</a>
                     </li>`;
            
            $('#pagination-list').html(html);
            
            // Attach click handlers
            $('.page_button').off('click').on('click', function(e) {
                e.preventDefault();
                const action = $(this).data('action');
                const pageNum = parseInt($(this).data('page'));
                
                if (action === 'first') {
                    currentPage = 1;
                } else if (action === 'prev') {
                    currentPage = Math.max(1, currentPage - 1);
                } else if (action === 'next') {
                    currentPage = Math.min(totalPages, currentPage + 1);
                } else if (action === 'last') {
                    currentPage = totalPages;
                } else if (!isNaN(pageNum)) {
                    currentPage = pageNum;
                }
                
                updateTableDisplay();
            });
        }
        
        // Handle entries per page change
        $('#limit').off('change').on('change', function() {
            const value = $(this).val();
            entriesPerPage = value === 'all' ? 'all' : parseInt(value);
            currentPage = 1;
            updateTableDisplay();
        });
        
        // Initialize pagination on page load
        $(document).ready(function() {
            setTimeout(function() {
                initializePagination();
            }, 100);
        });
    });
    </script>
    
    <?= $this->endSection() ?>
    
<?= $this->endSection() ?>

