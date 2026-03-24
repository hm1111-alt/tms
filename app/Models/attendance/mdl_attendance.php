<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Attendance extends Model
{
        function __construct(){
                // Connect to another database
                $this->db_hrmis = Database::connect('hrmis');
                
        }
        
        
        function get_holidays($condition='')
        {
                $query = $this->db_hrmis->query("SELECT *
                                                FROM holidays 
                                                LEFT JOIN holidays_category ON id_holiday_category = holiday_category_id
                                                LEFT JOIN holidays_coverage ON id_holiday_coverage=holiday_coverage_id
                                                WHERE id_holiday!='' ".$condition."
                                                ORDER BY holiday_date ASC ");
                $result = $query->getResult();

                return $result;
        }
        
        
}

