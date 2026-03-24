<?php

namespace App\Controllers\Records;

use App\Controllers\BaseController;
use App\Libraries\PdfService;

class Employees extends BaseController
{
        public function __construct()
        {
            // Load the model in the constructor
            $this->mdl_employee = new \App\Models\mdl_employee();
        }

        public function index()
        {
//                $emplyoees = $this->mdl_employee->sync();
//                
//                echo '<pre>';
//                print_r($emplyoees);
//                echo '<pre>';
//                
//                die;
                        
                $data['module_main'] = 'HR Records';
                $data['module_sub'] = 'Employees';
                $data['module_name'] = 'Employees';
                return view('records/employees/employees',$data);
                
        }

        function load_employees_table()
        {
                $search = trim($_POST['search']);
		$page=1;
                $page2 = $_POST['page'];
		if($page2!='') {
                    $page=$page2;
                    session()->remove('employees_page');
                    session()->set('employees_page', $page);
                } if(session()->get('employees_page')!=''){
                    $page = session()->get('employees_page');
                }
                $limit = $_POST['limit'];
		if($_POST['limit']!='') {
                    session()->remove('employees_limit');
                    session()->set('employees_limit', $limit);
                }
                
                $order_by = $_POST['order_by'];
		if($_POST['order_by']!='') {
                    session()->remove('employees_order_by');
                    session()->set('employees_order_by', $order_by);
                }
                
                $sort_by = $_POST['sort_by'];
		if($_POST['sort_by']!='') {
                    session()->remove('employees_sort_by');
                    session()->set('employees_sort_by', $sort_by);
                }
                
                $num_list = $limit;


		$condition="";
		if($search!=''){
                    
                        $condition .= "and (emp_fullname like '%".($search)."%' or emp_idno like '%".($search)."%' 
                                                  )";
                        
                        if(session()->get('employees_search')!='' && session()->get('employees_search')!=$search){
                            session()->remove('employees_page');
                        }
                        
                        session()->remove('employees_search');
                        session()->set('employees_search', $search);
                } else {
                        session()->remove('employees_search');
                }
                

                $event_list = $this->mdl_employee->get_employees($condition,$page,$num_list,$order_by,$sort_by);
                $event_count = $this->mdl_employee->count_employees($condition);

                $max_page = 0;//($num_list!='all') ? ceil($event_count/$num_list) : 0;
                $page_final = 1;//($page>$max_page) ? 1 : $page;

                $data['records']=$event_list;
                $data['details']= array(
                    'aa'=>0,//($num_list*$page)-$num_list,
                    'page'=>$page_final,
                    'num_list'=>$num_list,
                    'event_count'=>$event_count,
                    'max_page'=>$max_page,
                    'num'=>0
                );
                return view('records/employees/employees_table',$data);
        }
        
        
        function load_employee_details()
        {
                $details = $this->mdl_employee->get_employee_details($_POST['emp_idno']);
                echo json_encode($details);
        }
        
        
        function view($employee_id)
        {
                
                $basic = $this->mdl_employee->get_employee_basic($employee_id);
                $data['basic'] = $basic;
                
                
                $data['module_main'] = 'HR Records';
                $data['module_sub'] = 'Employees';
                $data['module_name'] = 'Employees';
                $empname = @$basic[0]->emp_fname.' '.@$basic[0]->emp_lname.' '.@$basic[0]->emp_extname;
                $data['module_sub2'] = 'Application for Leave - '.$empname;
                
                return view('records/employees/employees_view',$data);
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
                $data['programs'] = $this->mdl_menu->get_programs_menu();
                
                $data['positions'] = $this->mdl_menu->get_positions_menu(1);
                
                $data['civil_status'] = $this->mdl_menu->get_civil_status_menu();
                
                        
                
                if($employee_id!=""){
                    $basic = $this->mdl_employee->get_employee_basic($employee_id);
                    $data['basic'] = $basic;
                    
//                    $personal = $this->mdl_employee->get_employee_personal($employee_id);
//                    $data['personal'] = $personal;
                }
                        
                $empname = @$basic[0]->emp_fname.' '.@$basic[0]->emp_lname.' '.@$basic[0]->emp_extname;
                
                $data['module_main'] = 'HR Records';
                $data['module_sub'] = 'Employees';
                $data['module_name'] = 'Employees';
                $data['module_sub2'] = $empname.' (edit)';
                
                return view('records/employees/employees_edit',$data);
        }
        
        
        function edit($employee_id,$to=1)
	{                
                
                $validation = $this->_validate_employee();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){

                        return $this->_form($employee_id);

                } else {

                        $res = $this->mdl_employee->save_employee($employee_id);

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
                        
                        'emp_program' => [
                            'label' => 'Program',
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
