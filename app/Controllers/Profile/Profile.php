<?php

namespace App\Controllers\Profile;

use App\Controllers\BaseController;

class Profile extends BaseController
{
        public function __construct()
        {
                $this->mdl_profile = new \App\Models\mdl_profile();
                $this->mdl_setting = new \App\Models\preferences\mdl_setting();
                $this->class_name = basename(str_replace('\\', '/', get_class($this)));
                
        }

        public function index()
        {
                $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                $data['class_name'] = $this->class_name;
                
//                echo $this->class_name;
//                echo '<pre>';
//                print_r($data['page']);
//                echo '<pre>';
//                die;
                //$data['module_sub2'] = 'Edit Profile';
                
                $employee_id = session()->get('empid');
                $data['basic'] = $this->mdl_profile->get_employee_basic($employee_id);
                $data['pages'] = $this->mdl_profile->get_profile_pages();
                return view('profile/myprofile',$data);
                
        }
  
        function first_update()
	{
                $employee_id = session()->get('empid');
                
                $validation = $this->_validate_first_update();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){

                        if($this->request->getPost()){
                            \Config\Services::validation();
                            $data['validation'] = $this->validator;
                        }

                        $this->mdl_menu = new \App\Models\mdl_menu();

                        $basic = $this->mdl_profile->get_employee_basic($employee_id);
                        $data['basic'] = $basic;

                        $data['lib_offices'] = $this->mdl_menu->get_lib_offices();
						$data['ovpaa_offices'] = $this->mdl_menu->get_lib_offices(3);
                        
                        /*if(@$basic[0]->emp_office!=''){
                            $data['divisions'] = $this->mdl_menu->get_divisions_menu(@$basic[0]->emp_office);
                        }
                        if(@$basic[0]->emp_division!=''){
                            $data['units'] = $this->mdl_menu->get_units_menu(@$basic[0]->emp_division);
                        }
                         */
                        //$data['subunits'] = $this->mdl_menu->get_subunits_menu();
                        
                        $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                        $data['module_name'] = $data['page']->page_name;
                        
                        $data['warning'] = 'Note: This is your first login. Update your details to continue using the system.';
                        
                        return view('profile/first_update_form',$data);

                } else {

                        $res = $this->mdl_profile->save_first_update();

                        if($res){
                            session()->remove('first_login');
                            session()->set('first_login', 0);
                    
                            session()->setFlashdata('success', 'Profile successfully updated.');
                        } else {
                            session()->setFlashdata('error', 'Something went wrong while updating profile.');
                        }

                        return redirect()->to('dashboard');

                }
                        
        }
        
        
        private function _validate_first_update()
        {
                
                $rules = [
                    'emp_mi' => [
                        'label' => 'Middle Initial',
                        'rules' =>'trim',
                    ],
                    'emp_email_personal' => [
                        'label' => 'Personal Email',
                        'rules' => 'trim',
                    ],
                    'emp_email_official' => [
                        'label' => 'Official Email',
                        'rules' => 'required|trim',
                    ],
                    'emp_cpno' => [
                        'label' => 'Contact no.',
                        'rules' =>'trim',
                    ],
                    
                    
//                    'file_upload' => [
//                        'label' => 'File',
//                        'rules' => 'uploaded[file_upload]|max_size[file_upload,3072]|ext_in[file_upload,jpg,jpeg,png]',
//                    ],
                ];
				
                if($this->request->getPost('emp_class')==2){
                    
                    $rules += [
                        'with_assignment' => [
                            'label' => 'With Office assignment',
                            'rules' =>'trim',
                        ]
                    ];
                }
				
                if($this->request->getPost('emp_class')==2 && $this->request->getPost('with_assignment')==true ){ 
                    
                    $rules += [

                        'emp_office' => [
                            'label' => 'Office','rules' => 'required|trim',
                        ],
                        'emp_division' => [
                            'label' => 'Division', 'rules' => 'required|trim',
                        ],
                        'emp_unit' => [
                            'label' => 'Unit', 'rules' => 'required|trim',
                        ],
                        'emp_subunit' => [
                            'label' => 'Sub-unit', 'rules' => 'trim',
                        ],

                        'faculty_division' => [
                            'label' => 'College', 'rules' => 'required|trim',
                        ],
                        'faculty_unit' => [
                            'label' => 'Department', 'rules' => 'required|trim',
                        ],
                        'faculty_subunit' => [
                            'label' => 'Sub-unit', 'rules' => 'trim',
                        ],
                    ];
                    
                } else if($this->request->getPost('emp_class')==2 && $this->request->getPost('with_assignment')==false ){ 
                    
                    $rules += [

                        'faculty_division' => [
                            'label' => 'College', 'rules' => 'required|trim',
                        ],
                        'faculty_unit' => [
                            'label' => 'Department', 'rules' => 'required|trim',
                        ],
                        'faculty_subunit' => [
                            'label' => 'Sub-unit', 'rules' => 'trim',
                        ],
                    ];
                    
                } else {
                    
                    $rules += [

                        'emp_office' => [
                            'label' => 'Office','rules' => 'required|trim',
                        ],
                        'emp_division' => [
                            'label' => 'Division', 'rules' => 'required|trim',
                        ],
                        'emp_unit' => [
                            'label' => 'Unit', 'rules' => 'required|trim',
                        ],
                        'emp_subunit' => [
                            'label' => 'Sub-unit', 'rules' => 'trim',
                        ],

                    ];
                }
				
                
		return $this->validate($rules);
        }
        
        private function _validate_picture()
        {
                $rules = [
                    'file_upload' => [
                        'label' => 'File',
                        'rules' => 'uploaded[file_upload]|max_size[file_upload,3072]|ext_in[file_upload,jpg,jpeg,png]',
                    ],
                ];
                
		return $this->validate($rules);
        }
        
        function picture_update()
        {
                
                $page_to = $this->request->getPost('page_to');
                
                $validation = $this->_validate_picture();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){

                        
                        session()->setFlashdata('error', 'Something went wrong while updating profile. '.$this->validator->getError('file_upload'));

                        

                } else {

                        $res = $this->mdl_profile->upload_profile_picture();

                        if($res){
                            session()->setFlashdata('success', 'Profile successfully updated.');
                        } else {
                            session()->setFlashdata('error', 'Something went wrong while updating profile.');
                        }

                }
                
                if($page_to=='Dashboard'){
                    return redirect()->to('dashboard');
                } else {
                    return redirect()->to('myprofile');
                }
                
        }
        
        
        
        private function _validate_password()
        {
                
                $rules = [
                    'password' => [
                        'label' => 'Password',
                        'rules' =>'required|min_length[8]|max_length[30]',
                    ],
                    'password_confirm' => [
                        'label' => 'Confirm Password',
                        'rules' =>'required|matches[password]',
                    ],
                ];
                
		return $this->validate($rules);
        }
        
        function update_reset_password()
	{
                $validation = $this->_validate_password();
                
                $mdl_login = model('App\Models\Preferences\mdl_login');
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){

                        if($this->request->getPost()){
                            \Config\Services::validation();
                            //$data['validation'] = $this->validator;
                            $validation = $this->validator;
                        }
                        if($validation->hasError('password')){
                            session()->setFlashdata('pass_error', $validation->getError('password'));
                        }
                        if($validation->hasError('password_confirm')){
                            session()->setFlashdata('pass_confirm_error', $validation->getError('password_confirm'));
                        }
                        //return redirect()->to('dashboard');

                } else if (!$mdl_login->valid_password()) {
                    
                        session()->setFlashdata('pass_error', 'Password should be at least 6 characters in length and should include at least one upper case letter, one number, and one special character.');
                        session()->setFlashdata('pass_error2', '1');
                        //return redirect()->to('dashboard');
                        
                } else {

                        $res = $this->mdl_profile->reset_password();

                        if($res){
                            session()->remove('password_reset');
                            session()->setFlashdata('success', 'Password successfully updated.');
                            //return redirect()->to('dashboard');
                        } else {
                            session()->setFlashdata('update_error', 'Something went wrong while updating password.');
                            //return redirect()->to('dashboard');
                        }

                }
                
                if(session()->get('password_reset')==1){
                    return redirect()->to('dashboard');
                } else {
                    return redirect()->to('myprofile');
                }
                        
        }
        
        
        
        
        
        function _form($employee_id)
        {
                if($this->request->getPost()){
                    \Config\Services::validation();
                    $data['validation'] = $this->validator;
                }
                
                $this->mdl_menu = new \App\Models\mdl_menu();
            
                $data['status'] = $this->mdl_menu->get_status_menu();
                $data['units'] = $this->mdl_menu->get_units_menu();
                $data['divisions'] = $this->mdl_menu->get_divisions_menu();
                $data['offices'] = $this->mdl_menu->get_offices_menu();
//                $data['programs'] = $this->mdl_menu->get_programs_menu();
                
//                $data['positions'] = $this->mdl_menu->get_positions_menu(1);
                
//                $data['civil_status'] = $this->mdl_menu->get_civil_status_menu();
                
                        
                
                if($employee_id!=""){
                    $basic = $this->mdl_profile->get_employee_basic($employee_id);
                    $data['basic'] = $basic;
                    
//                    $personal = $this->mdl_profile->get_employee_personal($employee_id);
//                    $data['personal'] = $personal;
                }
                        
                $empname = @$basic[0]->emp_fname.' '.@$basic[0]->emp_lname.' '.@$basic[0]->emp_extname;
                
                $data['module_main'] = 'HR Records';
                $data['module_sub'] = 'Employees';
                $data['module_name'] = 'Employees';
                $data['module_sub2'] = $empname.' (edit)';
                
                
                return view('profile/employees_edit',$data);
        }
        
        function load_employee_details()
        {
                $details = $this->mdl_profile->get_employee_details($_POST['emp_idno']);
                echo json_encode($details);
        }
        
        
        
        function tab_personal()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                $data['personal'] = $this->mdl_profile->get_employee_personal();
                return view('profile/tab_personal',$data);
        }
        
        function tab_family()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_family',$data);
        }
        
        function tab_educational()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_educational',$data);
        }
        
        function tab_eligibility()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_eligibility',$data);
        }
        
        function tab_employment()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_employment',$data);
        }
        
        function tab_voluntarywork()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_voluntarywork',$data);
        }
        
        function tab_skills()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_skills',$data);
        }
        
        function tab_recognitions()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_recognitions',$data);
        }
        
        function tab_organizations()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_organizations',$data);
        }
        
        function tab_others()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_others',$data);
        }
        
        function tab_references()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_references',$data);
        }
        
        function tab_pdsinfo()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_pdsinfo',$data);
        }

        function tab_trainings()
        {
                $data['page'] = $this->mdl_profile->get_profile_pages($_POST['page_id']);
                return view('profile/tab_trainings',$data);
        }

        

        
