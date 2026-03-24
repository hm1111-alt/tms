<?php

namespace App\Models;

use CodeIgniter\Model;

class Mdl_employee extends Model
{
        protected $table = 'employees';
//        protected $primaryKey = 'id_leave';
//        protected $allowedFields = 'leave_refno,emp_fname';
//        protected $returnType = 'object';
        
        
        
        function sync()
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
                                                LEFT JOIN lib_programs ON emp_program=id_program
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
                                                LEFT JOIN lib_programs ON emp_program=id_program
                                                LEFT JOIN lib_offices ON emp_office=id_office
                                                LEFT JOIN lib_divisions ON emp_division=id_division
                                                LEFT JOIN lib_units ON emp_unit=id_unit
                                                LEFT JOIN lib_positions ON emp_position = id_position
                                                LEFT JOIN lib_salaries ON (lib_positions.salary_grade=lib_salaries.salary_grade AND salary_schedule_id=31)
                                                WHERE id_employee!='' ".$condition."
                                                ");
                return $query->getRow('counts');
        }
        
        
        
        function get_employee_basic($employee_id)
        {            
                $query = $this->db->query("SELECT employees.*,emp_sex,
                                                    division_name,division_abbr,program_name,program_abbr,unit_name,unit_abbr,office_name,office_abbr,
                                                    id_position,position_abbr,position_name,position_name cur_position_name,position_abbr cur_position_abbr,
                                                    id_status,status_name,status_name cur_status_name, status_is_service,status_is_end
                                                FROM employees
                                                LEFT JOIN lib_units ON id_unit=emp_unit
                                                LEFT JOIN lib_divisions ON id_division=emp_division
                                                LEFT JOIN lib_offices ON id_office=emp_office
                                                LEFT JOIN lib_positions ON id_position=emp_position
                                                LEFT JOIN lib_status ON id_status=emp_status
                                                WHERE id_employee=".$employee_id);
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
                    'emp_program' => $this->request->getPost('emp_program'),
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
        
        
}

