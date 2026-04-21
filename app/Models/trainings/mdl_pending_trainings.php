<?php

namespace App\Models\trainings;

use CodeIgniter\Model;
use Config\Database;

class mdl_pending_trainings extends Model
{
    protected $table = 'pending_trainings';
    protected $primaryKey = 'id_pending_training';
    protected $DBGroup = 'default';
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
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'emp_idno' => 'required|min_length[3]|max_length[50]',
        'employee_id' => 'required|numeric',
        'emp_fullname' => 'required|max_length[255]',
        'training_name' => 'required|max_length[255]',
        'training_category_id' => 'permit_empty|numeric',
        'training_datefrom' => 'required|valid_date',
        'training_dateto' => 'required|valid_date',
        'training_hours' => 'permit_empty|numeric',
        'is_approved' => 'permit_empty|in_list[0,1]',
        'is_disapproved' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'emp_idno' => [
            'required' => 'Employee number is required'
        ],
        'employee_id' => [
            'required' => 'Employee ID is required'
        ],
        'emp_fullname' => [
            'required' => 'Employee full name is required'
        ],
        'training_name' => [
            'required' => 'Training name is required'
        ],
        'training_datefrom' => [
            'required' => 'Start date is required',
            'valid_date' => 'Please enter a valid start date'
        ],
        'training_dateto' => [
            'required' => 'End date is required',
            'valid_date' => 'Please enter a valid end date'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getPendingTrainingById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching pending training: ' . $e->getMessage());
            return null;
        }
    }

    public function getAllPendingTrainings($filters = [])
    {
        try {
            $query = $this->select('pending_trainings.*, 
                                        lib_training_category.training_category_name')
                ->join('lib_training_category', 'lib_training_category.id_training_category = pending_trainings.training_category_id', 'left');
            
            if (!empty($filters['employee_id'])) {
                $query->where('pending_trainings.employee_id', $filters['employee_id']);
            }
            
            if (!empty($filters['emp_idno'])) {
                $query->where('pending_trainings.emp_idno', $filters['emp_idno']);
            }
            
            if (!empty($filters['training_category_id'])) {
                $query->where('pending_trainings.training_category_id', $filters['training_category_id']);
            }
            
            // Filter by approval status
            if (isset($filters['is_approved'])) {
                $query->where('pending_trainings.is_approved', $filters['is_approved']);
            }
            
            if (isset($filters['is_disapproved'])) {
                $query->where('pending_trainings.is_disapproved', $filters['is_disapproved']);
            }
            
            // Get only pending (not approved or disapproved)
            if (!isset($filters['show_all']) || $filters['show_all'] === false) {
                $query->groupStart()
                    ->where('pending_trainings.is_approved IS NULL')
                    ->orWhere('pending_trainings.is_approved', 0)
                    ->groupEnd();
            }
            
            if (!empty($filters['date_from'])) {
                $query->where('pending_trainings.training_datefrom >=', $filters['date_from']);
            }
            
            if (!empty($filters['date_to'])) {
                $query->where('pending_trainings.training_dateto <=', $filters['date_to']);
            }
            
            if (!empty($filters['search'])) {
                $query->groupStart()
                    ->like('pending_trainings.emp_fullname', $filters['search'])
                    ->orLike('pending_trainings.training_name', $filters['search'])
                    ->orLike('pending_trainings.emp_idno', $filters['search'])
                    ->groupEnd();
            }
            
            return $query->orderBy('pending_trainings.added_date', 'DESC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching pending trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function getPendingTrainingsByEmployee($employeeId)
    {
        try {
            return $this->where('employee_id', $employeeId)
                ->where('is_approved IS NULL')
                ->orderBy('added_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching employee pending trainings: ' . $e->getMessage());
            return [];
        }
    }

 
    public function getApprovedTrainingsByEmployee($employeeId)
    {
        try {
            return $this->where('employee_id', $employeeId)
                ->where('is_approved', 1)
                ->orderBy('added_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching approved trainings: ' . $e->getMessage());
            return [];
        }
    }


    public function getDisapprovedTrainingsByEmployee($employeeId)
    {
        try {
            return $this->where('employee_id', $employeeId)
                ->where('is_disapproved', 1)
                ->orderBy('added_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching disapproved trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function createPendingTraining($data)
    {
        try {
            $data['added_date'] = date('Y-m-d H:i:s');
            $data['is_approved'] = $data['is_approved'] ?? null;
            $data['is_disapproved'] = $data['is_disapproved'] ?? null;
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating pending training: ' . $e->getMessage());
            return false;
        }
    }

 
    public function updatePendingTraining($id, $data)
    {
        try {
            $data['updated_date'] = date('Y-m-d H:i:s');
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating pending training: ' . $e->getMessage());
            return false;
        }
    }


    public function deletePendingTraining($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting pending training: ' . $e->getMessage());
            return false;
        }
    }


    public function approveTraining($id, $remarks = '')
    {
        try {
            return $this->update($id, [
                'is_approved' => 1,
                'is_disapproved' => 0,
                'approve_remarks' => $remarks,
                'updated_date' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error approving training: ' . $e->getMessage());
            return false;
        }
    }


    public function disapproveTraining($id, $remarks = '')
    {
        try {
            return $this->update($id, [
                'is_approved' => 0,
                'is_disapproved' => 1,
                'approve_remarks' => $remarks,
                'updated_date' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error disapproving training: ' . $e->getMessage());
            return false;
        }
    }

    public function isApproved($id)
    {
        try {
            $record = $this->find($id);
            return $record && $record->is_approved == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking approval status: ' . $e->getMessage());
            return false;
        }
    }

    public function isDisapproved($id)
    {
        try {
            $record = $this->find($id);
            return $record && $record->is_disapproved == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking disapproval status: ' . $e->getMessage());
            return false;
        }
    }


    public function isPending($id)
    {
        try {
            $record = $this->find($id);
            return $record && ($record->is_approved === null || $record->is_approved == 0) && $record->is_disapproved != 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking pending status: ' . $e->getMessage());
            return false;
        }
    }


    public function countPendingTrainings($filters = [])
    {
        try {
            $query = $this;
            
            if (!empty($filters['employee_id'])) {
                $query->where('employee_id', $filters['employee_id']);
            }
            
            if (!empty($filters['is_approved'])) {
                $query->where('is_approved', $filters['is_approved']);
            }
            
            if (!empty($filters['is_disapproved'])) {
                $query->where('is_disapproved', $filters['is_disapproved']);
            }
            
            return $query->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting pending trainings: ' . $e->getMessage());
            return 0;
        }
    }


    public function searchPendingTrainings($keyword)
    {
        try {
            return $this->groupStart()
                ->like('emp_fullname', $keyword)
                ->orLike('training_name', $keyword)
                ->orLike('emp_idno', $keyword)
                ->orLike('training_facilitator', $keyword)
                ->groupEnd()
                ->orderBy('emp_fullname', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching pending trainings: ' . $e->getMessage());
            return [];
        }
    }

 
    public function getPendingTrainingsByCategory($categoryId)
    {
        try {
            return $this->where('training_category_id', $categoryId)
                ->orderBy('added_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching pending trainings by category: ' . $e->getMessage());
            return [];
        }
    }
}