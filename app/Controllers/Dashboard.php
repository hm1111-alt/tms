<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
        public function __construct()
        {
                $this->mdl_dashboard = new \App\Models\mdl_dashboard();
                $this->mdl_setting = new \App\Models\preferences\mdl_setting();
                $this->class_name = basename(str_replace('\\', '/', get_class($this)));
        }

        public function index()
        {
                $employee_id = session()->get('empid');
                
                $validation = $this->_validate_first_update();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){

                        if($this->request->getPost()){
                            \Config\Services::validation();
                            $data['validation'] = $this->validator;
                        }

                        $this->mdl_menu = new \App\Models\mdl_menu();

                        $basic = $this->mdl_dashboard->get_employee_basic($employee_id);
                        $data['basic'] = $basic;

                        $data['lib_offices'] = $this->mdl_menu->get_lib_offices();
                        
                        if(@$basic[0]->emp_office!=''){
                            $data['divisions'] = $this->mdl_menu->get_divisions_menu(@$basic[0]->emp_office);
                        }
                        if(@$basic[0]->emp_division!=''){
                            $data['units'] = $this->mdl_menu->get_units_menu(@$basic[0]->emp_division);
                        }
                        
                        
                        
                        $data['memos'] = '';//$this->mdl_dashboard->get_memos();
                        
                        $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                        $data['module_name'] = $data['page']->page_name;
//                        die($data['page']->page_name);
                
                        $data['class_name'] = $this->class_name;
                        
                        $data['empidno'] = '';//$this->mdl_dashboard->encode(session()->get('emp_idno'));
                        //$data['empidno'] = $this->mdl_dashboard->encode('20160822-01'); // for testing
                        
//                        $data['warning'] = 'Note: This is your first login. Update your details to continue using the system.';
                        
                        //if(!session()->get('earned_monet')){
                            $earned_monet = '123,456.00';//$this->compute_earned_monet();
                            session()->set('earned_monet', $earned_monet);
                        //}
                        
                        return view('layout/dashboard',$data);

                } else {

                        $this->mdl_profile = new \App\Models\mdl_profile();
                        $res = $this->mdl_profile->save_first_update();

                        if($res){
                            session()->remove('first_login');
                            session()->set('first_login', 0);
                    
                            session()->setFlashdata('success', 'Profile successfully updated.');
                        } else {
                            session()->setFlashdata('error', 'Something went wrong while updating profile.');
                        }

                        return redirect()->to('myprofile');

                }
                        
        }
        
        /*
        function compute_earned_monet()
        {
                $this->mdl_profile = new \App\Models\mdl_profile();
                $employee = $this->mdl_profile->get_employee_salary();
                
                $this->mdl_leave = new \App\Models\attendance\mdl_leave();
                $credits = $this->mdl_leave->get_employee_credits(session()->get('empid'));
                
                $total_credits = floatval(@$credits[0]->vl) + floatval(@$credits[0]->sl);
                $factor = floatval($this->mdl_setting->get_settings('leave_monet_factor'));
                $salary = floatval(@$employee[0]->salary);
                
                $monet_amount = $salary * $total_credits * $factor;
                
                return number_format($monet_amount, 2, '.', ',');
        }*/
        
        function load_memo()
        {
                $details = $this->mdl_dashboard->get_memo_details($_POST['memo_id']);
//                $details = $this->mdl_dashboard->get_memo_details();
                				
                $filedir = $_SERVER['REMOTE_ADDR']=='::1' ? 'http://localhost/hrmisv2/public/assets/files/memos/'.$details->memo_file_name : 'https://hrmis2.clsu.edu.ph/public/assets/files/memos/'.$details->memo_file_name; 
                echo $filedir;
        }
        
        function load_my_credits()
        {
                $this->mdl_leave = new \App\Models\attendance\mdl_leave();
                //$credits = $this->mdl_leave->get_employee_credits(session()->get('empid'));
                $credits0 = $this->mdl_leave->get_last_forward(session()->get('empid'));
                $credits = @$credits0[0]->vl>0 ? $credits0 : $this->mdl_leave->get_employee_credits(session()->get('empid'));
                $credits[0]->asof_date = @$credits[0]->balance_forward_date!='' ? date('F j, Y',strtotime(@$credits[0]->balance_forward_date)) : date('F j, Y',strtotime(@$credits[0]->last_updated));
                
                echo json_encode($credits);
        }
        
//----divider----------------------------------------------------------
//----divider----------------------------------------------------------
//----divider----------------------------------------------------------
        
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
                    
                    'emp_program' => [
                        'label' => 'Program',
                        'rules' => 'required|trim',
                    ],
                    'emp_office' => [
                        'label' => 'College / Division / Office',
                        'rules' => 'required|trim',
                    ],
                    'emp_division' => [
                        'label' => 'Department / Unit / Section',
                        'rules' => 'required|trim',
                    ],
                    
                    'file_upload' => [
                        'label' => 'File',
                        'rules' => 'uploaded[file_upload]|max_size[file_upload,3072]|ext_in[file_upload,jpg,jpeg,png]',
                    ],
                ];
                
		return $this->validate($rules);
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
                
//                $data['positions'] = $this->mdl_menu->get_positions_menu(1);
                
//                $data['civil_status'] = $this->mdl_menu->get_civil_status_menu();
                
                        
                
                if($employee_id!=""){
                    $basic = $this->mdl_dashboard->get_employee_basic($employee_id);
                    $data['basic'] = $basic;
                    
//                    $personal = $this->mdl_dashboard->get_employee_personal($employee_id);
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
                $details = $this->mdl_dashboard->get_employee_details($_POST['emp_idno']);
                echo json_encode($details);
        }
        
        
        function view($employee_id)
        {
                
                $basic = $this->mdl_dashboard->get_employee_basic($employee_id);
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

                        $res = $this->mdl_dashboard->save_employee($employee_id);

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
