<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Dashboard extends Model
{
        protected $table = 'employees';
//        protected $primaryKey = 'id_leave';
//        protected $allowedFields = 'leave_refno,emp_fname';
//        protected $returnType = 'object';
        
        function __construct(){
                $this->db = Database::connect(); // default
                $this->db_hrmis = Database::connect('hrmis');
        }
        
        function get_memos()
        {
                $query = $this->db_hrmis->query("SELECT * FROM memos
                                                WHERE memo_is_active=1
                                                ORDER BY memo_sort ASC 
                                                LIMIT 3");
                return $query->getResult();
        }
        
        function get_memo_details($memo_id)
        {
                $query = $this->db_hrmis->query("SELECT * FROM memos
                                                WHERE id_memo='".$memo_id."' ");
                return $query->getRow();
        }
        
        
        function get_employee_basic($employee_id)
        {            
                $query = $this->db->query("SELECT employees.*,emp_sex,
                                                    office_name,office_abbr,office_ismain,
                                                    division_name,division_abbr,
                                                    unit_name,unit_abbr
                                                    
                                                FROM employees
                                                LEFT JOIN lib_offices ON id_office=emp_office
                                                LEFT JOIN lib_divisions ON id_division=emp_division
                                                LEFT JOIN lib_units ON id_unit=emp_unit
                                                WHERE id_employee=".$employee_id);
                $result = $query->getResult();
                
                foreach($result as $row){
                    
                    $query2 = $this->db_hrmis->query("SELECT emp_mname,emp_cpno,emp_date_hired
                                                    FROM employees
                                                    WHERE id_employee=".$row->id_employee);
                    $emp_mname = @$query2->getRow()->emp_mname;
                    $row->emp_mname = $emp_mname;
                    $emp_cpno = @$query2->getRow()->emp_cpno;
                    $row->emp_cpno = $emp_cpno;
                    $row->emp_date_hired = @$query2->getRow()->emp_date_hired;
                }
                
                return $result;
        }
        
        
	function encode($data) 
        {
                $firstkey = 'qVEq39xkOn9CuqLQ4gFZe2aQaatJs9BiUqVmeb9Cw3c=';
                $secondkey = 'FTHOaut2rP2tcetm4p4qTSd6HKedlPfiJ5m6+jTcqOwSN00YO1+KY6f65PrORrqszHbs97qM1Ri4rv7oPVxmvg==';
        
		$first_key = base64_decode($firstkey);
		$second_key = base64_decode($secondkey);    

		$method = "AES-256-CBC";    
		$iv_length = openssl_cipher_iv_length($method);
		$iv = openssl_random_pseudo_bytes($iv_length);

		$first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA ,$iv);    
		$second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

		$output = base64_encode($iv.$second_encrypted.$first_encrypted);    
		return $output;  
            
        }
	function decode($input) 
        {
                $firstkey = 'qVEq39xkOn9CuqLQ4gFZe2aQaatJs9BiUqVmeb9Cw3c=';
                $secondkey = 'FTHOaut2rP2tcetm4p4qTSd6HKedlPfiJ5m6+jTcqOwSN00YO1+KY6f65PrORrqszHbs97qM1Ri4rv7oPVxmvg==';
        
		$first_key = base64_decode($firstkey);
		$second_key = base64_decode($secondkey);             
		$mix = base64_decode($input);

		$method = "AES-256-CBC";    
		$iv_length = openssl_cipher_iv_length($method);
		$iv = substr($mix, 0, $iv_length);
		
		$second_encrypted = substr($mix, $iv_length, 64);
		$first_encrypted = substr($mix, $iv_length+64);

		$data = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);
		$second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

		if (hash_equals($second_encrypted,$second_encrypted_new))
			return $data;

		return false;
	}
        
        
        
        function get_employees($condition,$page,$num_list,$order_by, $sort_by='asc')
        {
                $order_by = $order_by ? $order_by : 'emp_lname';
		if($num_list!='all' && $num_list!='') $limit =" limit ".($page-1)*$num_list.", ".$num_list." ";
                else $limit='';
                
                //$access_level = session()->get('access_level');
                
                /*if($access_level==2){
                    $condition .= ' and emp_program='.session()->get('emp_program');
                }else if($access_level==3){
                    $condition .= ' and emp_office='.session()->get('emp_office');
                }else if($access_level==4){
                    $condition .= ' and emp_division='.session()->get('emp_division');
                }else if($access_level==5){
                    $condition .= ' and emp_unit='.session()->get('emp_unit');
                }else if($access_level==6){
                    $condition .= ' and emp_idno="'.session()->get('emp_idno').'"';
                }
                
                $this->load->model('settings/mdl_settings');
                $status = $this->mdl_settings->get_settings('permanent_status_id');
                $condition .= " and (emp_status=".$status;
                $status = $this->mdl_settings->get_settings('coterminus_status_id');
                $condition .= " or emp_status=".$status;
                $status = $this->mdl_settings->get_settings('presl_appointee_status_id');
                $condition .= " or emp_status=".$status;
                $status = $this->mdl_settings->get_settings('contractual_status_id');
                $condition .= " or emp_status=".$status.")";
                */
                
                
                $query = $this->db->query("SELECT *
                                                FROM  employees
                                                LEFT JOIN lib_offices ON emp_office=id_office
                                                LEFT JOIN lib_divisions ON emp_division=id_division
                                                LEFT JOIN lib_units ON emp_unit=id_unit
                                                LEFT JOIN lib_status ON emp_status = id_status
                                                LEFT JOIN lib_positions ON emp_position = id_position
                                                LEFT JOIN lib_salaries ON (lib_positions.salary_grade=lib_salaries.salary_grade AND salary_schedule_id=31)
                                                WHERE  id_employee ".$condition."
                                                ORDER BY ".$order_by." ".$sort_by."
                                                ".$limit." ");
                $result = $query->getResult();
                
                return $result;
        }
        function count_employees($condition)
        {
                $access_level = session()->get('access_level');
                /*
                if($access_level==2){
                    $condition .= ' and emp_program='.session()->get('emp_program');
                }else if($access_level==3){
                    $condition .= ' and emp_office='.session()->get('emp_office');
                }else if($access_level==4){
                    $condition .= ' and emp_division='.session()->get('emp_division');
                }else if($access_level==5){
                    $condition .= ' and emp_unit='.session()->get('emp_unit');
                }else if($access_level==6){
                    $condition .= ' and emp_idno="'.session()->get('emp_idno').'"';
                }
                
                $this->load->model('settings/mdl_settings');
                $status = $this->mdl_settings->get_settings('permanent_status_id');
                $condition .= " and (emp_status=".$status;
                $status = $this->mdl_settings->get_settings('coterminus_status_id');
                $condition .= " or emp_status=".$status;
                $status = $this->mdl_settings->get_settings('presl_appointee_status_id');
                $condition .= " or emp_status=".$status;
                $status = $this->mdl_settings->get_settings('contractual_status_id');
                $condition .= " or emp_status=".$status.")";*/
                
                $query = $this->db->query("SELECT count(*) as counts
                                                FROM employees
                                                LEFT JOIN lib_offices ON emp_office=id_office
                                                LEFT JOIN lib_divisions ON emp_division=id_division
                                                LEFT JOIN lib_units ON emp_unit=id_unit
                                                LEFT JOIN lib_positions ON emp_position = id_position
                                                LEFT JOIN lib_salaries ON (lib_positions.salary_grade=lib_salaries.salary_grade AND salary_schedule_id=31)
                                                WHERE id_employee!='' ".$condition."
                                                ");
                return $query->getRow('counts');
        }
        
        
        
        
        function set_esigname($length=10)
        {
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $link_pass = substr(str_shuffle($chars),0,$length);

                if(count($this->check_esigname_exist($link_pass))>0){
                    $link_pass2 = $this->set_esigname($length);
                    return $link_pass2;
                } else return $link_pass;
        }
        
        function check_esigname_exist($link_pass)
        {
                $query = $this->db_hrmis->query("SELECT * FROM employees_esign
                                                WHERE esign_file_name='".$link_pass."' ");
                return $query->getResult();
        }
        
        
        function save_employee($employee_id='')
        { //$this->request->getPost('username');
        
                $this->request = \Config\Services::request();
                
                $emp_fullname = $this->request->getPost('emp_fname').' ';
                $emp_fullname .= $this->request->getPost('emp_mi')!='' ? $this->request->getPost('emp_mi').'. ' : '';
                $emp_fullname .= $this->request->getPost('emp_lname');
                $emp_fullname .= $this->request->getPost('emp_extname')!='' ? ' '.$this->request->getPost('emp_extname') : '';
                
                $data = array(
                    'emp_idno' => $this->request->getPost('emp_idno'),
                    'emp_fname' => $this->request->getPost('emp_fname'),
                    'emp_lname' => $this->request->getPost('emp_lname'),
                    'emp_mname' => $this->request->getPost('emp_mname'),
                    'emp_mi' => $this->request->getPost('emp_mi'),
                    'emp_extname' => $this->request->getPost('emp_extname'),
                    'emp_fullname' => $emp_fullname,
                    'emp_sex' => $this->request->getPost('emp_sex'),
                    'emp_email_official' => $this->request->getPost('emp_email_official'),
                    'emp_email_personal' => $this->request->getPost('emp_email_personal'),
                    
                    'emp_status' => $this->request->getPost('emp_status'),
                    'emp_date_hired' => date('Y-m-d',strtotime($this->request->getPost('emp_date_hired'))),
                    'emp_position' => $this->request->getPost('emp_position'),
                    'emp_office' => $this->request->getPost('emp_office')!='---' ? $this->request->getPost('emp_office') : null,
                    'emp_division' => $this->request->getPost('emp_division')!='---' ? $this->request->getPost('emp_division') : null,
                    'emp_unit' => $this->request->getPost('emp_unit')!='' ? $this->request->getPost('emp_unit') : null,
                    'emp_is_active' => $this->request->getPost('emp_is_active')==true ? 1 : 0,
                    
                    'emp_date_updated' => date('Y-m-d H:i:s'),
                    'emp_updated_by' => session()->get('userid'),
                );
                        
                if($employee_id==''){
                    $action = 'ADD';
                    $res = $this->db->table('employees')->insert($data);                    
                    $employee_id = $this->db->insertID();
                } else {
                    $action = 'UPDATE';
                    $res = $this->db->table('employees')->where('id_employee', $employee_id)->update($data);
                }
                
                if($res){
                    
                    $this->session = \Config\Services::session();
                    
                    $his_data = array(
                        'history_date'=>date('Y-m-d H:i:s'),
                        'history_name'=>$employee_id,
                        'history_category'=>'EMPLOYEES',
                        'history_action'=>$action,
                        'history_user'=>session()->get('userid'),
                        'history_ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'history_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR']),
                    );
                    $this->db->table('history')->insert($his_data);
                }
                
                return $res;
        }
        
        
        
        function fix_divisions()
        {            
                $query = $this->db_hrmis->query("SELECT *
                                                FROM lib_divisions
                                                LEFT JOIN lib_offices ON division_office=id_office
                                                ");
                $result = $query->getResult();
                
                foreach($result as $row){
                    
                        $data = array(
                            'division_program' => $row->program_id,
                            'division_updated_date' => null
                        );
                        
                        if(@$row->division_abbr==''){
                            $data += array(
                                'division_abbr' => null,
                            );
                        }
                    
                        $this->db_hrmis->table('lib_divisions')
                                ->where('id_division', $row->id_division)
                                ->update($data);
                }
                echo 'finished';
                die;
        }
        
        function sync111()
        {
                $db_hrmis = \Config\Database::connect('hrmis'); // Connects to the secondary database

                
                $query = $db_hrmis->query("SELECT *
                                                FROM  employees
                                                LEFT JOIN plantillaitems ON plantillaitems.ItemID=employees.ItemID
                                                 ");
                $result = $query->getResult();
                
                foreach ($result as $row){
                    
                
                        $query_position = $this->db->query("SELECT *
                                                        FROM  lib_positions
                                                        WHERE  position_name='".trim($row->ItemTitle)."'
                                                            ");
                        $position = $query_position->getResult();
                    
                        if(@$position){
                            $position_id = @$position[0]->id_position;
                        } else {
                            $position_data = array(
                                'position_name' => trim($row->ItemTitle),
                                'salary_grade' => intval($row->ItemSalaryGrade),
                                'position_is_internal'=>1,
                            );
                            $this->db->table('lib_positions')->insert($position_data);                    
                            $position_id = $this->db->insertID();
                        }
                        
                        $fullname = $row->FirstName.' '.$row->LastName;
                        $fullname .= @$row->ExtName!='' ? ' '.$row->ExtName : '';
                        
                        $emp_sex = $row->Sex=='M' ? 'Male' : 'Female';
                        
                        
                        $query_status = $this->db->query("SELECT *
                                                        FROM  lib_status
                                                        WHERE  status_name='".trim($row->Status)."'
                                                            ");
                        $status = $query_status->getResult();
                        
                        if(@$status){
                            $status_id = @$status[0]->id_status;
                        } else {
                            $status_data = array(
                                'status_name' => trim($row->ItemTitle),
                                'status_is_active'=>1,
                                'status_is_service'=>1,
                            );
                            $this->db->table('lib_status')->insert($status_data);                    
                            $status_id = $this->db->insertID();
                        }
                        
                        $emp_data = array(
                            'emp_idno' => $row->EmployeeID,
                            'emp_fname' => $row->FirstName,
                            'emp_lname' => $row->LastName,
                            'emp_mname' => null,//$row->EmployeeID,
                            'emp_mi' => null,//$row->EmployeeID,
                            'emp_extname' => $row->ExtName,
                            'emp_fullname' => $fullname,
                            'emp_sex' => $emp_sex,
                            'emp_status' => $status_id,
                            'emp_date_hired' => date('Y-m-d',strtotime($row->OrigAppointDate)),
                            'emp_position' => $position_id,
                            'step_increment' => 1,
                            'emp_date_updated' => date('Y-m-d H:i:s'),
                            'emp_updated_by' => 1,
                        );
                        $this->db->table('employees')->insert($emp_data);                    
                }
                echo 'finished';
                //return $result;
        }
        
        
}

