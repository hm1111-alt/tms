<?php

namespace App\Models\preferences;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Login extends Model
{
        protected $table = 'users';
        
        function __construct(){
                $this->db = Database::connect(); // default
                // Connect to another database
                $this->db_hrmis = Database::connect('hrmis');
        }
        
        
        function login_validation()
        {
                $this->request = \Config\Services::request();
                $query = $this->db->query("SELECT userid,password,user_email
                                            FROM users
                                            LEFT JOIN user_types ON id_user_type=user_type_id
                                            WHERE binary username ='" . esc($this->request->getPost('username')) . "' 
                                                OR user_email ='" . $this->request->getPost('username') . "' 
                                                 ");
		return $query->getResult();
        }
        
        function get_user_details($userid)
        {
                $query = $this->db->query("SELECT is_active,userid,username,password_expired,employee_idno,user_email,
                                                user_type_id,register_verified,first_login, password_reset,reset_link,
                                                access_id,hrmis_access,
                                                level_id, id_employee, emp_idno, emp_fname, emp_lname, emp_mi, emp_extname,emp_nickname,
                                                profile_picture,profile_picture_icon,profile_picture_loc, emp_position_name position_name, emp_class_name,
                                                emp_is_active, register_verified, user_email,
                                                user_type_id, user_type_name, 
                                                emp_office,emp_division,emp_unit
                                            FROM users
                                            LEFT JOIN user_types ON id_user_type=user_type_id
                                            LEFT JOIN employees ON employees.emp_idno=employee_idno
                                            WHERE userid =" . $userid . " ");
		$result = $query->getResult();
                
		$query2 = $this->db_hrmis->query("SELECT esign_file_name,esign_location
                                            FROM employees_esign 
                                            WHERE employee_id =" . @$result[0]->id_employee . "
                                            ");
                $result2 = $query2->getResult();
                $result[0]->esign_file_name = @$result2[0]->esign_file_name;
                $result[0]->esign_location = @$result2[0]->esign_location;
                
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
        
        
        function randomPassword($len = 8) {

                //enforce min length 8
                if($len < 8)
                    $len = 8;

                //define character libraries - remove ambiguous characters like iIl|1 0oO
                $sets = array();
                $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
                $sets[] = 'abcdefghjkmnpqrstuvwxyz';
                $sets[] = '0123456789';
                //$sets[]  = '!@#$%^&*()\-_=+{};:,<.>~';
                $sets[]  = '-_.~';

                $password = '';

                //append a character from each set - gets first 4 characters
                foreach ($sets as $set) {
                    $password .= $set[array_rand(str_split($set))];
                }

                //use all characters to fill up to $len
                while(strlen($password) < $len) {
                    //get a random set
                    $randomSet = $sets[array_rand($sets)];

                    //add a random char from the random set
                    $password .= $randomSet[array_rand(str_split($randomSet))]; 
                }

                //shuffle the password string before returning!
                $link_pass = str_shuffle($password);
        
                if(count($this->check_link_exist($link_pass))>0){
                    $link_pass2 = $this->randomPassword(25);
                    return $link_pass2;
                } else {
                    return $link_pass;
                }
        }
        
        function check_link_exist($link_pass)
        {
                    $query = $this->db->query("SELECT userid,password_reset,reset_link,reset_date FROM users
                                                    WHERE reset_link='".$link_pass."' ");
                    return $query->getResult();
            
        }
        
        function clear_request_reset($userid)
        {
                $data = array(
                    'password_reset'=> 0,
                    'reset_link'=> null,
                    'reset_date'=> null,
                );
                $this->db->table('users')
                    ->where('userid', $userid) 
                    ->update($data);
        }
        
        function request_reset_password()
        {
                $user = $this->login_validation();
                $link = $this->randomPassword(25);
                
                $data = array(
                    'password_reset'=> 1,
                    'reset_link'=> $link,
                    'reset_date'=>date('Y-m-d H:i:s'),
                );
                $res = $this->db->table('users')
                    ->where('userid', @$user[0]->userid) 
                    ->update($data);
                         
                if($res){
                    $data2 = array(
                        'logs_date'=>date('Y-m-d H:i:s'),
                        'logs_user'=> @$user[0]->userid,
                        'logs_action'=>'FORGOT PASSWORD',
                        'logs_ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'logs_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR'])
                    );
                    $this->db->table('logs')->insert($data2);
                    
                    return @$user[0]->userid;
                } else {
                    return false;
                }
        }
        
        function save_login()
        {
                $data = array(
                    'last_login'=>date('Y-m-d H:i:s')
                );
                $this->db->table('users')
                    ->where('userid', session()->get('userid')) 
                    ->update($data);
                                    
                $data2 = array(
                    'logs_date'=>date('Y-m-d H:i:s'),
                    'logs_user'=>session()->get('userid'),
                    'logs_action'=>'LOGIN',
                    'logs_ip_address'=>$_SERVER['REMOTE_ADDR'],
                    'logs_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR'])
                );
                $this->db->table('logs')->insert($data2);
        }
	
	function userid_login_attempt()
	{
                $this->request = \Config\Services::request();
		$user = $this->request->getPost('username');
		$query = $this->db->query("SELECT userid from users where binary username ='" . esc($user) . "'");
		return $query->getRow('userid');
	}
        
        function get_blob($session_id)
        {
		$query = $this->db->query("SELECT * from ci_sessions where id ='" . $session_id . "'");
		return $query->getResult();
	}
        
        function save_login_attempt($userid)
        {
                $data2 = array(
                    'logs_date'=>date('Y-m-d H:i:s'),
                    'logs_user'=>$userid,
                    'logs_action'=>'LOGIN ATTEMPT',
                    'logs_ip_address'=>$_SERVER['REMOTE_ADDR'],
                    'logs_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR'])
                );
                $this->db->table('logs')->insert($data2);
        }
        
        function save_logout()
        {
                $data2 = array(
                    'logs_date'=>date('Y-m-d H:i:s'),
                    'logs_user'=>session()->get('userid'),
                    'logs_action'=>'LOGOUT',
                    'logs_ip_address'=>$_SERVER['REMOTE_ADDR'],
                    'logs_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR'])
                );
                $this->db->table('logs')->insert($data2);
                
