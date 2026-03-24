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
            <?php if(isset($request['id_request']) && isset($document['rd_id'])): ?>
                <a href="<?= site_url('services/requests/view_pdf/'.$request['id_request'].'/'.$document['rd_id']) ?>"
                   class="btn btn-light"
                   target="_blank"
                   title="View document as PDF">
                    <i class="fas fa-file-pdf"></i>
                    <div class="text-muted">View as PDF</div>
                </a>
            <?php else: ?>
                <button class="btn btn-light" 
                        onclick="alert('No PDF available for this document'); return false;"
                        title="PDF not available">
                    <i class="fas fa-file-pdf"></i>
                    <div class="text-muted">View as PDF</div>
                </button>
            <?php endif; ?>
        </li>

        <li>
            <a href="<?= site_url('services/requests') ?>"
               class="btn btn-light">
                <i class="fas fa-arrow-circle-left"></i>
                <div class="text-muted">Back</div>
            </a>
        </li>

    </ul>
</div>

<?= $this->endSection('header_actions') ?>

<?= $this->section('content') ?>
<div style="min-height:50vh">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="mt-2 mb-4">Request Details</h2>
                    <div class="form-horizontal tasi-form">
                        <div class="form-group mb-2 px-1">
                            <label><b>Employee Name</b></label>
                            <div class="px-3 fw-bold"><?= esc($request['emp_name'] ?? '---') ?></div>
                        </div>
                        <div class="form-group mb-2 px-1">
                            <label><b>Unit / College</b></label>
                            <div class="px-3 fw-bold"><?= esc($request['unit_college'] ?? '---') ?></div>
                        </div>
                        <div class="form-group mb-2 px-1">
                            <label><b>Date Filed</b></label>
                            <div class="px-3 fw-bold">
                                <?php 
                                $date_requested = $request['date_requested'] ?? null;
                                if (!empty($date_requested)) {
                                    $timestamp = strtotime($date_requested);
                                    if ($timestamp !== false) {
                                        echo date('F j, Y g:i A', $timestamp);
                                    } else {
                                        echo 'Invalid Date';
                                    }
                                } else {
                                    echo '---';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mt-2 mb-3">Document Details</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>Document</th>
                            <td>
                                <?= esc($document['document_name'] ?? 'Unknown') ?>
                                <?php if(!empty($document['other_documents'])): ?>
                                    - <?= esc($document['other_documents']) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Purpose(s)</th>
                            <td>
                                <?php if(!empty($purposes)): ?>
                                    <?php foreach($purposes as $p): ?>
                                        <div class="mb-1">
                                            <?= (!empty($p['purpose_group_name']) ? esc($p['purpose_group_name']).': ' : '') . esc($p['purpose_name']) ?>
                                            <?= !empty($p['purpose_details']) ? ' - ' . esc($p['purpose_details']) : '' ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">No purpose recorded</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php
                                $status = $document['status'] ?? 'pending';
                                $status_lower = strtolower(trim($status));
                                $document_id = $document['rd_id'] ?? 'unknown';
                                
                                switch($status_lower) {
                                    case 'pending':
                                        $badge = 'bg-secondary';
                                        break;
                                    case 'on process':
                                    case 'on-process':
                                    case 'processing':
                                        $badge = 'bg-warning text-dark'; 
                                        break;
                                    case 'completed':
                                        $badge = 'bg-success';
                                        break;
                                    default:
                                        $badge = 'bg-secondary'; 
                                        break;
                                }
                                ?>
                                <span class="badge <?= $badge ?>" id="document-status-<?= $document_id ?>"><?= ucfirst($status) ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th>Remarks</th>
                            <td><?= esc($document['remarks'] ?? '---') ?></td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td>
                                <?php if(!empty($document['file_path'])): ?>
                                    <a href="<?= base_url('services/requests/view_file/' . $document['file_path']) ?>" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View File
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">No file</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Feedback</th>
                            <td>
                                <?php
                                $status = strtolower(trim($document['status'] ?? 'pending'));
                                $is_completed = ($status === 'completed');
                                $document_id = $document['rd_id'] ?? 0;
                                $request_id = $request['id_request'] ?? 0;
                                ?>
                                <?php if($is_completed && $feedback_submitted): ?>
                                    <a href="<?= site_url('feedback/view_feedback_pdf/'.$request_id.'/'.$document_id) ?>" class="btn btn-success btn-sm" target="_blank">
                                        <i class="fas fa-file-pdf"></i> View Feedback
                                    </a>
                                <?php elseif($is_completed && !$feedback_submitted): ?>
                                    <a href="<?= site_url('feedback/submit/'.$request_id.'/'.$document_id) ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-comment"></i> Give Feedback
                                    </a>
                                    <span class="text-muted d-block mt-2">No feedback submitted yet</span>
                                <?php else: ?>
                                    <span class="text-muted">Feedback available after document completion</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        

    </div>
</div>

<?= $this->endSection('content') ?>
