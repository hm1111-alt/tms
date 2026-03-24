<?php

namespace App\Models\attendance;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Holiday extends Model
{
        protected $table = 'holidays';
//        protected $primaryKey = 'id_leave';
//        protected $allowedFields = 'leave_refno,emp_fname';
//        protected $returnType = 'object';
        
        
        function __construct(){
                // Connect to another database
                $this->db_attendance = Database::connect('attendance');
                $this->db_hrmis = Database::connect('hrmis');
                
        }
        
        
        function get_holidays($condition='')
        {
                $query = $this->db_attendance->query("SELECT *
                                                FROM holidays 
                                                LEFT JOIN holidays_category ON id_holiday_category = holiday_category_id
                                                LEFT JOIN holidays_coverage ON id_holiday_coverage=holiday_coverage_id
                                                WHERE id_holiday!='' ".$condition."
                                                ORDER BY holiday_date ASC ");
                $result = $query->getResult();

                return $result;
        }
        
        function get_holidays_categories()
        {
                $query = $this->db_attendance->query("SELECT * FROM holidays_category ");
                return $query->getResult();
        }
        function get_holidays_coverage()
        {
                $query = $this->db_attendance->query("SELECT * FROM holidays_coverage ");
                return $query->getResult();
        }
        
        
        function get_holiday_details($holiday_id)
        {
                $query = $this->db_attendance->query("SELECT *
                                                FROM holidays
                                                WHERE id_holiday=".$holiday_id."");
                return $query->getResult();
        }
        
        
}

