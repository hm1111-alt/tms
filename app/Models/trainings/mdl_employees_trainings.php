<?php

namespace App\Models\trainings;

use CodeIgniter\Model;
use Config\Database;

class mdl_employees_trainings extends Model
{
    protected $table = 'employees_trainings';
    protected $primaryKey = 'id_employee_training';
    protected $DBGroup = 'default'; 
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
        'updated_date',
        'has_feedback'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;

    protected $validationRules = [
        'employee_id' => 'required|numeric',
        'emp_idno' => 'required|min_length[3]|max_length[50]',
        'training_id' => 'required|numeric',
        'training_hours' => 'permit_empty|numeric',
        'training_date_graduated' => 'permit_empty|valid_date',
        'training_cert_sent' => 'permit_empty|in_list[0,1]',
        'has_feedback' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'employee_id' => [
            'required' => 'Employee ID is required'
        ],
        'emp_idno' => [
            'required' => 'Employee number is required'
        ],
        'training_id' => [
            'required' => 'Training is required'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getTrainingDetails($id)
    {
        try {
            return $this->select('employees_trainings.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                ->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training details: ' . $e->getMessage());
            return null;
        }
    }


    public function getApprovedTrainingsByEmployee($employeeId)
    {
        try {
            return $this->select('employees_trainings.*, lib_trainings.training_name, lib_trainings.training_datefrom, lib_trainings.training_dateto')
                ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                ->where('employees_trainings.employee_id', $employeeId)
                ->orderBy('employees_trainings.addeddate', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching employee trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function getAllEmployeeTrainings($filters = [])
    {
        try {
            $query = $this->select('employees_trainings.*, 
                                        lib_trainings.training_name,
                                        lib_trainings.training_datefrom,
                                        lib_trainings.training_dateto,
                                        lib_training_category.training_category_name')
                ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                ->join('lib_training_category', 'lib_training_category.id_training_category = lib_trainings.training_category_id', 'left');
            
            if (!empty($filters['employee_id'])) {
                $query->where('employees_trainings.employee_id', $filters['employee_id']);
            }
            
            if (!empty($filters['emp_idno'])) {
                $query->where('employees_trainings.emp_idno', $filters['emp_idno']);
            }
            
            if (!empty($filters['training_id'])) {
                $query->where('employees_trainings.training_id', $filters['training_id']);
            }
            
            if (!empty($filters['training_category_id'])) {
                $query->where('lib_trainings.training_category_id', $filters['training_category_id']);
            }
            
            if (isset($filters['has_feedback'])) {
                $query->where('employees_trainings.has_feedback', $filters['has_feedback']);
            }
            
            if (isset($filters['training_cert_sent'])) {
                $query->where('employees_trainings.training_cert_sent', $filters['training_cert_sent']);
            }
            
            if (!empty($filters['date_from'])) {
                $query->where('employees_trainings.training_date_graduated >=', $filters['date_from']);
            }
            
            if (!empty($filters['date_to'])) {
                $query->where('employees_trainings.training_date_graduated <=', $filters['date_to']);
            }
            
            return $query->orderBy('employees_trainings.training_date_graduated', 'DESC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all employee trainings: ' . $e->getMessage());
            return [];
        }
    }


    public function createEmployeeTraining($data)
    {
        try {
            $data['addeddate'] = date('Y-m-d H:i:s');
            $data['employee_training_refno'] = $data['employee_training_refno'] ?? $this->generateRefNo();
            $data['has_feedback'] = $data['has_feedback'] ?? 0;
            $data['training_cert_sent'] = $data['training_cert_sent'] ?? 0;
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating employee training: ' . $e->getMessage());
            return false;
        }
    }


    public function updateEmployeeTraining($id, $data)
    {
        try {
            $data['updated_date'] = date('Y-m-d H:i:s');
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating employee training: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteEmployeeTraining($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting employee training: ' . $e->getMessage());
            return false;
        }
    }


    private function generateRefNo()
    {
        $prefix = 'ETRN-' . date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return $prefix . '-' . $random;
    }


    public function refNoExists($refNo)
    {
        try {
            return $this->where('employee_training_refno', $refNo)->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking refno: ' . $e->getMessage());
            return false;
        }
    }

    public function getTraineesByTraining($trainingId)
    {
        try {
            return $this->select('employees_trainings.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                ->where('employees_trainings.training_id', $trainingId)
                ->orderBy('employees_trainings.emp_fullname', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching trainees: ' . $e->getMessage());
            return [];
        }
    }

    public function getTrainingsByEmpIdno($empIdno)
    {
        try {
            return $this->select('employees_trainings.*, lib_trainings.training_name, lib_trainings.training_datefrom, lib_trainings.training_dateto')
                ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                ->where('employees_trainings.emp_idno', $empIdno)
                ->orderBy('employees_trainings.training_date_graduated', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching trainings by employee ID: ' . $e->getMessage());
            return [];
        }
    }


    public function markFeedbackSubmitted($id)
    {
        try {
            return $this->update($id, ['has_feedback' => 1]);
        } catch (\Exception $e) {
            log_message('error', 'Error marking feedback as submitted: ' . $e->getMessage());
            return false;
        }
    }


    public function hasFeedback($id)
    {
        try {
            $record = $this->find($id);
            return $record && $record->has_feedback == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking feedback status: ' . $e->getMessage());
            return false;
        }
    }

 
    public function markCertificateSent($id)
    {
        try {
            return $this->update($id, [
                'training_cert_sent' => 1,
                'training_cert_sentdate' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error marking certificate as sent: ' . $e->getMessage());
            return false;
        }
    }


    public function getTotalTrainingHours($employeeId)
    {
        try {
            $result = $this->selectSum('training_hours', 'total_hours')
                ->where('employee_id', $employeeId)
                ->first();
            
            return $result->total_hours ?? 0;
        } catch (\Exception $e) {
            log_message('error', 'Error calculating total hours: ' . $e->getMessage());
            return 0;
        }
    }

    public function countTrainings($filters = [])
    {
        try {
            $query = $this;
            
            if (!empty($filters['employee_id'])) {
                $query->where('employee_id', $filters['employee_id']);
            }
            
            if (!empty($filters['has_feedback'])) {
                $query->where('has_feedback', $filters['has_feedback']);
            }
            
            if (!empty($filters['training_cert_sent'])) {
                $query->where('training_cert_sent', $filters['training_cert_sent']);
            }
            
            return $query->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting trainings: ' . $e->getMessage());
            return 0;
        }
    }


    public function searchEmployeeTrainings($keyword)
    {
        try {
            return $this->select('employees_trainings.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = employees_trainings.training_id', 'left')
                ->groupStart()
                ->like('employees_trainings.emp_fullname', $keyword)
                ->orLike('employees_trainings.emp_idno', $keyword)
                ->orLike('lib_trainings.training_name', $keyword)
                ->groupEnd()
                ->orderBy('employees_trainings.emp_fullname', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching employee trainings: ' . $e->getMessage());
            return [];
        }
    }
}