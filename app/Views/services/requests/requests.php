<?= $this->extend('/layout/main') ?>

<style>
.page_title_button .btn {
    width: 120px;
    height: 80px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 2px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 10px;
    margin: 5px;
    text-align: center;
    transition: all 0.2s ease-in-out;
}

.page_title_button .btn:hover {
    border-color: #0d6efd;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.page_title_button .btn i {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.page_title_button .btn div {
    font-size: 0.8rem;
    font-weight: 500;
}
</style>

<?= $this->section('header_actions') ?>

<div class="mt-4"
     data-check-user-type-url="<?= site_url('services/requests/check_user_type') ?>"
     data-update-pending-status-url="<?= site_url('services/requests/update-pending-status') ?>"
     data-document-view-url="<?= site_url('services/requests/document/') ?>"
     data-request-view-url="<?= site_url('services/requests/view/') ?>"
     data-log-document-view-url="<?= site_url('services/requests/log-document-view') ?>"
     data-csrf-name="<?= csrf_token() ?>"
     data-csrf-hash="<?= csrf_hash() ?>">

    <ul class="page_title_button" style="list-style: none; float:right; display:flex; gap:10px;">
        <li>
            <a href="<?= site_url('services/requests/requests_form'); ?>" class="btn btn-light text-success" role="button" id="add_request_btn">
                <i class="fas fa-plus-circle"></i>
                <div style="color: #999;">Add Request</div>
            </a>
        </li>
    </ul>
</div>

<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>
<div style="min-height: 50vh">
    <div class="card mb-4" style="border-top: 0px;">
        <div class="card-body">

            <div class="datatable-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="datatable-dropdown">
                        <label>
                            <?php $entry_per_page = @$num_list ?: 10; ?>
                            <select id="limit" class="datatable-selector">
                                <option value="10" <?= ($entry_per_page==10)?'selected':''; ?>>10</option>
                                <option value="25" <?= ($entry_per_page==25)?'selected':''; ?>>25</option>
                                <option value="50" <?= ($entry_per_page==50)?'selected':''; ?>>50</option>
                                <option value="100" <?= ($entry_per_page==100)?'selected':''; ?>>100</option>
                                <option value="all" <?= ($entry_per_page=='all')?'selected':''; ?>>All</option>
                            </select> entries per page
                        </label>
                    </div>

                    <div class="datatable-dropdown">
                        <label>
                            <select id="record_status" class="datatable-selector">
                                <option value="">All</option>
                                <option value="Pending">Pending</option>
                                <option value="On Process">On Process</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="datatable-search ms-auto">
                    <input name="search_event_list" id="search_event_list" class="datatable-input search <?= session()->get('requests_search')?'notempty':''; ?>" 
                        value="<?= session()->get('requests_search') ?: ''; ?>"
                        placeholder="Search requests..." type="search">
                </div>

                <input type="hidden" id="order_by" value="<?= session()->get('requests_order_by') ?: 'date_requested'; ?>">
                <input type="hidden" id="sort_by" value="<?= session()->get('requests_sort_by') ?: 'desc'; ?>">
            </div>

            <div class="datatable-container" id="load_result">
            </div>

        </div>
    </div>
</div>
<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>
<script src="<?= base_url('public/assets/js/myscript/my_table.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addRequestBtn = document.getElementById('add_request_btn');
    
    if (addRequestBtn) {
        addRequestBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            fetch('<?= site_url('services/requests/check_pending_feedback') ?>', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.has_pending) {
                    window.location.href = data.redirect_url;
                    return; 
                }
                
                let hasDraft = false;
                let draftKey = '';
                
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && key.startsWith('request_draft_')) {
                        const draftData = localStorage.getItem(key);
                        if (draftData) {
                            try {
                                const parsedData = JSON.parse(draftData);
                                if (parsedData && parsedData.length > 0) {
                                    hasDraft = true;
                                    draftKey = key;
                                    break;
                                }
                            } catch (error) {
                                localStorage.removeItem(key);
                            }
                        }
                    }
                }
                
                if (hasDraft) {
                    if (confirm('Found a saved draft. Do you want to restore it?')) {
                        sessionStorage.setItem('restore_draft_key', draftKey);
                        window.location.href = this.href;
                    } else {
                        localStorage.removeItem(draftKey);
                        window.location.href = this.href;
                    }
                } else {
                    window.location.href = this.href;
                }
            })
            .catch(error => {
                console.error('Error checking pending feedback:', error);
                window.location.href = this.href;
            });
        });
    }
});

$(document).ready(function(){
    load_table_url = '<?= site_url('services/requests/load_table_requests'); ?>';
    load_listevent('', '1');

    $('#search_event_list').on('change', function(){
        load_listevent('', '1');
    });

    $('#limit').on('change', function(){
        load_listevent('', '1');
    });

    $('#record_status').on('change', function(){
        load_listevent('', '1');
    });
});
</script>
<?= $this->endSection('footer_jscript') ?>
