<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Training_Status extends Model
{
    protected $table = 'training_status';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'status_name'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'status_name' => 'required|min_length[3]|max_length[100]|is_unique[training_status.status_name,id,{id}]'
    ];
    
    protected $validationMessages = [
        'status_name' => [
            'required' => 'Status name is required',
            'min_length' => 'Status name must be at least 3 characters',
            'max_length' => 'Status name cannot exceed 100 characters',
            'is_unique' => 'This status already exists'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getAllStatuses()
    {
        try {
            return $this->orderBy('status_name', 'ASC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training statuses: ' . $e->getMessage());
            return [];
        }
    }


    public function getStatusById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching status by ID: ' . $e->getMessage());
            return null;
        }
    }


    public function getStatusName($id)
    {
        try {
            $result = $this->find($id);
            return $result ? $result->status_name : null;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching status name: ' . $e->getMessage());
            return null;
        }
    }


    public function createStatus($data)
    {
        try {
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating training status: ' . $e->getMessage());
            return false;
        }
    }


    public function updateStatus($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating training status: ' . $e->getMessage());
            return false;
        }
    }


    public function deleteStatus($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training status: ' . $e->getMessage());
            return false;
        }
    }


    public function statusNameExists($statusName, $excludeId = null)
    {
        try {
            $query = $this->where('status_name', $statusName);
            
            if ($excludeId !== null) {
                $query->where('id !=', $excludeId);
            }
            
            return $query->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking status name existence: ' . $e->getMessage());
            return false;
        }
    }

    public function getStatusesWithCount()
    {
        try {
            return $this->select('training_status.*, COUNT(other_training_info.id) as training_count')
                ->join('other_training_info', 'other_training_info.status_id = training_status.id', 'left')
                ->groupBy('training_status.id')
                ->orderBy('training_status.status_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching statuses with count: ' . $e->getMessage());
            return [];
        }
    }

 
    public function getStatusesForDropdown()
    {
        try {
            $statuses = $this->getAllStatuses();
            $options = [];
            
            foreach ($statuses as $status) {
                $options[$status->id] = $status->status_name;
            }
            
            return $options;
        } catch (\Exception $e) {
            log_message('error', 'Error getting statuses for dropdown: ' . $e->getMessage());
            return [];
        }
    }


    public function searchStatuses($keyword)
    {
        try {
            return $this->like('status_name', $keyword)
                ->orderBy('status_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching statuses: ' . $e->getMessage());
            return [];
        }
    }

  
    public function countStatuses()
    {
        try {
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting statuses: ' . $e->getMessage());
            return 0;
        }
    }
}
