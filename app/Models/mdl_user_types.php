<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_User_Types extends Model
{
    protected $table = 'user_types';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_type'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'user_type' => 'required|min_length[3]|max_length[100]|is_unique[user_types.user_type,id,{id}]'
    ];
    
    protected $validationMessages = [
        'user_type' => [
            'required' => 'User type is required',
            'min_length' => 'User type must be at least 3 characters',
            'max_length' => 'User type cannot exceed 100 characters',
            'is_unique' => 'This user type already exists'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getAllUserTypes($includeInactive = false)
    {
        try {
            $query = $this->orderBy('user_type', 'ASC');
            
            if (!$includeInactive && $this->db->fieldExists('is_active', 'user_types')) {
                $query->where('is_active', 1);
            }
            
            return $query->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user types: ' . $e->getMessage());
            return [];
        }
    }


    public function getUserTypeById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user type by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function getUserTypeName($id)
    {
        try {
            $result = $this->find($id);
            return $result ? $result->user_type : null;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user type name: ' . $e->getMessage());
            return null;
        }
    }

    public function createUserType($data)
    {
        try {
            // Set default values if needed
            if ($this->db->fieldExists('is_active', 'user_types')) {
                $data['is_active'] = $data['is_active'] ?? 1;
            }
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating user type: ' . $e->getMessage());
            return false;
        }
    }


    public function updateUserType($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating user type: ' . $e->getMessage());
            return false;
        }
    }


    public function deleteUserType($id)
    {
        try {
            if ($this->db->fieldExists('is_active', 'user_types')) {
                // Soft delete
                return $this->update($id, ['is_active' => 0]);
            } else {
                // Hard delete
                return $this->delete($id);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting user type: ' . $e->getMessage());
            return false;
        }
    }

    public function userTypeExists($userType, $excludeId = null)
    {
        try {
            $query = $this->where('user_type', $userType);
            
            if ($excludeId !== null) {
                $query->where('id !=', $excludeId);
            }
            
            return $query->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking user type existence: ' . $e->getMessage());
            return false;
        }
    }


    public function getUserTypesWithCount()
    {
        try {
            return $this->select('user_types.*, COUNT(users.userid) as user_count')
                ->join('users', 'users.user_type_id = user_types.id', 'left')
                ->groupBy('user_types.id')
                ->orderBy('user_types.user_type', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user types with count: ' . $e->getMessage());
            return [];
        }
    }

  
    public function activateUserType($id)
    {
        try {
            if ($this->db->fieldExists('is_active', 'user_types')) {
                return $this->update($id, ['is_active' => 1]);
            }
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error activating user type: ' . $e->getMessage());
            return false;
        }
    }

    public function deactivateUserType($id)
    {
        try {
            if ($this->db->fieldExists('is_active', 'user_types')) {
                return $this->update($id, ['is_active' => 0]);
            }
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error deactivating user type: ' . $e->getMessage());
            return false;
        }
    }


    public function getActiveUserTypesForDropdown()
    {
        try {
            $types = $this->getAllUserTypes(false);
            $options = [];
            
            foreach ($types as $type) {
                $options[$type->id] = $type->user_type;
            }
            
            return $options;
        } catch (\Exception $e) {
            log_message('error', 'Error getting user types for dropdown: ' . $e->getMessage());
            return [];
        }
    }


    public function searchUserTypes($keyword)
    {
        try {
            return $this->like('user_type', $keyword)
                ->orderBy('user_type', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching user types: ' . $e->getMessage());
            return [];
        }
    }
}
