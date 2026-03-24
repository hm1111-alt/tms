<style>
.badge {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
}
</style>

<input type="hidden" id="current_approved_page" value="<?= $page ?? 1 ?>">
<input type="hidden" id="max_approved_page" value="<?= $max_page ?? 1 ?>">
<input type="hidden" id="total_approved_records" value="<?= $event_count ?? 0 ?>">

<table class="table table-hover" id="main-approved-table">
    <thead>
        <tr>
            <th style="text-align: right; width: 5%;">#</th>
            <th style="width: 25%;">Title of Seminar/Conference/Workshop/Short Courses</th>
            <th style="width: 15%;">Employee</th>
            <th style="width: 12%;">Date From</th>
            <th style="width: 12%;">Date To</th>
            <th style="width: 8%;">Number of Hours</th>
            <th style="width: 8%;">Status</th>
            <th style="width: 15%;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($trainings)): ?>
            <?php 
            $num = isset($aa) ? $aa : 0;
            $count0 = 0;
            foreach ($trainings as $training): 
                $count0++;
                $num++;
            ?>
                <tr>
                    <td align="right"><?= $num ?></td>
                    <td><strong><?= esc($training['training_name'] ?? 'N/A') ?></strong></td>
                    <td><?= esc($training['emp_fullname'] ?? 'N/A') ?></td>
                    <td>
                        <?php 
                        if (!empty($training['training_datefrom'])) {
                            echo date('M j, Y', strtotime($training['training_datefrom']));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if (!empty($training['training_dateto'])) {
                            echo date('M j, Y', strtotime($training['training_dateto']));
                        } else {
                            echo 'N/A';
                        }
                        ?>
                    </td>
                    <td align="center"><?= esc($training['training_hours'] ?? 'N/A') ?></td>
                    <td>
                        <span class="badge bg-success">Approved</span>
                    </td>
                    <td class="td-actions">
                        <button type="button" class="btn btn-primary btn-sm" onclick="viewTraining(<?= $training['id_pending_training'] ?? 0 ?>)" title="View Details">
                            <i class="fas fa-eye"></i> View
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center" style="font-style: italic;">No training records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php 
$details = [
    'num' => isset($trainings) ? count($trainings) : 0,
    'aa' => isset($aa) ? $aa : 0,
    'event_count' => isset($event_count) ? $event_count : 0,
    'max_page' => isset($max_page) ? $max_page : 1,
    'page' => isset($page) ? $page : 1
];
?>
<?= view('layout/mytable/my_table_pagination', $details); ?>

<input type="hidden" id="limit" value="<?= isset($num_list) ? $num_list : 10 ?>">
<input type="hidden" id="search" value="<?= isset($search) ? $search : '' ?>">

<script>
function viewTraining(trainingId) {
    $.ajax({
        url: '<?= site_url("trainings/view/") ?>' + trainingId,
        success: function(response) {
            $('#trainingDetailContent').html(response);
            $('#trainingDetailModal').modal('show');
        },
        error: function(xhr, status, error) {
            alert('Error loading training details: ' + error);
        }
    });
}
</script>