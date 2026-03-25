<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class mdl_menu extends Model
{
    function __construct(){
        $this->db = Database::connect(); // default
        $this->db_employee = Database::connect('db_employee');
    }
    
    /**
     * Get all offices
     */
    function get_lib_offices()
    {
        try {
            $query = $this->db_employee->query("SELECT * FROM lib_offices ORDER BY office_name");
            return $query->getResult();
        } catch(\Exception $e) {
            log_message('error', 'Error getting offices: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get divisions by office
     */
    function get_divisions_menu($office_id = null)
    {
        try {
            if($office_id) {
                $query = $this->db_employee->query("SELECT * FROM lib_divisions 
                                                    WHERE office_id = ? 
                                                    ORDER BY division_name", [$office_id]);
            } else {
                $query = $this->db_employee->query("SELECT * FROM lib_divisions ORDER BY division_name");
            }
            return $query->getResult();
        } catch(\Exception $e) {
            log_message('error', 'Error getting divisions: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get units by division
     */
    function get_units_menu($division_id = null)
    {
        try {
            if($division_id) {
                $query = $this->db_employee->query("SELECT * FROM lib_units 
                                                    WHERE division_id = ? 
                                                    ORDER BY unit_name", [$division_id]);
            } else {
                $query = $this->db_employee->query("SELECT * FROM lib_units ORDER BY unit_name");
            }
            return $query->getResult();
        } catch(\Exception $e) {
            log_message('error', 'Error getting units: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get status menu (for trainings/applications)
     */
    function get_status_menu()
    {
        try {
            $query = $this->db->query("SELECT * FROM training_status ORDER BY status_name");
            return $query->getResult();
        } catch(\Exception $e) {
            log_message('error', 'Error getting status: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get offices menu (alias for get_lib_offices)
     */
    function get_offices_menu()
    {
        return $this->get_lib_offices();
    }
}
