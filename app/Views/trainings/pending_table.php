<style>
.badge {
    font-size: 0.85em;
    padding: 0.5em 0.75em;
}
</style>

<input type="hidden" id="current_pending_page" value="<?= $page ?? 1 ?>">
<input type="hidden" id="max_pending_page" value="<?= $max_page ?? 1 ?>">
<input type="hidden" id="total_pending_records" value="<?= $event_count ?? 0 ?>">

<table class="table table-hover" id="main-pending-table">
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
        <?php if (!empty($pending_trainings)): ?>
            <?php 
            $num = isset($aa) ? $aa : 0;
            $count0 = 0;
            foreach ($pending_trainings as $training): 
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
                        <span class="badge bg-warning text-dark">Pending</span>
                    </td>
                    <td class="td-actions">
                        <button type="button" class="btn btn-primary btn-sm" onclick="viewPendingTraining(<?= $training['id_pending_training'] ?? $training['id'] ?? $training['id_employee_training'] ?? 0 ?>)" title="View Details">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" onclick="editPendingTraining(<?= $training['id_pending_training'] ?? $training['id'] ?? $training['id_employee_training'] ?? 0 ?>)" title="Edit Training">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deletePendingTraining(<?= $training['id_pending_training'] ?? $training['id'] ?? $training['id_employee_training'] ?? 0 ?>)" title="Delete Training">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center" style="font-style: italic;">No pending training records found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php 
$details = [
    'num' => isset($pending_trainings) ? count($pending_trainings) : 0,
    'aa' => isset($aa) ? $aa : 0,
    'event_count' => isset($event_count) ? $event_count : 0,
    'max_page' => isset($max_page) ? $max_page : 1,
    'page' => isset($page) ? $page : 1
];
?>
<?= view('layout/mytable/my_table_pagination', $details); ?>

<input type="hidden" id="limit" value="<?= isset($num_list) ? $num_list : 10 ?>">
<input type="hidden" id="search" value="<?= isset($search) ? $search : '' ?>">

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
console.log('pending_table_full.js loaded - window.loadPendingTrainingsGlobal exists:', typeof window.loadPendingTrainingsGlobal);

function viewPendingTraining(trainingId) {
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

function editPendingTraining(trainingId) {
    $.ajax({
        url: '<?= site_url("trainings/get_pending_details/") ?>' + trainingId,
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const training = response.data;
                
                $('#edit_training_id').val(training.id_pending_training || training.id);
                $('#edit_training_name').val(training.training_name || '');
                $('#edit_training_datefrom').val(training.training_datefrom || '');
                $('#edit_training_dateto').val(training.training_dateto || '');
                $('#edit_training_hours').val(training.training_hours || '');
                $('#edit_training_category').val(training.training_category_id || '');
                $('#edit_training_venue').val(training.training_venue || '');
                $('#edit_training_facilitator').val(training.training_facilitator || '');
                $('#edit_training_sponsor').val(training.training_sponsor || '');
                
                if (training.training_certificate_file && training.training_certificate_file !== '') {
                    $('#current_certificate_name').text(training.training_certificate_file);
                    $('#current_certificate_link').attr('href', '<?= base_url("uploads/trainings/certificates/") ?>' + training.training_certificate_file);
                    $('#current_certificate_container').show();
                } else {
                    $('#current_certificate_container').hide();
                }
                
                $('#editTrainingModal').modal('show');
            } else {
                alert('Error loading training details: ' + response.message);
            }
        },
        error: function(xhr, status, error) {
            alert('Error loading training details: ' + error);
        }
    });
}

function deletePendingTraining(trainingId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            var currentPage = parseInt($('#current_pending_page').val()) || 1;
            
            $.ajax({
                url: '<?= site_url("trainings/delete_pending/") ?>' + trainingId,
                method: 'POST',
                data: { page: currentPage },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        console.log('Delete successful, reloading table...');
                        console.log('window.loadPendingTrainingsGlobal exists:', typeof window.loadPendingTrainingsGlobal);
                        
                        if (typeof window.loadPendingTrainingsGlobal === 'function') {
                            console.log('Calling loadPendingTrainingsGlobal()');
                            window.loadPendingTrainingsGlobal();
                        } else {
                            console.warn('loadPendingTrainingsGlobal not available, forcing tab reload');
                            $('#pending-tab').trigger('click');
                        }
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'Pending training has been deleted.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to delete training'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error deleting training: ' + error
                    });
                }
            });
        }
    });
}
</script>