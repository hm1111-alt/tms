<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LoginController extends BaseController
{
        public function __construct()
        {
                
                $this->mdl_login = new \App\Models\preferences\mdl_login();
                
//            
//                // Load the session service
//                $this->session = \Config\Services::session();
//        
//                $this->session->start();
//
//                $this->session->set([
//                    'userid' => '1',
//                    'username' => 'Admin',
//                    'logged_in' => true
//                ]);
        }
        
        
        
        public function index()
        {
//                $data = $this->mdl_login->get_blob('eportal:t85rhmnabbqrhudi8p1582103tr11783');
//                        
//                echo '<pre>';
//                print_r($data);
//                echo '<pre>';
//                die;
            
                if(session()->get('logged_in')==true){
                    return redirect()->to('dashboard');
                }
                
                $validation = $this->_authenticate();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){
                    
                        $data['module_main'] = 'Login';
                        
                        if($this->request->getPost()){
                            \Config\Services::validation();
                            $data['validation'] = $this->validator;
                        }
                
                        return view('login/login',$data); // Display the login form
                    
                } else {
                    
                        $check_user = $this->mdl_login->login_validation();
                        
                        
                        if (password_verify($this->request->getPost('password'), @$check_user[0]->password)) {
                        //if(count($check_user)>0){
                            
                            $employee = $this->mdl_login->get_user_details($check_user[0]->userid);
                            

                            foreach($employee as $row){
                                $is_active = $row->is_active;
                                $register_verified = $row->register_verified;

                                $portalid = ''; //$this->mdl_login->encode($row->employee_idno);
                                //$portalid = $this->mdl_login->encode('20160822-01'); // for testing
                            
                                $session_data = array (
                                        'userid' => $row->userid,
                                        'username' => $row->username,
                                        'password_expired' => $row->password_expired,
                                        'first_name' => $row->emp_fname,
                                        'last_name' => $row->emp_lname,
                                        'ext_name' => @$row->emp_extname,
                                        'emp_nickname' => @$row->emp_nickname,
                                        'empid' => @$row->id_employee,
                                        'emp_idno' => $row->employee_idno,
                                        'profile_picture' => @$row->profile_picture,
                                        'profile_icon' => @$row->profile_picture_icon,
                                        'profile_loc' => @$row->profile_picture_loc,
                                    
                                        'user_type_id' => $row->user_type_id,
                                        'user_type_name' => $row->user_type_name,
                                        'access_level' => $row->user_type_id,
                                        'emp_class' => @$row->emp_class_name,
                                    
                                        'emp_office' => @$row->emp_office,
                                        'emp_division' => @$row->emp_division,
                                        'emp_unit' => @$row->emp_unit,
                                    
                                        'isregular' => 1, // @$row->isregular, -------------update this
                                        'first_login' => $row->first_login,
                                        'password_reset' => $row->password_reset,
                                        'logged_in' => TRUE,
                                        'portalid' => $portalid
                                );
                                
                                if(@$row->esign_file_name!=''){
                                    $session_data += array (
                                        'esign_file' => $row->esign_file_name,
                                    );
                                } 
                                
                                if(@$row->hrmis_access==1){
                                    $session_data += array (
                                        'hrmis_access' => $row->hrmis_access,
                                    );
                                } 
                                
                                if(@$row->emp_fname!=''){
                                    $display_name = $row->emp_fname.' '.$row->emp_lname.' '.$row->emp_extname;
                                } else if(@$row->employee_idno!=''){
                                    $display_name = $row->employee_idno;
                                } else {
                                    $display_name = $row->username;
                                }
                                
                                $session_data += array(
                                    'display_name' => $display_name
                                );
                            }

                            if($register_verified==0){
                                session()->setFlashdata('error','This account is not yet verified.<br>Please check your email to verify.');
                                $this->mdl_login->save_login_attempt($session_data['userid']);
                                return redirect()->back();

                            } else if($is_active==0){
                                session()->setFlashdata('error','This account has been deactivated.<br>Please contact your system administrator.');
                                $this->mdl_login->save_login_attempt($session_data['userid']);
                                return redirect()->back();

                            } else {
                                
                                //session_start();
                                session()->set($session_data);
                                $this->mdl_login->save_login();

                                //$this->load->model('settings/mdl_user_type');
                                //$landing_page = $this->mdl_user_type->get_landing_page();
                                //return redirect()->to($landing_page);
                                return redirect()->to('dashboard');
                            }

                        } else {

                            $userid = $this->mdl_login->userid_login_attempt();
                            if(@$userid) $this->mdl_login->save_login_attempt($userid);

                            session()->setFlashdata('error','Incorrect username or password. Try Again.');                        
                            return redirect()->back();
                        }
                        
                }
        }
        
        private function _authenticate()
        {
                
                $rules = [
                    'username' => [
                        'label' => 'Username',
                        'rules'  => 'required',
                        'errors' => [
                            'required'   => 'The username is required.',
                        ]
                    ],
                    /*'email'    => [
                        'label' => 'Username',
                        'rules'  => 'required|valid_email',
                        'errors' => [
                            'required'    => 'The email is required.',
                            'valid_email' => 'Please enter a valid email address.'
                        ]
                    ],*/
                    'password' => [
                        'rules'  => 'required',
                        'errors' => [
                            'required'   => 'The password is required.',
                            //'min_length' => 'The password must be at least 6 characters long.'
                        ]
                    ]
                ];
                
		return $this->validate($rules);
        }

        public function authenticate()
        {
                $session = session(); // Start the session
                $userModel = new UserModel();

                // Get username and password from POST data
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');

                // Fetch the user from the database
                $user = $userModel->where('username', $username)->first();

                // Validate the user and password
                if ($user && password_verify($password, $user['password'])) {
                    // Set session data
                    $session->set([
                        'user_id'   => $user['id'],
                        'username'  => $user['username'],
                        'logged_in' => true
                    ]);

                    // Redirect to the dashboard
                    return redirect()->to('/dashboard');
                } else {
                    // Set error message and redirect back
                    $session->setFlashdata('error', 'Invalid login credentials.');
                    return redirect()->back();
                }
        }

        public function logout()
        {
                $this->mdl_login->save_logout();
                
                session()->destroy();  // Destroy all session data
                
                return redirect()->to('/login'); // Redirect to login page
        }
        
        private function _validate_registration()
        {
                // Check which form was submitted
                $registration_type = $this->request->getPost('registration_type');
                
                if($registration_type == 'employee') {
                        // Employee registration validation
                        $rules = [
                                'employee_idno' => [
                                        'label' => 'Employee ID no.',
                                        'rules' =>'required|trim',
                                ],
                                'employee_fname' => [
                                        'label' => 'First Name',
                                        'rules' =>'required|trim',
                                ],
                                'employee_lname' => [
                                        'label' => 'Last Name',
                                        'rules' =>'required|trim',
                                ],
                                'employee_mi' => [
                                        'label' => 'Middle Initial',
                                        'rules' =>'permit_empty|trim|max_length[2]',
                                ],
                                'employee_extname' => [
                                        'label' => 'Extension Name',
                                        'rules' =>'permit_empty|trim',
                                ],
                                'email_address' => [
                                        'label' => 'E-mail Address',
                                        'rules' =>'required|trim|valid_email',
                                ],
                                'password' => [
                                        'label' => 'Password',
                                        'rules' =>'required|min_length[12]|max_length[30]',
                                ],
                                'password_confirm' => [
                                        'label' => 'Confirm Password',
                                        'rules' =>'required|matches[password]',
                                ],
                        ];
                } else {
                        // Guest registration validation
                        $rules = [
                                'guest_fname' => [
                                        'label' => 'First Name',
                                        'rules' =>'required|trim',
                                ],
                                'guest_lname' => [
                                        'label' => 'Last Name',
                                        'rules' =>'required|trim',
                                ],
                                'guest_mi' => [
                                        'label' => 'Middle Initial',
                                        'rules' =>'permit_empty|trim|max_length[2]',
                                ],
                                'guest_extname' => [
                                        'label' => 'Extension Name',
                                        'rules' =>'permit_empty|trim',
                                ],
                                'guest_email' => [
                                        'label' => 'E-mail Address',
                                        'rules' =>'required|trim|valid_email',
                                ],
                                'guest_password' => [
                                        'label' => 'Password',
                                        'rules' =>'required|min_length[8]|max_length[30]',
                                ],
                                'guest_password_confirm' => [
                                        'label' => 'Confirm Password',
                                        'rules' =>'required|matches[guest_password]',
                                ],
                        ];
                }
                
                return $this->validate($rules);
        }
        
        private function _validate_reset_pass($pass_length=8)
        {
                
                $rules = [
                    'password' => [
                        'label' => 'Password',
                        'rules' =>'required|min_length['.$pass_length.']|max_length[30]',
                    ],
                    'password_confirm' => [
                        'label' => 'Confirm Password',
                        'rules' =>'required|matches[password]',
                    ],
                ];
                
		return $this->validate($rules);
        }
        
        private function _validate_forgot_pass()
        {
                
                $rules = [
                    'username' => [
                        'label' => 'Employee ID no.',
                        'rules' =>'required|trim',
                    ],
                ];
                
		return $this->validate($rules);
        }
        
        
        public function reset_password($link)
        {
                $check_link = $this->mdl_login->check_link_exist($link);
                
                $now = date('Y-m-d H:i:s');
                $expire = strtotime(@$check_link[0]->reset_date.' +1day');
                
                if(!@$check_link){
                    
                        session()->setFlashdata('error', '<b>Error!</b> Invalid link.');
                        return redirect()->to('login');
                        
                } else if(@$check_link[0]->password_reset==0 || strtotime($now)>$expire){
                    
                        $this->mdl_login->clear_request_reset(@$check_link[0]->userid);
                        
                        session()->setFlashdata('error', '<b>Error!</b> The reset link is no longer valid.<br>
                                    <a class="text-white" href="'.site_url('forgot_password').'">Click here to request a new password reset.</a>');
                        
                        return redirect()->to('login');
                }
                
                $pass_length = 8;
                $validation = $this->_validate_reset_pass($pass_length);
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){
                    
                        $data[] = '';
                        if($this->request->getPost()){
                            \Config\Services::validation();
                            $data['validation'] = $this->validator;
                        }
                
                        return view('login/reset_password',$data); // Display the  form
                    
                } elseif (!$this->mdl_login->valid_password()) {
                    
                        $data['pass_error'] = 'Password should be at least '.$pass_length.' characters in length and should include at least one upper case letter, one number, and one special character.';
                        
                        $data['module'] = '';
                        if($this->request->getPost()){
                            \Config\Services::validation();
                            $data['validation'] = $this->validator;
                        }
                
                        return view('login/reset_password',$data); // Display the login form
                
                } else {
                    
                        $res = $this->mdl_login->reset_password(@$check_link[0]->userid,$link);
                    
                        if($res){
                            session()->setFlashdata('success', '<b>Your password has been successfully updated!</b><br>Login using your new password.');
                            return redirect()->to('login');
                        } else {
                            session()->setFlashdata('error', '<b>Error!</b> Something went wrong while updating password.<br>Please contact please contact your system administrator at <b style="color: blue;">miso@clsu.edu.ph</b>.');
                            return redirect()->to('login');
                        }
                    
                }
        }
        
        
        public function forgot_password()
        {
                $validation = $this->_validate_forgot_pass();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){
                    
                        $data[] = '';
                        if($this->request->getPost()){
                            \Config\Services::validation();
                            $data['validation'] = $this->validator;
                        }
                
                        return view('login/forgot_password',$data); // Display the  form
                    
                } else if(!$this->mdl_login->login_validation()){
                    
                        $data['error'] = 'The ID number or email address you entered does not exist.';
                        
                        if($this->request->getPost()){
                            \Config\Services::validation();
                            $data['validation'] = $this->validator;
                        }
                
                        return view('login/forgot_password',$data); // Display the  form
                } else {
                    
                        $res = $this->mdl_login->request_reset_password();
                        
                        if($res!=false){
                            
                            
                                $user = $this->mdl_login->get_user_details($res);
                            
                                    $name = $this->request->getPost('employee_fname');
                                    $refno = @$user[0]->reset_link;

                                    $data['email_title'] = 'Password Reset Request';

                                    $email_body = "Hi ".@$user[0]->emp_fname.",";
                                    $email_body .= "<br><br>We received a request to reset your password. Click the link below to set a new password";
                                    $email_body .= "<br><br>";
                                    $email_body .= "<a href='".site_url('resetPasword/'.@$refno)."' style='font-weight: bold; font-size: larger; color: blue;' target='_blank'>RESET PASSWORD</a>";
                                    $email_body .= "<br><br><br>or paste the following URL in your browser:";
                                    $email_body .= "<br><br>&emsp;".site_url('resetPasword/'.@$refno)." <br><br>";
                                    $email_body .= "<br><br><em>
                                            If you didn’t request a password reset, you can safely ignore this email.<br>
                                            This link will expire in 1 day for security reasons.</em><br><br>";

                                    $email_body .= '<br><hr><br>This is an auto-generated email from CLSU E-portal. Please do not reply.';

                                    $data['email_body'] = $email_body;

                                    $body = view('login/email_body', $data);

                                    $subject = 'CLSU E-portal | '.$data['email_title'];
                                    $cc = '';
                                    $bcc = '';

                                    $to_email = @$user[0]->user_email; 
                                    $to_name = @$user[0]->emp_fname.' '.@$user[0]->emp_lname.' '.@$user[0]->emp_extname; 

                                    helper('mailer'); // Load URL helper
                                    $mail_res = phpmail('CLSU-MISO-mailer',$to_email,$to_name,$subject,$body, $cc,$bcc);

                                    //echo $mail_res ? 'Email sent' : 'something went wrong';

                                    $masked_email = $this->maskEmail(@$user[0]->user_email) ;

                                    if($mail_res){
                                        session()->setFlashdata('success', '<b>Password reset request successful!</b><br>A reset link has been sent to '.$masked_email.'. Please check your inbox.');
                                        return redirect()->to('login');
                                    } else {
                                        session()->setFlashdata('error', '<b>Error!</b> Something went wrong while sending email notification.<br>Please contact please contact your system administrator at <b style="color: blue;">miso@clsu.edu.ph</b>.');
                                        return redirect()->to('login');
                                    }
                            
                            
                            
                        } else {
                            
                                session()->setFlashdata('error', 'Failed! Something went wrong while requesting to reset password!');
                                return redirect()->to('forgot_password');
                        }
                    
                }
                    
        }
        
        function maskEmail($email) 
        {
            
                list($user, $domain) = explode('@', $email);
                //list($domainName, $domainExt, $domainExt2) = explode('.', $domain);
                $parts = explode('.', $domain);
                $domainName = $parts[0] ?? '';
                $domainExt  = $parts[1] ?? '';
                $domainExt2 = $parts[2] ?? '';

                // Mask username
                $userMasked = '';
                $userLength = strlen($user);
                for ($i = 0; $i < $userLength; $i++) {
                    if ($i === 0 || $i === 1 || $i === 7 || $i === 8 || $i === 12) {
                        $userMasked .= $user[$i];
                    } else {
                        $userMasked .= '*';
                    }
                }

                // Mask domain name (standard 3-letter reveal)
                $domainMasked = substr($domainName, 0, 3) . str_repeat('*', max(0, strlen($domainName) - 3));

                return $userMasked . '@' . $domainMasked . '.' . $domainExt .'.' . $domainExt2;
            
        }
        
        public function register()
        {
                $validation = $this->_validate_registration();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){
                        return view('login/user_registration');
                } else {
                        // Check which registration type
                        $registration_type = $this->request->getPost('registration_type');
                        
                        if($registration_type == 'employee') {
                                // Employee registration
                                $employee_idno = $this->request->getPost('employee_idno');
                                
                                // Check if already registered
                                if($this->mdl_login->check_user_exist()==true){
                                        session()->setFlashdata('error', 'Failed! ID no. or E-mail already registered!');
                                        return redirect()->to('register');
                                }
                                
                                // Validate employee
                                $employee = $this->mdl_login->check_employee_valid();
                                
                                if($employee==false){
                                        session()->setFlashdata('error', '<b>Incorrect employee details!</b> Employee ID not found in database. Please verify your information at the Human Resources Management Office (HRMO).');
                                        return redirect()->to('register');
                                }
                                
                                // Save with employee validation
                                $res = $this->mdl_login->save_registration(@$employee[0]->emp_idno);
                        } else {
                                // Guest registration - simple registration
                                // Check if email already exists
                                if($this->mdl_login->check_email_exists($this->request->getPost('guest_email'))){
                                        session()->setFlashdata('error', 'Failed! Email address already registered!');
                                        return redirect()->to('register');
                                }
                                
                                // Save simple registration
                                $res = $this->mdl_login->save_registration_simple();
                        }
                                
                        if($res==false){
                                session()->setFlashdata('error', '<b>Error!</b> Something went wrong while saving registration. Please contact <b style="color: blue;">miso@clsu.edu.ph</b>.');
                                return redirect()->to('register');
                        } else {
                                $registration_type = $this->request->getPost('registration_type');
                                $refno = $res;
                                
                                if($registration_type == 'employee') {
                                        // Employee registration
                                        $name = $this->request->getPost('employee_fname');
                                        $employee_idno = $this->request->getPost('employee_idno');
                                        
                                        // TEMPORARILY DISABLED - Email verification not required yet
                                        // Skip email verification, allow immediate login
                                        session()->setFlashdata('success', '<b>Account registration successful!</b><br>You can now login to continue.');
                                        return redirect()->to('login');
                                } else {
                                        // Guest registration
                                        $name = $this->request->getPost('guest_fname');
                                        
                                        session()->setFlashdata('success', '<b>Account registration successful!</b><br>You can now login to continue.');
                                        return redirect()->to('login');
                                }
                        }
                }
        }
        
        
        public function privacy()
        {
            return view('login/data-privacy-policy'); 
        }
        
        function verify_account($refno)
        {
                $account = $this->mdl_login->check_register_refno($refno);
                
                if($account){
                    
                    $res = $this->mdl_login->verify_registration(@$account[0]->userid);
                    
                    if($res==true){
                        
                            $account = $this->mdl_login->get_user_details(@$account[0]->userid);

                            $name = @$account[0]->emp_fname;
                
                            $data['email_title'] = 'Account Registration';
                            
                            $email_body = "Hi ".$name.",";
                            $email_body .= "<br><br>You CLSU E-portal account has been successfuly verified.";
                            $email_body .= "<br><br>";
                            $email_body .= "<a href='".site_url('login')."' style='font-weight: bold; font-size: larger; color: blue;' target='_blank'>CLICK HERE TO LOGIN</a>";
                            $email_body .= "<br><br><br>";
                            $email_body .= '<br><hr><br>This is an auto-generated email from CLSU E-portal. Please do not reply.';
                            $data['email_body'] = $email_body;

                            //$body = view('email_body', $data);

                            $body = view('login/email_body', $data);

                            $subject = 'CLSU E-portal | Account Registration';
                            $cc = '';
                            $bcc = '';


                            $to_email = @$account[0]->user_email; // 'benj0143@yahoo.com'; // for testing //
                            $to_name = @$account[0]->emp_fname.' '.@$account[0]->emp_lname.' '.@$account[0]->emp_extname; 

                            helper('mailer'); // Load URL helper
                            $mail_res = phpmail('CLSU-MISO-mailer',$to_email,$to_name,$subject,$body, $cc,$bcc);

                            //echo $mail_res ? 'Email sent' : 'something went wrong';

                            if($mail_res){
                                session()->setFlashdata('success', '<b>Account verification successful!</b><br>You may now login to continue.<br><em>Email notification sent</em>');
                                return redirect()->to('login');
                            } else {
                                session()->setFlashdata('success', '<b>Account verification successful!</b><br>You may now login to continue.<br><em>Email notification not sent</em>');
                                return redirect()->to('login');
                            }
                                            
                                            
                        
                    } else {
                        
                            session()->setFlashdata('error', '<b>Error!</b> Something went wrong while verifying your E-portal account.');
                            return redirect()->to('login');
                        
                    }
                } else {
                    
                    session()->setFlashdata('error', '<b>Error!</b> Invalid reference number.');
                    return redirect()->to('register');
                    
                }
                
        }
        
}