//-----------------------------divider----------------------------------
//-----------------------------divider----------------------------------
//-----------------------------divider----------------------------------
        
        
        function view($employee_id)
        {
                
                $basic = $this->mdl_profile->get_employee_basic($employee_id);
                $data['basic'] = $basic;
                
                
                $data['module_main'] = 'HR Records';
                $data['module_sub'] = 'Employees';
                $data['module_name'] = 'Employees';
                $empname = @$basic[0]->emp_fname.' '.@$basic[0]->emp_lname.' '.@$basic[0]->emp_extname;
                $data['module_sub2'] = 'Application for Leave - '.$empname;
                
                return view('records/employees/employees_view',$data);
        }
        
        
        
        function edit($employee_id,$to=1)
	{                
                
                $validation = $this->_validate_employee();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){

                        return $this->_form($employee_id);

                } else {

                        $res = $this->mdl_profile->save_employee($employee_id);

                        if($res){
                            session()->setFlashdata('success', 'Employee successfully updated.');
                        } else {
                            session()->setFlashdata('error', 'Something went wrong while updating Employee.');
                        }
                        

                        if($to==1){
                            return redirect()->to('employees/view/'.$employee_id);
                        } elseif ($to==2) {                            
                            return redirect()->to('employees/edit/'.$employee_id);
                        }

                }
                        
        }
        
        private function _validate_employee()
        {
            
                    $rules = [
                        'emp_idno' => [
                            'label' => 'Employee',
                            'rules' =>'required|trim',
                        ],
                        'employee_id' => [
                            'label' => 'Employee',
                            'rules' =>'trim',
                        ],

                        'emp_lname' => [
                            'label' => 'Last Name',
                            'rules' => 'required|trim',
                        ],
                        'emp_fname' => [
                            'label' => 'First name',
                            'rules' => 'required|trim',
                        ],
                        'emp_mname' => [
                            'label' => 'Middle name',
                            'rules' => 'trim',
                        ],
                        'emp_mi' => [
                            'label' => 'Middle Initial',
                            'rules' => 'trim',
                        ],
                        'emp_extname' => [
                            'label' => 'Name Extension',
                            'rules' => 'trim',
                        ],
                        'emp_extname' => [
                            'label' => 'Name Extension',
                            'rules' => 'trim',
                        ],
                        'emp_sex' => [
                            'label' => 'Sex',
                            'rules' => 'required|trim',
                        ],
                        'emp_email_personal' => [
                            'label' => 'Personal Email',
                            'rules' => 'trim',
                        ],
                        'emp_email_official' => [
                            'label' => 'Official Email',
                            'rules' => 'required|trim',
                        ],
                        

                        'emp_status' => [
                            'label' => 'Status',
                            'rules' => 'required|trim',
                        ],
                        'emp_date_hired' => [
                            'label' => 'Date of Appointment',
                            'rules' => 'trim',
                        ],
                        'emp_position' => [
                            'label' => 'Position',
                            'rules' => 'required|trim',
                        ],
                        
//                        'emp_program' => [
//                            'label' => 'Program',
//                            'rules' => 'required|trim',
//                        ],
                        'emp_office' => [
                            'label' => 'Office',
                            'rules' => 'required|trim',
                        ],
                        'emp_division' => [
                            'label' => 'Division',
                            'rules' => 'required|trim',
                        ],
                        'emp_unit' => [
                            'label' => 'Unit',
                            'rules' => 'trim',
                        ],

                        'emp_is_active' => [
                            'label' => 'Is Active',
                            'rules' =>'trim',
                        ]
                    ];

                
		return $this->validate($rules);
                
        }
        
        
        
        
        
        
        
        
}
