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
                $user_type = session()->get('user_type_name');
                $employee_id = session()->get('empid');
                $emp_idno = session()->get('emp_idno');
                $userid = session()->get('userid');
                
                log_message('info', '=== DASHBOARD DEBUG START ===');
                log_message('info', 'User Type: ' . ($user_type ?? 'NULL'));
                log_message('info', 'Employee ID: ' . ($employee_id ?? 'NULL'));
                log_message('info', 'Emp ID No: ' . ($emp_idno ?? 'NULL'));
                log_message('info', 'User ID: ' . ($userid ?? 'NULL'));
                log_message('info', 'ALL SESSION DATA: ' . json_encode(session()->get()));
                
                $this->mdl_menu = new \App\Models\mdl_menu();
                $data['user_type'] = $user_type;
                $data['basic'] = null;
                $data['lib_offices'] = [];
                $data['divisions'] = [];
                $data['units'] = [];
                
                if (!empty($emp_idno) && stripos($user_type, 'guest') === false) {
                    try {
                        $basic = $this->mdl_dashboard->get_employee_basic($emp_idno);
                        $data['basic'] = $basic;
                        
                        if(!empty($basic) && isset($basic[0])) {
                            $data['lib_offices'] = $this->mdl_menu->get_lib_offices();
                            
                            if(@$basic[0]->emp_office!=''){
                                $data['divisions'] = $this->mdl_menu->get_divisions_menu(@$basic[0]->emp_office);
                            }
                            if(@$basic[0]->emp_division!=''){
                                $data['units'] = $this->mdl_menu->get_units_menu(@$basic[0]->emp_division);
                            }
                        }
                    } catch(\Exception $e) {
                        log_message('error', 'Error loading employee data: ' . $e->getMessage());
                    }
                }
                
                $data = $this->load_training_statistics($data, $employee_id, $emp_idno, $user_type);
                
                $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                $data['module_name'] = $data['page']->page_name;
                $data['class_name'] = $this->class_name;
                $data['empidno'] = '';
                
                log_message('info', 'Loading dashboard for user type: ' . $user_type);
                
                if (stripos($user_type, 'admin') !== false) {
                    log_message('info', 'Redirecting to admin dashboard');
                    return view('layout/dashboard_admin', $data);
                } elseif (stripos($user_type, 'employee') !== false || stripos($user_type, 'staff') !== false || stripos($user_type, 'guest') !== false) {
                    log_message('info', 'Redirecting to employee/guest dashboard');
                    return view('layout/dashboard_employee', $data);
                } else {
                    log_message('warning', 'Unknown user type, loading default dashboard');
                    return view('layout/dashboard', $data);
                }
        }
        
        private function load_training_statistics($data, $employee_id, $emp_idno, $user_type)
        {
            $data['total_trainings'] = 0;
            $data['trainings_this_month'] = 0;
            $data['upcoming_trainings'] = 0;
            $data['completed_trainings'] = 0;
            $data['category_summary'] = [];
            $data['pending_trainings'] = 0;
            $data['recent_trainings'] = [];
            $data['my_trainings_count'] = 0;
            
            log_message('info', '=== LOAD TRAINING STATS DEBUG ===');
            log_message('info', 'User Type: ' . ($user_type ?? 'NULL'));
            log_message('info', 'Employee ID: ' . ($employee_id ?? 'NULL'));
            log_message('info', 'Emp ID No: ' . ($emp_idno ?? 'NULL'));
            log_message('info', 'Session userid: ' . (session()->get('userid') ?? 'NULL'));
            
            $db = \Config\Database::connect();
            try {
                if (stripos($user_type, 'admin') !== false) {
                        $all_trainings = $db->table('lib_trainings lt')
                            ->select('lt.*, ltc.training_category_name, ts.status_name')
                            ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('training_status ts', 'ts.id = oti.status_id')
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->groupBy('lt.id_training')
                            ->get()
                            ->getResultArray();
                        
                        $data['total_trainings'] = count($all_trainings);
                        log_message('info', 'DEBUG - Total trainings (array count): ' . $data['total_trainings']);
                                        
                        $data['pending_trainings'] = (int) $db->table('pending_trainings')
                            ->groupStart()
                                ->where('is_approved IS NULL', null, null, false)
                                ->orWhere('is_approved', 0)
                            ->groupEnd()
                            ->where('is_disapproved !=', 1)
                            ->countAllResults();
                                        
                        $current_month = date('m');
                        $current_year = date('Y');
                        $trainings_this_month = $db->table('lib_trainings lt')
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('training_status ts', 'ts.id = oti.status_id')
                            ->where('YEAR(lt.training_added_date)', $current_year)
                            ->where('MONTH(lt.training_added_date)', $current_month)
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->groupBy('lt.id_training')
                            ->get()
                            ->getResultArray();
                        $data['trainings_this_month'] = count($trainings_this_month);
                                        
                        // Upcoming trainings (all upcoming)
                        $today = date('Y-m-d');
                        $upcoming_trainings = $db->table('lib_trainings lt')
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('training_status ts', 'ts.id = oti.status_id')
                            ->where('lt.training_datefrom >', $today)
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->groupBy('lt.id_training')
                            ->get()
                            ->getResultArray();
                        $data['upcoming_trainings'] = count($upcoming_trainings);
                                        
                        // Recent trainings (for admin table)
                        $data['recent_trainings'] = $db->table('lib_trainings lt')
                            ->select('lt.*, ltc.training_category_name, ts.status_name')
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                            ->join('training_status ts', 'ts.id = oti.status_id')
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->groupBy('lt.id_training')
                            ->orderBy('lt.training_added_date', 'DESC')
                            ->limit(5)
                            ->get()
                            ->getResultArray();
                                            
                    } elseif (stripos($user_type, 'employee') !== false || stripos($user_type, 'staff') !== false || stripos($user_type, 'guest') !== false) {
                        // Employee and Guest see their own statistics
                        // Use userid from session (works for both employees and guests)
                        $userid = session()->get('userid') ?? $emp_idno;
                        
                        log_message('info', 'Using userid for training count: ' . ($userid ?? 'NULL'));
                        
                        $data['my_trainings_count'] = (int) $db->table('training_attendees')
                            ->where('user_id', $userid)
                            ->countAllResults();
                        
                        log_message('info', 'My trainings count: ' . $data['my_trainings_count']);
                        
                        $data['pending_trainings_count'] = (int) $db->table('pending_trainings')
                            ->where('emp_idno', $emp_idno)
                            ->where('is_approved', 0)
                            ->countAllResults();
                        $data['pending_trainings'] = $data['pending_trainings_count'];
                        
                        // Total available trainings (for display purposes)
                        $data['total_trainings'] = (int) $db->table('lib_trainings lt')
                            ->select('lt.id_training', false)
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('training_status ts', 'ts.id = oti.status_id')
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->groupBy('lt.id_training')
                            ->countAllResults();
                        
                        // Trainings this month
                        $current_month = date('m');
                        $current_year = date('Y');
                        $data['trainings_this_month'] = (int) $db->table('lib_trainings lt')
                            ->select('lt.id_training', false)
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('training_status ts', 'ts.id = oti.status_id')
                            ->where('YEAR(lt.training_added_date)', $current_year)
                            ->where('MONTH(lt.training_added_date)', $current_month)
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->groupBy('lt.id_training')
                            ->countAllResults();
                        
                        // Upcoming trainings
                        $today = date('Y-m-d');
                        $data['upcoming_trainings'] = (int) $db->table('training_attendees ta')
                            ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                            ->where('ta.user_id', $userid)
                            ->where('lt.training_datefrom >', $today)
                            ->countAllResults();
                        
                        // Completed trainings
                        $today = date('Y-m-d');
                        $data['completed_trainings'] = (int) $db->table('training_attendees ta')
                            ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                            ->where('ta.user_id', $userid)
                            ->where('lt.training_dateto <', $today)
                            ->countAllResults();
                        
                        // Category summary
                        $data['category_summary'] = $db->table('lib_trainings lt')
                            ->select('ltc.training_category_name, COUNT(lt.id_training) as count')
                            ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('training_status ts', 'ts.id = oti.status_id')
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->groupBy('ltc.training_category_name')
                            ->orderBy('count', 'DESC')
                            ->get()
                            ->getResultArray();
                        
                        // Recent trainings (available for all)
                        $data['recent_trainings'] = $db->table('lib_trainings lt')
                            ->select('lt.*, ltc.training_category_name, ts.status_name')
                            ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                            ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                            ->where('ts.status_name IS NOT NULL', null, null, false)
                            ->where('ts.status_name != ""', null, null, false)
                            ->orderBy('lt.training_added_date', 'DESC')
                            ->limit(5)
                            ->get()
                            ->getResultArray();
                    }
                    
                    log_message('info', 'Training statistics loaded successfully');
                } catch (\Exception $e) {
                    log_message('error', 'Error loading training statistics: ' . $e->getMessage());
                    log_message('error', 'Exception trace: ' . $e->getTraceAsString());
                }
                
                return $data;
        }
        
        function load_memo()
        {
                $details = $this->mdl_dashboard->get_memo_details($_POST['memo_id']); 				
                $filedir = $_SERVER['REMOTE_ADDR']=='::1' ? 'http://localhost/hrmisv2/public/assets/files/memos/'.$details->memo_file_name : 'https://hrmis2.clsu.edu.ph/public/assets/files/memos/'.$details->memo_file_name; 
                echo $filedir;
        }
        
        function load_my_credits()
        {
                echo json_encode([]);
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
                         
                if($employee_id!=""){
                    $basic = $this->mdl_dashboard->get_employee_basic($employee_id);
                    $data['basic'] = $basic;
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
