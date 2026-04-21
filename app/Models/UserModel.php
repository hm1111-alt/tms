<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'userid';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
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
        'reset_date',
        'access_id',
        'hrmis_access',
    ];

    protected $useTimestamps = false;

    public function getUserWithEmployee($userid)
    {
        $db = \Config\Database::connect();
        $employeesTableExists = $db->tableExists('employees');
        
        if ($employeesTableExists) {
            return $this->select('users.*, employees.emp_fname, employees.emp_lname, employees.emp_fullname, employees.emp_position_name, employees.emp_office')
                       ->join('employees', 'employees.emp_idno = users.employee_idno')
                       ->where('users.userid', $userid)
                       ->first();
        } else {
            return $this->where('userid', $userid)->first();
        }
    }

    public function getAdminUsers()
    {
        return $this->where('access_id', 1)->findAll();
    }

    public function getEmployeeUsers()
    {
        return $this->where('access_id', 2)->findAll();
    }

    public function usernameExists($username)
    {
        return $this->where('username', $username)->countAllResults() > 0;
    }

    public function employeeHasAccount($empIdno)
    {
        return $this->where('employee_idno', $empIdno)->countAllResults() > 0;
    }
}
