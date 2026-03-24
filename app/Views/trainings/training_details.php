<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <table class="table table-borderless">
                <tr>
                    <th width="40%">Title:</th>
                    <td><?= esc($training['training_name'] ?? $training['title'] ?? 'N/A') ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <th width="40%">Employee:</th>
                    <td>
                        <?php 
                        $emp_name = $training['emp_fullname'] ?? $training['employee_name'] ?? '';
                        if (empty($emp_name) && !empty($training['emp_idno'])) {
                            $emp_name = 'Employee ID: ' . $training['emp_idno'];
                        } elseif (empty($emp_name)) {
                            $emp_name = 'N/A';
                        }
                        echo esc($emp_name);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Date From:</th>
                    <td><?= !empty($training['training_datefrom']) ? date('M j, Y', strtotime($training['training_datefrom'])) : 
                          (!empty($training['date_from']) ? date('M j, Y', strtotime($training['date_from'])) : 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Date To:</th>
                    <td><?= !empty($training['training_dateto']) ? date('M j, Y', strtotime($training['training_dateto'])) : 
                          (!empty($training['date_to']) ? date('M j, Y', strtotime($training['date_to'])) : 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Duration:</th>
                    <td><strong><?= esc($training['training_hours'] ?? $training['hours'] ?? 'N/A') ?></strong> hours</td>
                </tr>
                <tr>
                    <th>Type:</th>
                    <td><span class="badge bg-info"><?= esc($training_type) ?></span></td>
                </tr>
            </table>
        </div>
        
        <div class="col-md-6">
            <table class="table table-borderless">
                <tr>
                    <th width="40%">Venue:</th>
                    <td><?= esc($training['training_venue'] ?? $training['venue'] ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Facilitator:</th>
                    <td><?= esc($training['training_facilitator'] ?? $training['facilitator'] ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        <?php if (isset($training['is_pending']) && $training['is_pending']): ?>
                            <span class="badge bg-warning">Pending</span>
                        <?php else: ?>
                            <span class="badge bg-success">Approved</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Certificate:</th>
                    <td>
                        <?php if (!empty($training['training_certificate_file']) || !empty($training['certificate_file'])): ?>
                            <a href="<?= base_url('uploads/trainings/certificates/' . esc($training['training_certificate_file'] ?? $training['certificate_file'])) ?>" target="_blank" class="btn btn-sm btn-success">
                                View Certificate
                            </a>
                        <?php else: ?>
                            <span class="badge bg-secondary">Not Available</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Date Added:</th>
                    <td><small class="text-muted"><?= !empty($training['added_date']) ? date('M j, Y g:i A', strtotime($training['added_date'])) : 
                       (!empty($training['created_at']) ? date('M j, Y g:i A', strtotime($training['created_at'])) : 'N/A') ?></small>
                    </td>
                </tr>
            </table>
        </div>
    </div>