<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

use CodeIgniter\Files\File;
use CodeIgniter\Image\Image;

class Mdl_Profile extends Model
{
        protected $table = 'employees';
//        protected $primaryKey = 'id_leave';
//        protected $allowedFields = 'leave_refno,emp_fname';
//        protected $returnType = 'object';
        
        function __construct(){
                $this->db = Database::connect(); // default
                $this->db_hrmis = Database::connect('hrmis');
        }
        
        function get_profile_pages($page_id="")
        {            
                $condition = $page_id!='' ? " AND id_page=".$page_id." " : "";
                $query = $this->db->query("SELECT * FROM pages
                                                WHERE page_parent=7 ".$condition."
                                                ORDER BY position ASC");
                return $query->getResult();
        }
        
        function get_employee_basic($employee_id)
        {
                // Return empty array if no employee_id provided
                if(empty($employee_id)) {
                    return [];
                }
                
                $query = $this->db->query("SELECT employees.*,emp_sex,
                                                    office_ismain,office_name,office_abbr,
                                                    division_name,division_abbr,
                                                    unit_name,unit_abbr
                                                    
                                                FROM employees
                                                LEFT JOIN lib_offices ON id_office=emp_office
                                                LEFT JOIN lib_divisions ON id_division=emp_division
                                                LEFT JOIN lib_units ON id_unit=emp_unit
                                                WHERE id_employee=" . $this->db->escape($employee_id));
                $result = $query->getResult();
                
                foreach($result as $row){
                    
                    // Try to get additional data from hrmis database
                    try {
                        $query2 = $this->db_hrmis->query("SELECT emp_mname,emp_cpno,emp_date_hired
                                                        FROM employees
                                                        WHERE id_employee=" . $this->db->escape($row->id_employee));
                        $emp_mname = @$query2->getRow()->emp_mname;
                        $row->emp_mname = $emp_mname;
                        $emp_cpno = @$query2->getRow()->emp_cpno;
                        $row->emp_cpno = $emp_cpno;
                        $row->emp_date_hired = @$query2->getRow()->emp_date_hired;
                    } catch(\Exception $e) {
                        // HRMIS not available, set null values
                        $row->emp_mname = null;
                        $row->emp_cpno = null;
                        $row->emp_date_hired = null;
                    }
                }
                
                return $result;
        }
        
        
        function save_first_update()
        { 
                $employee_id = session()->get('empid');
                
                $this->request = \Config\Services::request();
                
                $emp_fullname = $this->request->getPost('emp_fname').' ';
                $emp_fullname .= $this->request->getPost('emp_mi')!='' ? $this->request->getPost('emp_mi').'. ' : '';
                $emp_fullname .= $this->request->getPost('emp_lname');
                $emp_fullname .= $this->request->getPost('emp_extname')!='' ? ' '.$this->request->getPost('emp_extname') : '';
                
                $emp_fullname2 = $this->request->getPost('emp_lname').', ';
                $emp_fullname2 .= $this->request->getPost('emp_fname');
                $emp_fullname2 .= $this->request->getPost('emp_extname')!='' ? ' '.$this->request->getPost('emp_extname') : '';
                $emp_fullname2 .= $this->request->getPost('emp_mi')!='' ? ' '.$this->request->getPost('emp_mi').'. ' : '';
                
                $data = array(
                    'emp_mi' => $this->request->getPost('emp_mi'),
                    //'emp_extname' => $this->request->getPost('emp_extname'),
                    'emp_fullname' => $emp_fullname,
                    'emp_fullname2' => $emp_fullname2,
                    'emp_email_official' => $this->request->getPost('emp_email_official'),
                    'emp_email_personal' => $this->request->getPost('emp_email_personal'),
                                        
                    'emp_date_updated' => date('Y-m-d H:i:s'),
                    'emp_updated_by' => session()->get('userid'),
                );
				
                if($this->request->getPost('emp_class')==2 && $this->request->getPost('with_assignment')==true){
                    $data += array(
                        'emp_office' => $this->request->getPost('emp_office')!='no' ? $this->request->getPost('emp_office') : null,
                        'emp_division' => $this->request->getPost('emp_division')!='no' ? $this->request->getPost('emp_division') : null,
                        'emp_unit' => $this->request->getPost('emp_unit')!='no' ? $this->request->getPost('emp_unit') : null,
                        'emp_subunit' => $this->request->getPost('emp_subunit')!='no' ? $this->request->getPost('emp_subunit') : null,
                        
                        'faculty_division' => $this->request->getPost('faculty_division')!='no' ? $this->request->getPost('faculty_division') : null,
                        'faculty_unit' => $this->request->getPost('faculty_unit')!='no' ? $this->request->getPost('faculty_unit') : null,
                        'faculty_subunit' => $this->request->getPost('faculty_subunit')!='no' ? $this->request->getPost('faculty_subunit') : null,
                        
                        'with_assignment' => 1,
                    );
                } else if($this->request->getPost('emp_class')==2 && $this->request->getPost('with_assignment')==false){
                    $data += array(
                        'emp_office' => 3,
                        'emp_division' => $this->request->getPost('faculty_division')!='no' ? $this->request->getPost('faculty_division') : null,
                        'emp_unit' => $this->request->getPost('faculty_unit')!='no' ? $this->request->getPost('faculty_unit') : null,
                        'emp_subunit' => $this->request->getPost('faculty_subunit')!='no' ? $this->request->getPost('faculty_subunit') : null,
                        
                        'faculty_division' => $this->request->getPost('faculty_division')!='no' ? $this->request->getPost('faculty_division') : null,
                        'faculty_unit' => $this->request->getPost('faculty_unit')!='no' ? $this->request->getPost('faculty_unit') : null,
                        'faculty_subunit' => $this->request->getPost('faculty_subunit')!='no' ? $this->request->getPost('faculty_subunit') : null,
                        'with_assignment' => 0,
                    );
                } else {
                    $data += array(
                        'emp_office' => $this->request->getPost('emp_office')!='no' ? $this->request->getPost('emp_office') : null,
                        'emp_division' => $this->request->getPost('emp_division')!='no' ? $this->request->getPost('emp_division') : null,
                        'emp_unit' => $this->request->getPost('emp_unit')!='no' ? $this->request->getPost('emp_unit') : null,
                        'emp_subunit' => $this->request->getPost('emp_subunit')!='no' ? $this->request->getPost('emp_subunit') : null,
                    );
                }
                        
                $action = 'FIRST UPDATE';
                $res = $this->db->table('employees')->where('id_employee', $employee_id)->update($data);
                
                $data2 = $data;
				
                if($res){
            
                    $data2 += array(
                        'emp_cpno' => $this->request->getPost('emp_cpno'),
                    );
                
                    // Try to update hrmis database if available
                    try {
                        $res_hrmis = $this->db_hrmis->table('employees')
                                ->where('id_employee', $employee_id)
                                ->update($data2);
                    } catch(\Exception $e) {
                        log_message('warning', 'HRMIS database not available: ' . $e->getMessage());
                        $res_hrmis = true; // Consider it successful to continue
                    }
                    
                    if($res_hrmis){
                        $his_data = array(
                            'history_date'=>date('Y-m-d H:i:s'),
                            'history_name'=>$employee_id,
                            'history_category'=>'PROFILE',
                            'history_remarks'=>'thru e-portal',
                            'history_action'=>$action,
                            'history_user'=>session()->get('userid'),
                            'history_ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'history_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR']),
                        );
                        // Log to logs table in hr_lnd_db
                        try {
                            $this->db->table('logs')->insert([
                                'logs_date' => date('Y-m-d H:i:s'),
                                'logs_user' => session()->get('userid'),
                                'logs_action' => 'PROFILE UPDATE - ' . $action . ' (thru e-portal)',
                                'logs_ip_address' => $_SERVER['REMOTE_ADDR'],
                                'logs_comp' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                            ]);
                        } catch(\Exception $e) {
                            log_message('warning', 'Could not log profile update: ' . $e->getMessage());
                        }
                    }
                    
                    if($this->request->getFile('file_upload')!=''){
                    
                            $esign_res = $this->upload_esignature();
                            
                    }
                    
                    
                    $this->db->table('users')
                            ->where('userid', session()->get('userid'))
                            ->update(array('first_login'=>0));
                    
                    // Log to logs table instead of history
                    try {
                        $this->db->table('logs')->insert([
                            'logs_date' => date('Y-m-d H:i:s'),
                            'logs_user' => session()->get('userid'),
                            'logs_action' => 'PROFILE UPDATE - ' . $action,
                            'logs_ip_address' => $_SERVER['REMOTE_ADDR'],
                            'logs_comp' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                        ]);
                    } catch(\Exception $e) {
                        log_message('error', 'Failed to insert profile update log: ' . $e->getMessage());
                    }
                }
                
                return $res;
        }
        
        function upload_esignature()
        {
                $this->request = \Config\Services::request();
                
                //------start--of----uploading---file------------------
                $filedir = 'public/assets/uploads/esigns/';
                if (!is_dir(FCPATH . $filedir)) {
                    mkdir(FCPATH . $filedir, 0777, true);
                    copy(FCPATH . 'public/assets/index.html', FCPATH . $filedir.'/index.html');
                }

                $file = $this->request->getFile('file_upload');
                //$newName = $file->getRandomName();
                $file_sizemb = $file->getSize('mb');
                $file_oldname = $file->getClientName(); // Get the original file name
                $file_ext = $file->getClientExtension();
                $newname = $this->set_esigname().'.'.$file_ext;
                $file->move(FCPATH . $filedir, $newname);

                //-------end--of---uploading---file------------------


                $data_file = array(
                    'emp_idno' => session()->get('emp_idno'),
                    'employee_id' => session()->get('empid'),
                    'esign_file_name' => $newname,
                    'esign_file_oldname' => $file_oldname,
                    'esign_file_size' => $file_sizemb,
                    'esign_location' => 'eportal',
                    'esign_file_added' => date('Y-m-d H:i:s'),
                    'esign_file_addedby' => session()->get('userid'),
                );
                
                // Try to save to hrmis database if available
                try {
                    $esign_res = $this->db_hrmis->table('employees_esign')->insert($data_file);
                } catch(\Exception $e) {
                    log_message('warning', 'HRMIS database not available for e-signature: ' . $e->getMessage());
                    $esign_res = true; // Consider it successful to continue
                }
                    
                if($esign_res){
                    session()->set('esign_file', $newname);
                }
                            
                return $esign_res;
        }
        
	function reset_password()
	{
                $userid = session()->get('userid');
                $this->request = \Config\Services::request();
                
                $user_data = array(
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                    'password_reset'=> 0,
                    'reset_link'=> null,
                    'reset_date'=> null,
                );
                $res = $this->db->table('users')
                    ->where('userid', $userid) 
                    ->update($user_data);
                
                if($res){
                    
                    $user = $this->get_employee_basic(session()->get('empid'));
                    // Log to logs table instead of history
                    try {
                        $this->db->table('logs')->insert([
                            'logs_date' => date('Y-m-d H:i:s'),
                            'logs_user' => $userid,
                            'logs_action' => 'Update Password',
                            'logs_ip_address' => $_SERVER['REMOTE_ADDR'],
                            'logs_comp' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                        ]);
                    } catch(\Exception $e) {
                        log_message('error', 'Failed to insert password update log: ' . $e->getMessage());
                    }
                }
                
                return $res;
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
        
        
        
        function upload_profile_picture()
        {
                $this->request = \Config\Services::request();
                
                //------start--of----uploading---file------------------
            
                    $filedir = 'public/assets/images/profiles/';
                    if (!is_dir(FCPATH . $filedir)) {
                        mkdir(FCPATH . $filedir, 0777, true);
                        copy(FCPATH . 'public/assets/index.html', FCPATH . $filedir.'index.html');
                    }
                    
                    
                    $file = $this->request->getFile('file_upload');
                    $file_ext = $file->getClientExtension();
					
                    $idno_sub = substr(session()->get('emp_idno'), -5);
                    $newname1 = $this->set_picname($idno_sub);
                    $newname = $newname1.'.'.$file_ext;
                    $res = $file->move(FCPATH . $filedir, $newname);
                    
                //-------end--of---uploading---file------------------

                if($res){
                    
                        //------delete--file----------------------
                        if(session()->get('profile_picture')!=''){
                            $filePath1 = FCPATH . $filedir . session()->get('profile_picture');
                            if (file_exists($filePath1)) {
                                unlink($filePath1);
                            } 
                        }
                        //------delete--file----------------------

                        $filedir_thumb = 'public/assets/images/profiles/thumbs/';
                        if (!is_dir(FCPATH . $filedir_thumb)) {
                            mkdir(FCPATH . $filedir_thumb, 0777, true);
                            copy(FCPATH . 'public/assets/index.html', FCPATH . $filedir_thumb.'index.html');
                        }

                        $newname_thumb = $newname1.'_thumb.'.$file_ext;

                        $image = \Config\Services::image()
                            ->withFile(FCPATH . $filedir . $newname)
                            ->resize(50, 50, true, 'width') // Resize proportionally
                            ->save(FCPATH . $filedir_thumb . $newname_thumb); // Save in thumbnails folder

                        
                        //------delete--file--thumb--------------------
                        if(session()->get('profile_icon')!=''){
                            $filePath2 = FCPATH . $filedir_thumb . session()->get('profile_icon');
                            if (file_exists($filePath2)) {
                                unlink($filePath2);
                            } 
                        }
                        //------delete--file--thumb--------------------

                        $picture_data = array(
                            'profile_picture' => $newname,
                            'profile_picture_icon' => $newname_thumb,
                            'profile_picture_loc' => 'portal',
                            'emp_date_updated' => date('Y-m-d H:i:s'),
                            'emp_updated_by' => session()->get('userid'),
                        );

                        $this->db->table('employees')
                                ->where('id_employee', session()->get('empid'))
                                ->update($picture_data);
								
                        // Try to update hrmis database if available
                        try {
                            $this->db_hrmis->table('employees')
                                    ->where('id_employee', session()->get('empid'))
                                    ->update($picture_data);
                        } catch(\Exception $e) {
                            log_message('warning', 'HRMIS database not available for profile picture: ' . $e->getMessage());
                        }

                            session()->remove('profile_picture');
                            session()->set('profile_picture', $newname);
                            session()->remove('profile_icon');
                            session()->set('profile_icon', $newname_thumb);
                            session()->remove('profile_loc');
                            session()->set('profile_loc', 'portal');
							
							
						// Log to logs table instead of history
						try {
							$this->db->table('logs')->insert([
								'logs_date' => date('Y-m-d H:i:s'),
								'logs_user' => session()->get('userid'),
								'logs_action' => 'Update Profile Picture',
								'logs_ip_address' => $_SERVER['REMOTE_ADDR'],
								'logs_comp' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
							]);
						} catch(\Exception $e) {
							log_message('error', 'Failed to insert profile picture update log: ' . $e->getMessage());
						}
                }


                return $res;
        }
        
        
        function set_picname($idno,$length=6)
        {
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $link_pass = $idno.'-'.substr(str_shuffle($chars),0,$length);

                if(count($this->check_picname_exist($link_pass))>0){
                    $link_pass2 = $this->set_picname($length);
                    return $link_pass2;
                } else return $link_pass;
        }
        
        function check_picname_exist($link_pass)
        {
                $query = $this->db->query("SELECT * FROM employees
                                                WHERE profile_picture='".$link_pass."' ");
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
                    'emp_office' => $this->request->getPost('emp_office'),
                    'emp_division' => $this->request->getPost('emp_division')!='no' ? $this->request->getPost('emp_division') : null,
                    'emp_unit' => $this->request->getPost('emp_unit')!='no' ? $this->request->getPost('emp_unit') : null,
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
                    
                    // Log to logs table instead of history
                    try {
                        $this->db->table('logs')->insert([
                            'logs_date' => date('Y-m-d H:i:s'),
                            'logs_user' => session()->get('userid'),
                            'logs_action' => 'EMPLOYEES - ' . $action,
                            'logs_ip_address' => $_SERVER['REMOTE_ADDR'],
                            'logs_comp' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                        ]);
                    } catch(\Exception $e) {
                        log_message('error', 'Failed to insert employee log: ' . $e->getMessage());
                    }
                }
                
                return $res;
        }
        
        
        function get_employee_personal()
        {            
                $query = $this->db->query("SELECT *
                                                FROM employees
                                                LEFT JOIN lib_offices ON id_office=emp_office
                                                LEFT JOIN lib_divisions ON id_division=emp_division
                                                LEFT JOIN lib_units ON id_unit=emp_unit
                                                WHERE id_employee=".session()->get('empid'));
                $result = $query->getResult();
                
                return $result;
        }

        
        function get_employee_salary()
        {            
                $query0 = $this->db_hrmis->query("SELECT id_salary_schedule
                                                FROM lib_salary_schedules
                                                WHERE schedule_effectivity<'".date('Y-m-d')."' 
                                                ORDER BY schedule_effectivity DESC
                                                LIMIT 1
                                            ");
                $schedule_id = $query0->getRow()->id_salary_schedule;
                
                $query = $this->db_hrmis->query("SELECT position_name,step_increment,lib_positions.salary_grade,
                                                    sg_sin1,sg_sin2,sg_sin3,sg_sin4,sg_sin5,sg_sin6,sg_sin7,sg_sin8
                                                FROM employees
                                                LEFT JOIN plantilla_items ON id_plantilla_item=plantilla_item_id
                                                LEFT JOIN lib_positions ON position_id=id_position
                                                LEFT JOIN lib_salaries ON (lib_positions.salary_grade=lib_salaries.salary_grade AND salary_schedule_id=".$schedule_id." )
                                                WHERE id_employee=".session()->get('empid'));
                $result = $query->getResult();
                
                foreach($result as $i){
                    $step = 'sg_sin'.$i->step_increment;
                    $i->salary = @$i->$step ? $i->$step : ''; // for testing 12345; //
                    
                }
                
                return $result;
        }
//--------------divider--------------------------------------
//--------------divider--------------------------------------
//--------------divider--------------------------------------
        
        
        
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

