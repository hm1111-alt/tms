<?php

namespace App\Models\trainings;

use CodeIgniter\Model;

class mdl_employees_trainings extends Model
{
    protected $table = 'employees_trainings';
    protected $primaryKey = 'id_employee_training';
    protected $DBGroup = 'training'; 
    protected $allowedFields = [
        'employee_training_refno',
        'employee_id',
        'emp_idno',
        'training_id',
        'training_hours',
        'training_sponsor',
        'training_award',
        'training_date_graduated',
        'training_remarks',
        'training_certificate_file',
        'emp_fullname',
        'emp_given_name',
        'emp_email',
        'emp_lname',
        'emp_sex',
        'emp_station',
        'emp_office',
        'emp_division',
        'emp_unit',
        'emp_status',
        'training_cert_sent',
        'training_cert_sentdate',
        'addeddate',
        'updated_by',
        'updated_date'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'addeddate';
    protected $updatedField = 'updated_date';

    public function getApprovedTrainingsByEmployee($employee_id)
    {
        return $this->select('*, lib_trainings.training_name as title_seminar')
                   ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                   ->where('employees_trainings.employee_id', $employee_id)
                   ->orderBy('employees_trainings.addeddate', 'DESC')
                   ->findAll();
    }

    public function getTrainingDetails($id)
    {
        return $this->select('*, lib_trainings.training_name as title_seminar')
                   ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                   ->where('id_employee_training', $id)
                   ->first();
    }
}