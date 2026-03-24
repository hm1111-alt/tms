<style>
.badge {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
}
</style>

<table class="table table-hover" id="main-requests-table">
    <thead>
        <tr>
            <th style="text-align: right; width: 5%;">#</th>
            <th style="width: 10%;">Request Number</th>
            <th style="width: 15%;">Employee Name</th>
            <th style="width: 12%;">Unit / College</th>
            <th style="width: 12%;">Date/Time Filed</th>
            <th style="width: 15%;">Document</th>
            <th style="width: 15%;">Purpose</th>
            <th style="width: 8%;">Status</th>
            <th style="width: 13%;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        log_message('debug', 'Rendering requests_table view template');
        
        $num = @$details['aa'] ?? 0;
        $count0 = 0;

        if(!empty($requests)):
            foreach($requests as $req):
                $count0++;
                $num++;
        ?>
        <tr>
            <td align="right"><?= $num ?></td>
            <td><strong><?= esc($req['req_num'] ?? 'N/A') ?></strong></td>
            <td><?= esc($req['emp_name'] ?? 'N/A') ?></td>
            <td><?= esc($req['unit_college'] ?? 'N/A') ?></td>
            <td>
                <?php 
                $date_requested = $req['date_requested'] ?? null;
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
            </td>
            <td>
                <?php 
                $doc_name = esc($req['document_name'] ?? 'Unknown Document');
                if(!empty($req['other_documents']) && strtolower($doc_name) === 'others') {
                    $doc_name .= ' - ' . esc($req['other_documents']);
                }
                echo $doc_name;
                ?>
            </td>
            <td>
                <?= esc($req['purposes'] ?? 'No purpose specified') ?>
            </td>
            <td>
                <?php 
                    $status_raw = isset($req['status']) ? $req['status'] : 'pending';
                    $status_lower = strtolower(trim($status_raw));
                    
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
                <span class="badge <?= $badge ?>"><?= ucfirst($status_raw); ?></span>
            </td>
            <td class="td-actions">
                <a href="<?= site_url('services/requests/view/'.$req['id_request'].'/'.($req['document_id'] ?? $req['id_request'])) ?>"
                   class="btn btn-primary btn-sm" title="View Document">
                    <i class="fas fa-eye"></i> View
                </a>
                <?php 
                $status_raw = isset($req['status']) ? $req['status'] : 'pending';
                $status_lower = strtolower(trim($status_raw));
                $status_lower = trim($status_lower, " \t\n\r\0\x0B");
                $is_locked = ($status_lower === 'on process' || $status_lower === 'on-process' || 
                             $status_lower === 'processing' || $status_lower === 'completed' ||
                             $status_lower === 'complete');
                
                if (!$is_locked): ?>
                    <a href="<?= site_url('services/requests/edit_document/'.$req['id_request'].'/'.($req['document_id'] ?? $req['id_request'])) ?>"
                       class="btn btn-warning btn-sm" title="Edit Document">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="<?= site_url('services/requests/delete/'.$req['id_request']) ?>"
                       class="btn btn-danger btn-sm" title="Delete Request"
                       onclick="return confirm('Are you sure you want to delete this request? This action cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete
                    </a>
                <?php else: ?>
                    <button class="btn btn-secondary btn-sm" disabled title="Cannot edit when status is <?= ucfirst($status_lower) ?>">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-secondary btn-sm" disabled title="Cannot delete when status is <?= ucfirst($status_lower) ?>">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                <?php endif; ?>
            </td>
        </tr>
        <?php 
            endforeach;
        else: 
        ?>
        <tr>
            <td colspan="9" class="text-center" style="font-style: italic;">
                No requests found.
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php 
$details['num'] = $count0;
?>
<?= view('layout/mytable/my_table_pagination', $details); ?>

<input type="hidden" id="order_by" value="<?= @$details['order_by'] ?? 'date_requested' ?>">
<input type="hidden" id="sort_by" value="<?= @$details['sort_by'] ?? 'DESC' ?>">
<input type="hidden" id="limit" value="<?= @$details['num_list'] ?? 10 ?>">
<input type="hidden" id="page" value="<?= @$details['page'] ?? 1 ?>">
<input type="hidden" id="search" value="<?= @$details['search'] ?? '' ?>">
