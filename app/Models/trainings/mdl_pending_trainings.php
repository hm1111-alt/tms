<?php

namespace App\Models\trainings;

use CodeIgniter\Model;

class mdl_pending_trainings extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'pending_trainings';
    protected $primaryKey = 'id_pending_training';
    protected $allowedFields = [
        'emp_idno',
        'employee_id',
        'emp_fullname',
        'emp_lname',
        'station_id',
        'training_hours',
        'training_remarks',
        'training_certificate_file',
        'training_name',
        'training_type_id',
        'training_category_id',
        'training_datefrom',
        'training_dateto',
        'training_facilitator',
        'training_venue',
        'employee_training_id',
        'training_id',
        'is_approved',
        'is_disapproved',
        'approve_remarks',
        'added_by',
        'added_date',
        'updated_by',
        'updated_date'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'added_date';
    protected $updatedField = 'updated_date';

    public function getPendingTrainingsByEmployee($employee_id)
    {
        return $this->where('employee_id', $employee_id)
                   ->where('is_approved IS NULL')
                   ->orderBy('added_date', 'DESC')
                   ->findAll();
    }

    public function getApprovedTrainingsByEmployee($employee_id)
    {
        return $this->where('employee_id', $employee_id)
                   ->where('is_approved', 1)
                   ->orderBy('added_date', 'DESC')
                   ->findAll();
    }
}