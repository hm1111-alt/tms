<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;

class Holidays extends BaseController
{
        
        public function __construct()
        {
                // Load the model in the constructor
                $this->mdl_holiday = new \App\Models\attendance\mdl_holiday();
                
                $this->mdl_setting = new \App\Models\preferences\mdl_setting();
                $this->class_name = basename(str_replace('\\', '/', get_class($this)));
                
                
//                if (! $this->session->get('isLoggedIn')) {
                    // User is not logged in, redirect to login page
//                     redirect()->to('LoginController/login');
//                }
                
            
                // Load the session service
                //$this->session = \Config\Services::session();
        
                //$this->session->start();

//                session()->set([
//                    'userid' => '1',
//                    'username' => 'Admin',
//                    'logged_in' => true
//                ]);
        }
        
        public function index()
        {
                $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                
                return view('attendance/holidays/holidays',$data);
        }

        function datepicker()
        {
                return view('attendance/datepicker');
        }

        function load_holidays_table()
        {
                $search = trim($_POST['search']);
		$page=1;
                $page2 = $_POST['page'];
		if($page2!='') {
                    $page=$page2;
                } 
                $limit = $_POST['limit'];
                $order_by = $_POST['order_by'];
                $sort_by = $_POST['sort_by'];
                
                $num_list = $limit;
                $year = $_POST['record_status'];

                $condition = " and holiday_date LIKE '".$year."%' ";


                $event_list = $this->mdl_holiday->get_holidays($condition);
                $event_count = count($event_list);

                $max_page = ($num_list!='all') ? ceil($event_count/$num_list) : 0;
                $page_final = ($page>$max_page) ? 1 : $page;

                $data['records']=$event_list;
                $data['details']= array(
                    'aa'=>0,//($num_list*$page)-$num_list,
                    'page'=>$page_final,
                    'num_list'=>$num_list,
                    'event_count'=>$event_count,
                    'max_page'=>$max_page,
                    'num'=>0
                );
                return view('attendance/holidays/holidays_table',$data);
        }
        
        
        function edit($holiday_id)
        {
                $validation = $this->_validate_holidays();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){
                    
                        //echo $validation!=true ? 'false' : 'true';
                        return $this->_form($holiday_id);

                } else {
                    
                        $res = $this->mdl_holiday->save_holiday();

                        if($res){
                            session()->setFlashdata('success', 'Holiday successfully updated.');
                        } else {
                            session()->setFlashdata('error', 'Something went wrong while updating Holiday.');
                        }
                        return redirect()->to('attendance/holidays/index');
                }
                
        }
        
        private function _form($holiday_id='')
        {
                //$this->mdl_holiday = new \App\Models\mdl_holiday();
                
                if($holiday_id!=""){
                    $data['details'] = $this->mdl_holiday->get_holiday_details($holiday_id);
                }
                
                if($this->request->getPost()){
                    \Config\Services::validation();
                    $data['validation'] = $this->validator;
                }
                        
                $data['module_main'] = 'Attendance';
                $data['module_sub'] = 'Holidays & Work Suspensions';
                $data['module_name'] = 'Holidays & Work Suspensions';

                $data['categories'] = $this->mdl_holiday->get_holidays_categories();
                $data['coverages'] = $this->mdl_holiday->get_holidays_coverage();

                return view('attendance/holidays/holidays_form',$data);
        }
        
        function add()
        {
                
                $validation = $this->_validate_holidays();
                
                if(($this->request->getPost() && $validation!=TRUE) || !$this->request->getPost()){
                    
                        //echo $validation!=true ? 'false' : 'true';
                        return $this->_form();

                    
                } else {
                    
                        $res = $this->mdl_holiday->save_holiday();

                        if($res){
                            session()->setFlashdata('success', 'Holiday successfully saved.');
                        } else {
                            session()->setFlashdata('error', 'Something went wrong while saving Holiday.');
                        }
                        return redirect()->to('attendance/holidays/index');

                }
        }
        
        private function _validate_holidays()
        {
                
                $rules = [
                    'holiday_name' => [
                        'label' => 'Title',
                        'rules' =>'required|trim',
                    ],
                    'holiday_date' => [
                        'label' => 'Date',
                        'rules' =>'required',
                    ],
                    'category' => [
                        'label' => 'Category',
                        'rules' =>'required',
                    ],
                    'coverage' => [
                        'label' => 'Coverage',
                        'rules' =>'required',
                    ],
                    'holiday_remarks' => [
                        'label' => 'Remarks',
                        'rules' =>'trim',
                    ],
                ];
                
		return $this->validate($rules);
        }
        
        
        
        function delete($holiday_id)
        {
                $res = $this->mdl_holiday->delete_holiday($holiday_id);
                if($res){
                    session()->setFlashdata('success', 'Holiday successfully deleted.');
                } else {
                    session()->setFlashdata('error', 'Something went wrong while deleting Holiday.');
                }
                return redirect()->to('attendance/holidays/index');
        }
        
        
}
