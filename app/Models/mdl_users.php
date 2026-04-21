<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'userid';
    protected $allowedFields = [
        'username',
        'password',
        'password_expired',
        'employee_idno',
        'user_email',
        'user_type_id',
        'date_created',
        'date_updated',
        'last_login',
        'created_by',
        'is_active',
        'temp_pass',
        'register_refno',
        'register_verified',
        'first_login',
        'password_reset',
        'reset_link',
        'reset_date'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]',
        'password' => 'required|min_length[6]',
        'user_email' => 'required|valid_email',
        'employee_idno' => 'permit_empty|numeric',
        'user_type_id' => 'required|numeric',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters'
        ],
        'user_email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address'
        ],
        'user_type_id' => [
            'required' => 'User type is required'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getUserById($userId)
    {
        try {
            return $this->find($userId);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user by ID: ' . $e->getMessage());
            return null;
        }
    }


    public function getUserByUsername($username)
    {
        try {
            return $this->where('username', $username)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user by username: ' . $e->getMessage());
            return null;
        }
    }


    public function getUserByEmail($email)
    {
        try {
            return $this->where('user_email', $email)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user by email: ' . $e->getMessage());
            return null;
        }
    }


    public function getUserByEmployeeIdNo($employeeIdNo)
    {
        try {
            return $this->where('employee_idno', $employeeIdNo)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user by employee ID: ' . $e->getMessage());
            return null;
        }
    }


    public function getAllUsers($filters = [])
    {
        try {
            $query = $this->select('users.*, user_types.user_type_name')
                ->join('user_types', 'user_types.id = users.user_type_id', 'left');
            
            if (!empty($filters['is_active'])) {
                $query->where('users.is_active', $filters['is_active']);
            }
            
            if (!empty($filters['user_type_id'])) {
                $query->where('users.user_type_id', $filters['user_type_id']);
            }
            
            if (!empty($filters['search'])) {
                $query->groupStart()
                    ->like('users.username', $filters['search'])
                    ->orLike('users.user_email', $filters['search'])
                    ->orLike('users.employee_idno', $filters['search'])
                    ->groupEnd();
            }
            
            return $query->orderBy('users.date_created', 'DESC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all users: ' . $e->getMessage());
            return [];
        }
    }

    public function createUser($data)
    {
        try {
            // Set default values
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['date_updated'] = date('Y-m-d H:i:s');
            $data['is_active'] = $data['is_active'] ?? 1;
            $data['password_expired'] = $data['password_expired'] ?? 0;
            $data['register_verified'] = $data['register_verified'] ?? 0;
            $data['first_login'] = $data['first_login'] ?? 0;
            $data['password_reset'] = $data['password_reset'] ?? 0;
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating user: ' . $e->getMessage());
            return false;
        }
    }

    public function updateUser($userId, $data)
    {
        try {
            $data['date_updated'] = date('Y-m-d H:i:s');
            return $this->update($userId, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating user: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteUser($userId)
    {
        try {
            return $this->update($userId, ['is_active' => 0, 'date_updated' => date('Y-m-d H:i:s')]);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting user: ' . $e->getMessage());
            return false;
        }
    }


    public function updateLastLogin($userId)
    {
        try {
            return $this->update($userId, [
                'last_login' => date('Y-m-d H:i:s'),
                'date_updated' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating last login: ' . $e->getMessage());
            return false;
        }
    }

    public function isPasswordExpired($userId)
    {
        try {
            $user = $this->find($userId);
            return $user && $user->password_expired == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking password expiration: ' . $e->getMessage());
            return false;
        }
    }

    public function setPasswordExpired($userId)
    {
        try {
            return $this->update($userId, [
                'password_expired' => 1,
                'date_updated' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error setting password expired: ' . $e->getMessage());
            return false;
        }
    }

    public function verifyRegistration($refNo)
    {
        try {
            return $this->where('register_refno', $refNo)
                ->set(['register_verified' => 1, 'date_updated' => date('Y-m-d H:i:s')])
                ->update();
        } catch (\Exception $e) {
            log_message('error', 'Error verifying registration: ' . $e->getMessage());
            return false;
        }
    }

    public function setResetLink($userId, $resetLink)
    {
        try {
            return $this->update($userId, [
                'reset_link' => $resetLink,
                'reset_date' => date('Y-m-d H:i:s'),
                'password_reset' => 1,
                'date_updated' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error setting reset link: ' . $e->getMessage());
            return false;
        }
    }


    public function clearResetData($userId)
    {
        try {
            return $this->update($userId, [
                'reset_link' => null,
                'reset_date' => null,
                'password_reset' => 0,
                'date_updated' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error clearing reset data: ' . $e->getMessage());
            return false;
        }
    }

 
    public function isActive($userId)
    {
        try {
            $user = $this->find($userId);
            return $user && $user->is_active == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking user status: ' . $e->getMessage());
            return false;
        }
    }


    public function activateUser($userId)
    {
        try {
            return $this->update($userId, [
                'is_active' => 1,
                'date_updated' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error activating user: ' . $e->getMessage());
            return false;
        }
    }

    public function deactivateUser($userId)
    {
        try {
            return $this->update($userId, [
                'is_active' => 0,
                'date_updated' => date('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error deactivating user: ' . $e->getMessage());
            return false;
        }
    }


    public function countUsers($filters = [])
    {
        try {
            $query = $this;
            
            if (!empty($filters['is_active'])) {
                $query->where('is_active', $filters['is_active']);
            }
            
            if (!empty($filters['user_type_id'])) {
                $query->where('user_type_id', $filters['user_type_id']);
            }
            
            return $query->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting users: ' . $e->getMessage());
            return 0;
        }
    }

  
    public function getUsersByType($userTypeId)
    {
        try {
            return $this->where('user_type_id', $userTypeId)
                ->orderBy('username', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching users by type: ' . $e->getMessage());
            return [];
        }
    }


    public function searchUsers($keyword)
    {
        try {
            return $this->groupStart()
                ->like('username', $keyword)
                ->orLike('user_email', $keyword)
                ->orLike('employee_idno', $keyword)
                ->groupEnd()
                ->orderBy('username', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching users: ' . $e->getMessage());
            return [];
        }
    }
}
