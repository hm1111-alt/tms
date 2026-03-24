<?php

namespace App\Models;

use CodeIgniter\Model;

class Mdl_Menu extends Model
{
        
        
        function get_positions_menu($all=0)
        {
                $condition = $all!=0 ? " and position_is_internal=1 " : "";
                $query = $this->db->query("SELECT * FROM lib_positions 
                                            WHERE position_is_active=1 ".$condition."
                                            ORDER BY position_name ASC
                                            ");
                return $query->getResult();
        }
        
        function get_status_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_status
                                                WHERE status_is_active=1
                                                ORDER BY status_name
                                                  ");
                return $query->getResult();
        }
        
        
        function get_lib_offices($office_id='')
        {
                $condition = $office_id!='' ? " WHERE id_office=".$office_id." " : "";
                $query = $this->db->query("SELECT * FROM lib_offices ".$condition." ");
                $result = $query->getResult();
                
                foreach($result as $prog){
                    $divisions = $this->get_divisions_menu($prog->id_office);
                    
                    if(@$divisions){
                        foreach($divisions as $div){
                            $units = $this->get_units_menu($div->id_division);
                            
                            if(@$units){
                                foreach($units as $un){
                                    $subunits = $this->get_subunits_menu($un->id_unit);
                                    $un->subunits = $subunits;
                                }
                            }
                            $div->units = $units;
                        }
                    }
                    
                    $prog->divisions = $divisions;
                }
                return $result;
        }
        
        function get_program_details($program_id)
        {
		$query = $this->db->query("SELECT * FROM lib_programs
                                            WHERE id_program=".$program_id." ");
		return $query->getResult();
        }
        
        
        function get_offices_menu($program_id='')
        {
                $condition = $program_id!='' ? " AND program_id=".$program_id." " : "";
                $query = $this->db->query("SELECT * FROM lib_offices
                                            WHERE office_isactive=1
                                            ".$condition."
                                             ORDER BY office_order ASC");
                return $query->getResult();
        }
        
        function get_office_details($office_id)
        {
		$query = $this->db->query("SELECT * FROM lib_offices
                                            WHERE id_office=".$office_id." ");
		return $query->getResult();
        }
        
        function get_divisions_menu($office_id='')
        {
                $condition = $office_id!='' ? " and office_id=".$office_id." " : "";
                $query = $this->db->query("SELECT * FROM lib_divisions WHERE division_isactive=1 ".$condition."
                                            ORDER BY division_name");
                return $query->getResult();
        }
        
        function get_division_details($division_id)
        {
		$query = $this->db->query("SELECT * FROM lib_divisions
                                            WHERE id_division=".$division_id." ");
		return $query->getResult();
        }
        
        function get_units_menu($division_id='')
        {
                $condition = $division_id!='' ? " and unit_division=".$division_id." " : "";
                $query = $this->db->query("SELECT * FROM lib_units WHERE unit_isactive=1 ".$condition."
                                            ORDER BY unit_name ASC");
                return $query->getResult();
        }
        
        function get_subunits_menu($unit_id='')
        {
                $condition = $unit_id!='' ? " and unit_id=".$unit_id." " : "";
                $query = $this->db->query("SELECT * FROM lib_subunits WHERE subunit_isactive=1 ".$condition."
                                            ORDER BY subunit_name ASC");
                return $query->getResult();
        }
        
        function get_civil_status_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_civil_status WHERE civil_status_flag=1");
                return $query->getResult();
        }
        
        /*
        function get_countries_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_countries");
                return $query->getResult();
        }
        
        function get_provinces_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_provinces  ORDER BY province_name ASC ");
                return $query->getResult();
        }
        
        function get_pwdtypes_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_pwd_types ORDER BY id_pwd_type ASC ");
                return $query->getResult();
        }
        
        function load_province_city($province_id,$district_id='')
        {
                $district = $district_id=='' ? '' : ' and district_id='.$district_id.' ';
                $query = $this->db->query("SELECT id_municipality,municipality_name
                                            FROM lib_municipalities 
                                            LEFT JOIN lib_districts ON id_district=district_id
                                            LEFT JOIN lib_provinces ON province_id=id_province
                                            WHERE province_id=" .$province_id. $district." 
                                            ORDER BY municipality_name");
                return $query->getResult();
        }
        
        function get_exams_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_exams ORDER BY exam_name ASC ");
                return $query->getResult();
        }
        
        function get_courses_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_courses WHERE course_isactive=1 ORDER BY course_name ASC ");
                return $query->getResult();
        }
        
        function get_degrees_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_degrees ORDER BY degree_level,degree_name ASC ");
                return $query->getResult();
        }
        function get_schools_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_schools ORDER BY school_name ASC ");
                return $query->getResult();
        }
        function get_universities_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_schools WHERE school_isuniversity=1 ORDER BY school_name ASC ");
                return $query->getResult();
        }
        function get_agencies_menu()
        {
                $query = $this->db->query("SELECT * FROM lib_agencies 
                                        LEFT JOIN lib_municipalities ON id_municipality=municipality_id
                                        LEFT JOIN lib_provinces ON id_province=province_id
                                        ORDER BY agency_name ASC ");
                return $query->getResult();
        }
        function get_countries()
        {
                $query = $this->db->query("SELECT * FROM lib_countries ORDER BY country_name");
                return $query->getResult();
        }
        
        function get_signatories($area_id=1)
        {
                $today = date('Y-m-d');
                $query = $this->db->query("SELECT *
                                                FROM signatories
                                                LEFT JOIN employees ON id_employee=employee_id
                                                LEFT JOIN lib_positions ON emp_position=id_position
                                                LEFT JOIN employees_designations ON ((signatories.employee_id=employees_designations.employee_id)
                                                    and (designation_datefrom<='".$today."' and designation_datefrom is not null)
                                                    and (designation_dateto>='".$today."' or designation_dateto is null))
                                                LEFT JOIN lib_designations ON id_designation=designation_id
                                                WHERE area_id=".$area_id." ORDER BY signatory_order,id_employee ASC
                                                ");
                return $query->getResult();
        }

        function get_signatory_info($employee_id, $date = '', $head=0)
        {
                $is_head = $head ? " and is_head=1 " : '';
                $today = $date=='' ? date('Y-m-d') : $date;
                $query = $this->db->query("SELECT emp_fname,emp_lname,emp_mname,emp_extname, position_name, position_abbr, designation_name,designation_abbr,priority
                                                FROM employees
                                                LEFT JOIN lib_positions ON emp_position=id_position
                                                LEFT JOIN employees_designations ON ((employees_designations.employee_id=".$employee_id.")
                                                    and (designation_datefrom<='".$today."' and designation_datefrom is not null)
                                                    and (designation_dateto>='".$today."' or designation_dateto is null))
                                                LEFT JOIN lib_designations ON id_designation=designation_id
                                                WHERE id_employee=".$employee_id."  ".$is_head."
                                                ORDER BY priority ASC
                                                ");
                return $query->getResult();
        }
         * 
         */
        
        
        function get_active_statuses()
        {
                $query = $this->db->query("SELECT * FROM lib_status
                                                WHERE status_lock=1 and status_is_end=0
                                                ORDER BY status_name
                                                  ");
                return $query->getResult();
        }
        
        function get_statuses()
        {
                $query = $this->db->query("SELECT * FROM lib_status
                                                WHERE status_is_active=1
                                                ORDER BY status_name
                                                  ");
                return $query->getResult();
        }
        
        
        
}

