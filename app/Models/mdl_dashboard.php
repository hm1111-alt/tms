<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class mdl_dashboard extends Model
{
    protected $table = 'employees';
    
    function __construct(){
        $this->db = Database::connect(); // default
        $this->db_employee = Database::connect('db_employee');
    }
    
    /**
     * Get employee basic information
     */
    function get_employee_basic($employee_id)
    {
        try {
            $query = $this->db_employee->query("SELECT emp_idno, emp_fname, emp_lname, emp_mi, emp_extname,
                                                       emp_office, emp_division, emp_unit,
                                                       emp_position_name, emp_class_name, emp_status2,
                                                       emp_email_official, emp_is_active
                                                FROM employees
                                                WHERE id_employee = ?", [$employee_id]);
            return $query->getResult();
        } catch(\Exception $e) {
            log_message('error', 'Error getting employee basic info: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get memo details
     */
    function get_memo_details($memo_id = null)
    {
        try {
            if($memo_id) {
                $query = $this->db->query("SELECT * FROM memos WHERE id_memo = ?", [$memo_id]);
                $result = $query->getResult();
                return !empty($result) ? $result[0] : null;
            }
            return null;
        } catch(\Exception $e) {
            log_message('error', 'Error getting memo details: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get employee details by ID number
     */
    function get_employee_details($emp_idno)
    {
        try {
            $query = $this->db_employee->query("SELECT * FROM employees WHERE emp_idno = ?", [$emp_idno]);
            return $query->getResult();
        } catch(\Exception $e) {
            log_message('error', 'Error getting employee details: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Save employee data (placeholder - implement as needed)
     */
    function save_employee($employee_id)
    {
        try {
            // This method should be implemented based on your business logic
            // For now, returning true as a placeholder
            return true;
        } catch(\Exception $e) {
            log_message('error', 'Error saving employee: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Encode function (for portal ID encoding)
     */
    function encode($data)
    {
        $firstkey = 'qVEq39xkOn9CuqLQ4gFZe2aQaatJs9BiUqVmeb9Cw3c=';
        $secondkey = 'MjRfUE9SVEFMX1NZU1RFTQ==';
        
        $encode = base64_encode(base64_encode($firstkey.base64_encode(base64_encode($data).$secondkey)));
        return $encode;
    }
}