                $this->db->table('ci_sessions')->where('id', session_id())->delete();
        }
        
        function save_password($password)
        {
                $data = array(
                    'password' => md5($password),
                    'password_expired' => 0,
                    'date_updated' => date('Y-m-d H:i:s')
                );
                $this->db->table('users')
                    ->where('userid', session()->get('userid')) 
                    ->update($data);
            

                $his_data = array(
                    'history_date'=>date('Y-m-d H:i:s'),
                    'history_name'=>session()->get('userid'),
                    'history_category'=>'ACCOUNTS',
                    'history_action'=>'UPDATE',
                    'history_user'=>session()->get('userid'),
                    'history_ip_address'=>$_SERVER['REMOTE_ADDR'],
                    'history_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR']),
                );
                $this->db->table('history')->insert($his_data);
        }
        
    //-----account--registration--start
        
	
	function check_user_exist()
	{
                $this->request = \Config\Services::request();
		$idno = $this->request->getPost('employee_idno');
		$query = $this->db->query("SELECT employee_idno from users where binary employee_idno ='" . esc($idno) . "' OR  binary user_email ='" . esc($this->request->getPost('email_address')) . "'");
//		return false; // for testing
                return $query->getResult() ? true : false;
	}
	
	function check_employee_valid()
	{
                $this->request = \Config\Services::request();
		$query = $this->db_hrmis->query("SELECT emp_idno,emp_fname,emp_lname,emp_mi,emp_extname,emp_is_active 
                                            FROM employees 
                                            WHERE emp_idno ='" . esc($this->request->getPost('employee_idno')) . "'
                                                AND emp_fname ='" . esc($this->request->getPost('employee_fname')) . "'
                                                AND emp_lname ='" . esc($this->request->getPost('employee_lname')) . "'
                                            ");
		return $query->getResult() ? $query->getResult() : false;
	}
        
        
        
	//Create strong password 
	public function valid_password($password1 = '')
	{
                $this->request = \Config\Services::request();
                
		$password = trim($this->request->getPost('password'));

		$regex_lowercase = '/[a-z]/';
		$regex_uppercase = '/[A-Z]/';
		$regex_number = '/[0-9]/';
		$regex_special = '/[!@#$%^&*()\-_=+{};:,<.>~]/';

			//$this->form_validation->set_message('valid_password', 'The  field must be at least one lowercase letter.');
			//return FALSE;
                        
		if (preg_match_all($regex_lowercase, $password) < 1)
		{
			//$this->form_validation->set_message('valid_password', 'The  field must be at least one lowercase letter.');

			return FALSE;
		}

		if (preg_match_all($regex_uppercase, $password) < 1)
		{
			//$this->form_validation->set_message('valid_password', 'The field must be at least one uppercase letter.');
			return FALSE;
		}

		if (preg_match_all($regex_number, $password) < 1)
		{
			//$this->form_validation->set_message('valid_password', 'The  field must have at least one number.');
			return FALSE;
		}

		if (preg_match_all($regex_special, $password) < 1)
		{
			//$this->form_validation->set_message('valid_password', 'The  field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>ยง~'));
			return FALSE;
		}

		/*if (strlen($password) < 5)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field must be at least 5 characters in length.');

			return FALSE;
		}

		if (strlen($password) > 32)
		{
			$this->form_validation->set_message('valid_password', 'The {field} field cannot exceed 32 characters in length.');

			return FALSE;
		}*/

		return TRUE;
	}

        
	
	function reset_password($userid,$link)
	{
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
                
                $res_lbl = $res ? '' : ' (failed)';
                
                $user = $this->get_user_details($userid);
                $his_data = array(
                    'history_date'=>date('Y-m-d H:i:s'),
                    'history_name'=>$userid,
                    'history_category'=>'ACCOUNT',
                    'history_action'=> 'Update Password'.$res_lbl,
                    'history_remarks'=> @$user[0]->emp_fname.' '.@$user[0]->emp_lname.' '.@$user[0]->emp_extname.', Link = '.$link,
                    'history_user'=> null,
                    'history_ip_address'=>$_SERVER['REMOTE_ADDR'],
                    'history_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR']),
                );
                $this->db->table('history')->insert($his_data);
                
                return $res;
	}

        
	
	function save_registration($emp_idno)
	{
                $this->request = \Config\Services::request();
                
                $this->db_hrmis = Database::connect('hrmis');
		$query = $this->db_hrmis->query("SELECT id_employee,emp_idno,emp_fname,emp_lname,emp_mi,emp_extname, emp_fullname,emp_fullname2,emp_sex,emp_prefix,
                                                emp_email_official, emp_email_personal, emp_status2,emp_class,class_name,
                                                emp_is_active, position_name,position_abbr 
                                            FROM employees 
                                            LEFT JOIN lib_positions ON emp_position=id_position
                                            LEFT JOIN lib_class ON emp_class=id_class
                                            WHERE emp_idno ='" . $emp_idno . "'
                                            ");
		$result = $query->getResult();
                
                
                $refno = $this->set_register_refno();
                
                foreach($result as $emp){
                    
                    
                    $query2 = $this->db->query("SELECT *
                                                FROM employees 
                                                WHERE emp_idno ='" . $emp_idno . "'
                                                ");
                    $exist = $query2->getResult();
                    
                    if(!@$exist){
                        $employee_data = array(
                            'emp_idno' => $emp_idno,
                            'id_employee' => @$emp->id_employee,
                            'emp_fname' => @$emp->emp_fname,
                            'emp_lname' => @$emp->emp_lname,
                            'emp_mi' => @$emp->emp_mi,
                            'emp_extname' => @$emp->emp_extname,
                            'emp_fullname' => @$emp->emp_fullname,
                            'emp_fullname2' => @$emp->emp_fullname2,
                            'emp_sex' => @$emp->emp_sex,
                            'emp_email_official' => @$emp->emp_email_official,
                            'emp_email_personal' => @$emp->emp_email_personal!='' ? @$emp->emp_email_personal : esc($this->request->getPost('email_address')),
                            'emp_class_name' => @$emp->emp_class!='' ? @$emp->class_name : null,
                            'emp_status2' => @$emp->emp_status2!='' ? @$emp->emp_status2 : null,
                            'emp_position_name' => @$emp->position_name,
                            'emp_position_abbr' => @$emp->position_abbr,
                        );
                        $res = $this->db->table('employees')->insert($employee_data);
                    }
                    
                    if(@$res || @$exist){
                        $user_data = array(
                            'username' => $emp_idno,
                            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
                            'employee_idno' => $emp_idno,
                            'user_email' => esc($this->request->getPost('email_address')),
                            'date_created' => date('Y-m-d H:i:s'),
                            'is_active' => 0,
                            'register_refno' => $refno,
                            'register_verified' => 0,
                        );
                        $res2 = $this->db->table('users')->insert($user_data);
                    }
                    
                    $his_data = array(
                        'history_date'=>date('Y-m-d H:i:s'),
                        'history_name'=>$emp_idno,
                        'history_category'=>'ACCOUNT',
                        'history_action'=> 'Registration',
                        'history_remarks'=> $this->request->getPost('employee_fname').' '.$this->request->getPost('employee_lname').' - '.$this->request->getPost('email_address').' refno - '.$refno,
                        'history_user'=> null,
                        'history_ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'history_comp'=>gethostbyaddr($_SERVER['REMOTE_ADDR']),
                    );
                    $this->db_hrmis->table('history')->insert($his_data);
                    
                    return @$res2==true ? $refno : false;
                    
                }
                    
	}
        
        function set_register_refno($length=10)
        {
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $refno = 'REG-'.substr(str_shuffle($chars),0,$length);

                if(count($this->check_register_refno($refno))>0){
                    $refno2 = $this->set_register_refno();
                    return $refno2;
                } else {
                    return $refno;
                }
        }
        
        function check_register_refno($refno)
        {
                $query = $this->db->query("SELECT userid,employee_idno,user_email FROM users
                                                WHERE register_refno='".$refno."'
                                                ");
                return $query->getResult();
        }
        
//        function get_user_byrefno($refno)
//        {
//                $query = $this->db->query("SELECT employee_idno,user_email, emp_fname
//                                                FROM users
//                                                LEFT JOIN employees ON employee_idno=emp_idno
//                                                WHERE register_refno='".$refno."'
//                                                ");
//                return $query->getResult();
//        }
        
        function verify_registration($userid)
        {
                $user_data = array(
                    //'register_refno' => '',
                    'register_verified' => 1,
                    'date_updated' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'first_login' => 1,
                );
                $res = $this->db->table('users')
                    ->where('userid', $userid) 
                    ->update($user_data);
                        
                return $res;
                
        }
        
    //-----account--registration--end
        
}

