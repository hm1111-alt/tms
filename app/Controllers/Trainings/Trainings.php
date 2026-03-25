<?php

namespace App\Controllers\Trainings;

use App\Controllers\BaseController;
use App\Models\trainings\mdl_employees_trainings;
use App\Models\trainings\mdl_lib_trainings;
use App\Models\trainings\mdl_pending_trainings;

class Trainings extends BaseController
{
    public function __construct()
    {
        $this->mdl_employees_trainings = new mdl_employees_trainings();
        $this->mdl_lib_trainings = new mdl_lib_trainings();
        $this->mdl_pending_trainings = new mdl_pending_trainings();
    }

    public function public_landing()
    {
        try {
            $data['trainings'] = $this->mdl_lib_trainings->getPublicTrainings();
            
            $this->mdl_categories = new \App\Models\trainings\mdl_lib_training_categories();
            $data['categories'] = $this->mdl_categories->getAllCategories();
            
            $data['total_trainings'] = count($data['trainings']);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading public trainings: ' . $e->getMessage());
            $data['trainings'] = [];
            $data['categories'] = [];
            $data['total_trainings'] = 0;
        }
        
        return view('trainings/public_landing', $data);
    }
    
    /**
     * Enroll in a training (requires login)
     */
    public function enroll($id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            session()->setFlashdata('error', 'Please login to join trainings.');
            return redirect()->to('/login');
        }
        
        try {
            $db = \Config\Database::connect();
            
            // Get training details
            $training = $db->table('lib_trainings lt')
                ->select('lt.*, ltc.training_category_name, ts.status as status_name')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('training_status ts', 'ts.id = lt.status_id', 'left')
                ->where('lt.id_training', $id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                session()->setFlashdata('error', 'Training not found.');
                return redirect()->to('/');
            }
            
            // Check if already enrolled
            $userid = session()->get('userid');
            $existing = $db->table('training_attendees ta')
                ->where('ta.training_id', $id)
                ->where('ta.user_id', $userid)
                ->get()
                ->getRowArray();
            
            if ($existing) {
                session()->setFlashdata('info', 'You are already enrolled in this training.');
                return redirect()->to('trainings/view/' . $id);
            }
            
            // Insert enrollment
            $enrollment_data = [
                'training_id' => $id,
                'user_id' => $userid,
                'date_joined' => date('Y-m-d H:i:s')
            ];
            
            $db->table('training_attendees')->insert($enrollment_data);
            
            session()->setFlashdata('success', 'Successfully enrolled in ' . esc($training['training_name']) . '!');
            return redirect()->to('trainings/view/' . $id);
            
        } catch (\Exception $e) {
            log_message('error', 'Error enrolling in training: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to enroll. Please try again.');
            return redirect()->to('trainings/view/' . $id);
        }
    }

    public function index()
    {
        // Check user type
        $user_type = session()->get('user_type_name');
        
        if (stripos($user_type, 'admin') !== false) {
            // Admin view - show all trainings from lib_trainings with status
            try {
                $db = \Config\Database::connect();
                
                // Get all trainings with category and status (only those with non-empty status)
                $data['trainings'] = $db->table('lib_trainings lt')
                    ->select('lt.*, ltc.training_category_name, ts.status as status_name')
                    ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                    ->join('training_status ts', 'ts.id = lt.status_id', 'left')
                    ->groupStart()
                        ->where('ts.status IS NOT NULL', null, null, false)
                        ->orWhere('ts.status != ""', null, null, false)
                    ->groupEnd()
                    ->orderBy('lt.training_added_date', 'DESC')
                    ->get()
                    ->getResultArray();
                
                $data['total_trainings'] = count($data['trainings']);
                
            } catch (\Exception $e) {
                log_message('error', 'Error loading admin trainings: ' . $e->getMessage());
                $data['trainings'] = [];
                $data['total_trainings'] = 0;
            }
            
            return view('trainings/admin_list', $data);
        } else {
            // Employee/Guest view - show all available trainings they can join
            return $this->browse_all_trainings();
        }
    }
    
    /**
     * Show all available trainings for employees/guests to browse and join
     * This is the same as public_landing but with enrollment buttons for logged-in users
     */
    public function browse_all_trainings()
    {
        try {
            $db = \Config\Database::connect();
            $userid = session()->get('userid');
            
            // Load dashboard statistics for the layout
            $data['my_trainings_count'] = 0;
            $data['pending_trainings'] = 0;
            $data['pending_trainings_count'] = 0;
            $data['total_trainings'] = 0;
            
            if (!empty($userid)) {
                // Get my trainings count
                $my_trainings_result = $db->table('training_attendees ta')
                    ->selectCount('*', 'count')
                    ->where('ta.user_id', $userid)
                    ->get()
                    ->getRow();
                $data['my_trainings_count'] = (int) ($my_trainings_result->count ?? 0);
                
                // Get pending trainings count
                $pending_result = $db->table('pending_trainings')
                    ->selectCount('*', 'count')
                    ->where('emp_idno', $userid)
                    ->where('is_approved', 0)
                    ->get()
                    ->getRow();
                $pending_count = (int) ($pending_result->count ?? 0);
                $data['pending_trainings_count'] = $pending_count;
                $data['pending_trainings'] = $pending_count;
                
                // Get total available trainings
                $total_result = $db->table('lib_trainings lt')
                    ->selectCount('lt.id_training', 'count')
                    ->join('training_status ts', 'ts.id = lt.status_id')
                    ->where('ts.status IS NOT NULL', null, null, false)
                    ->where('ts.status != ""', null, null, false)
                    ->get()
                    ->getRow();
                $data['total_trainings'] = (int) ($total_result->count ?? 0);
            }
            
            // Get trainings using the model method (same as public landing)
            $data['trainings'] = $this->mdl_lib_trainings->getPublicTrainings();
            
            // Add enrollment status for current user
            log_message('debug', '=== ENROLLMENT CHECK START ===');
            log_message('debug', 'Current userid from session: ' . $userid);
            
            // Direct SQL test for training 13101
            $test_query = $db->query("SELECT * FROM training_attendees WHERE training_id = 13101 AND user_id = ?", [$userid]);
            $test_result = $test_query->getRowArray();
            log_message('debug', 'Direct SQL test for training 13101: ' . print_r($test_result, true));
            
            foreach ($data['trainings'] as &$training) {
                if (!empty($userid)) {
                    $training_id = $training['id_training'] ?? $training['id'] ?? null;
                    log_message('debug', 'Checking training ID: ' . $training_id . ' for user: ' . $userid);
                    
                    $enrolled = $db->table('training_attendees ta')
                        ->where('ta.training_id', $training_id)
                        ->where('ta.user_id', $userid)
                        ->get()
                        ->getRowArray();
                    
                    log_message('debug', 'Query result: ' . print_r($enrolled, true));
                    $training['is_enrolled'] = !empty($enrolled);
                    log_message('debug', 'is_enrolled for training ' . $training_id . ': ' . ($training['is_enrolled'] ? 'TRUE' : 'FALSE'));
                } else {
                    $training['is_enrolled'] = false;
                    $training['enrollment_status'] = null;
                }
            }
            log_message('debug', '=== ENROLLMENT CHECK END ===');
            
            $data['total_trainings'] = count($data['trainings']);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading browse trainings: ' . $e->getMessage());
            $data['trainings'] = [];
            $data['total_trainings'] = 0;
        }
        
        return view('trainings/public_landing', $data);
    }
    
    /**
     * View pending trainings (submitted by users for approval)
     */
    public function pending()
    {
        try {
            $db = \Config\Database::connect();
            $db_employee = \Config\Database::connect('db_employee');
            $user_type = session()->get('user_type_name');
            $userid = session()->get('userid');
            
            // Initialize dashboard statistics needed by the layout
            $data['my_trainings_count'] = 0;
            $data['pending_trainings'] = 0;
            $data['pending_trainings_count'] = 0;
            $data['total_trainings'] = 0;
            
            if (!empty($userid)) {
                // Get my trainings count (approved trainings from training_attendees)
                $my_trainings_result = $db->table('training_attendees ta')
                    ->selectCount('*', 'count')
                    ->where('ta.user_id', $userid)
                    ->get()
                    ->getRow();
                $data['my_trainings_count'] = (int) ($my_trainings_result->count ?? 0);
                
                // Get pending trainings count (for badge)
                $pending_result = $db->table('pending_trainings')
                    ->selectCount('*', 'count')
                    ->where('emp_idno', $userid)
                    ->where('is_approved', 0)
                    ->get()
                    ->getRow();
                $pending_count = (int) ($pending_result->count ?? 0);
                $data['pending_trainings_count'] = $pending_count;
                $data['pending_trainings'] = $pending_count;
                
                // Get total available trainings
                $total_result = $db->table('lib_trainings lt')
                    ->selectCount('lt.id_training', 'count')
                    ->join('training_status ts', 'ts.id = lt.status_id')
                    ->where('ts.status IS NOT NULL', null, null, false)
                    ->where('ts.status != ""', null, null, false)
                    ->get()
                    ->getRow();
                $data['total_trainings'] = (int) ($total_result->count ?? 0);
            }
            
            if (stripos($user_type, 'admin') !== false) {
                // Admin sees ALL pending trainings
                // Note: We can't JOIN with employees table directly since it's in different DB
                $pending_list = $db->table('pending_trainings pt')
                    ->select('pt.*, ltc.training_category_name')
                    ->join('lib_training_category ltc', 'ltc.id_training_category = pt.training_category_id', 'left')
                    ->orderBy('pt.added_date', 'DESC')
                    ->get()
                    ->getResultArray();
                    
                // Get employee names separately for each record
                foreach ($pending_list as &$pt) {
                    if (!empty($pt['emp_idno'])) {
                        $emp_data = $db_employee->table('employees')
                            ->select('emp_fname, emp_lname, emp_mi, emp_extname')
                            ->where('emp_idno', $pt['emp_idno'])
                            ->get()
                            ->getRowArray();
                        if ($emp_data) {
                            $pt['emp_fname'] = $emp_data['emp_fname'];
                            $pt['emp_lname'] = $emp_data['emp_lname'];
                            $pt['emp_mi'] = $emp_data['emp_mi'];
                            $pt['emp_extname'] = $emp_data['emp_extname'];
                        }
                    }
                }
                $data['pending_trainings_list'] = $pending_list;
            } else {
                // Employee/Guest sees only their own pending trainings
                $pending_list = $db->table('pending_trainings pt')
                    ->select('pt.*, ltc.training_category_name')
                    ->join('lib_training_category ltc', 'ltc.id_training_category = pt.training_category_id', 'left')
                    ->where('pt.emp_idno', $userid)
                    ->orderBy('pt.added_date', 'DESC')
                    ->get()
                    ->getResultArray();
                
                // Add employee name info
                foreach ($pending_list as &$pt) {
                    if (!empty($pt['emp_idno'])) {
                        $emp_data = $db_employee->table('employees')
                            ->select('emp_fname, emp_lname, emp_mi, emp_extname')
                            ->where('emp_idno', $pt['emp_idno'])
                            ->get()
                            ->getRowArray();
                        if ($emp_data) {
                            $pt['emp_fname'] = $emp_data['emp_fname'];
                            $pt['emp_lname'] = $emp_data['emp_lname'];
                            $pt['emp_mi'] = $emp_data['emp_mi'];
                            $pt['emp_extname'] = $emp_data['emp_extname'];
                        }
                    }
                }
                
                $data['pending_trainings_list'] = $pending_list;
            }
            
            $data['total_pending'] = count($data['pending_trainings_list']);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading pending trainings: ' . $e->getMessage());
            $data['pending_trainings_list'] = [];
            $data['total_pending'] = 0;
            $data['my_trainings_count'] = 0;
            $data['pending_trainings'] = 0;
            $data['pending_trainings_count'] = 0;
            $data['total_trainings'] = 0;
        }
        
        // Set flag to hide quick actions on pending list page
        $data['hide_quick_actions'] = true;
        
        return view('trainings/pending_list', $data);
    }
    
    /**
     * Show employee/guest their personal trainings (approved and pending)
     */
    public function my_trainings()
    {
        try {
            $db = \Config\Database::connect();
            $userid = session()->get('userid');
            
            // Get user type to determine what to show
            $user_type = session()->get('user_type_name');
            
            log_message('debug', '=== MY_TRAININGS METHOD START ===');
            log_message('debug', 'Accessed via URL: ' . current_url());
            log_message('debug', 'User ID: ' . ($userid ?? 'NULL'));
            log_message('debug', 'User Type: ' . ($user_type ?? 'NULL'));
            
            // Load dashboard statistics for the layout
            $data['my_trainings_count'] = 0;
            $data['pending_trainings'] = 0; // For dashboard_employee layout badge
            $data['pending_trainings_count'] = 0;
            $data['total_trainings'] = 0;
            
            if (!empty($userid)) {
                // Get my trainings count
                $my_trainings_result = $db->table('training_attendees ta')
                    ->selectCount('*', 'count')
                    ->where('ta.user_id', $userid)
                    ->get()
                    ->getRow();
                $data['my_trainings_count'] = (int) ($my_trainings_result->count ?? 0);
                log_message('debug', 'My trainings count: ' . $data['my_trainings_count']);
                
                // Get pending trainings count (for dashboard header and badge)
                $pending_result = $db->table('pending_trainings')
                    ->selectCount('*', 'count')
                    ->where('emp_idno', $userid)
                    ->where('is_approved', 0)
                    ->get()
                    ->getRow();
                $pending_count = (int) ($pending_result->count ?? 0);
                $data['pending_trainings_count'] = $pending_count;
                $data['pending_trainings'] = $pending_count; // Same value for dashboard badge
                log_message('debug', 'Pending count: ' . $pending_count);
                
                // Get total available trainings
                $total_result = $db->table('lib_trainings lt')
                    ->selectCount('lt.id_training', 'count')
                    ->join('training_status ts', 'ts.id = lt.status_id')
                    ->where('ts.status IS NOT NULL', null, null, false)
                    ->where('ts.status != ""', null, null, false)
                    ->get()
                    ->getRow();
                $data['total_trainings'] = (int) ($total_result->count ?? 0);
            }
            
            // Initialize list variables to avoid undefined errors
            $data['active_trainings_list'] = [];
            $data['completed_trainings_list'] = [];
            $data['pending_trainings_list'] = [];
            
            // For guests without emp_idno, show empty view with message
            if (empty($userid) && stripos($user_type, 'guest') !== false) {
                // Guest without emp_idno - show empty state
                log_message('info', 'Guest user without emp_idno accessing My Trainings');
                
                // Initialize ALL required arrays
                $data['upcoming_trainings_list'] = [];
                $data['ongoing_trainings_list'] = [];
                $data['completed_trainings_list'] = [];
                $data['pending_trainings_list'] = [];
                $data['total_upcoming'] = 0;
                $data['total_ongoing'] = 0;
                $data['total_completed'] = 0;
                $data['total_pending'] = 0;
                
                log_message('debug', 'Returning my_trainings view (guest without emp_idno)');
                return view('trainings/my_trainings', $data);
            }
            
            if (empty($userid)) {
                // User has no userid - show empty state
                log_message('info', 'User without userid accessing My Trainings - userid is: ' . var_export($userid, true));
                // Initialize ALL required arrays
                $data['upcoming_trainings_list'] = [];
                $data['ongoing_trainings_list'] = [];
                $data['completed_trainings_list'] = [];
                $data['pending_trainings_list'] = [];
                $data['total_upcoming'] = 0;
                $data['total_ongoing'] = 0;
                $data['total_completed'] = 0;
                $data['total_pending'] = 0;
                return view('trainings/my_trainings', $data);
            }
            
            log_message('debug', 'User has userid=' . $userid . ', proceeding to query database');
            
            // Get approved trainings (from training_attendees)
            log_message('debug', 'Querying trainings for user_id: ' . $userid);
            
            // Test query first - get ALL trainings for this user without date filter
            $test_all = $db->table('training_attendees ta')
                ->select('lt.training_name, lt.training_datefrom, lt.training_dateto, lt.status_id')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->where('ta.user_id', $userid)
                ->get()
                ->getResultArray();
            
            log_message('debug', 'TEST - ALL trainings for user (no date filter): ' . count($test_all));
            log_message('debug', 'TEST DATA: ' . json_encode($test_all));
            
            // Get UPCOMING trainings (status = 'upcoming' OR status = 'open')
            $data['upcoming_trainings_list'] = $db->table('training_attendees ta')
                ->select('lt.*, ltc.training_category_name, ts.status as status_name')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('training_status ts', 'ts.id = lt.status_id', 'left')
                ->where('ta.user_id', $userid)
                ->where("(ts.status = 'upcoming' OR ts.status = 'open')", null, false)
                ->orderBy('lt.training_datefrom', 'ASC')
                ->get()
                ->getResultArray();
            
            log_message('debug', 'Upcoming trainings count: ' . count($data['upcoming_trainings_list']));
            if (!empty($data['upcoming_trainings_list'])) {
                log_message('debug', 'Upcoming trainings data: ' . json_encode($data['upcoming_trainings_list']));
            }
            
            // Get ONGOING trainings (status = 'ongoing')
            $data['ongoing_trainings_list'] = $db->table('training_attendees ta')
                ->select('lt.*, ltc.training_category_name, ts.status as status_name')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('training_status ts', 'ts.id = lt.status_id', 'left')
                ->where('ta.user_id', $userid)
                ->where('ts.status', 'ongoing')
                ->orderBy('lt.training_datefrom', 'ASC')
                ->get()
                ->getResultArray();
            
            log_message('debug', 'Ongoing trainings count: ' . count($data['ongoing_trainings_list']));
            if (!empty($data['ongoing_trainings_list'])) {
                log_message('debug', 'Ongoing trainings data: ' . json_encode($data['ongoing_trainings_list']));
            }
            
            // Get completed trainings (status = 'completed' OR status = 'closed')
            log_message('debug', 'Querying completed trainings for user_id: ' . $userid);
            
            $data['completed_trainings_list'] = $db->table('training_attendees ta')
                ->select('lt.*, ltc.training_category_name, ts.status as status_name')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('training_status ts', 'ts.id = lt.status_id', 'left')
                ->where('ta.user_id', $userid)
                ->where("(ts.status = 'completed' OR ts.status = 'closed')", null, false)
                ->orderBy('lt.training_dateto', 'DESC')
                ->get()
                ->getResultArray();
            
            log_message('debug', 'Completed trainings count: ' . count($data['completed_trainings_list']));
            
            // Get pending trainings requests (use different name to avoid conflict)
            log_message('debug', 'Querying pending trainings for emp_idno: ' . $userid);
            
            $pending_list = $db->table('pending_trainings pt')
                ->select('lt.training_name, lt.training_category_id, ltc.training_category_name, pt.is_approved, pt.approve_remarks, pt.training_remarks, pt.added_date as date_requested')
                ->join('lib_trainings lt', 'lt.id_training = pt.training_id', 'left')
                ->join('lib_training_category ltc', 'ltc.id_training_category = pt.training_category_id', 'left')
                ->where('pt.emp_idno', $userid)
                ->orderBy('pt.added_date', 'DESC')
                ->get()
                ->getResultArray();
            
            // Add employee info for each pending training
            $db_employee = \Config\Database::connect('db_employee');
            foreach ($pending_list as &$pt) {
                if (!empty($pt['emp_idno'])) {
                    $emp_data = $db_employee->table('employees')
                        ->select('emp_fname, emp_lname, emp_mi, emp_extname')
                        ->where('emp_idno', $pt['emp_idno'])
                        ->get()
                        ->getRowArray();
                    if ($emp_data) {
                        $pt['emp_fname'] = $emp_data['emp_fname'];
                        $pt['emp_lname'] = $emp_data['emp_lname'];
                        $pt['emp_mi'] = $emp_data['emp_mi'];
                        $pt['emp_extname'] = $emp_data['emp_extname'];
                    }
                }
            }
            $data['pending_trainings_list'] = $pending_list;
            
            $data['total_upcoming'] = count($data['upcoming_trainings_list']);
            $data['total_ongoing'] = count($data['ongoing_trainings_list']);
            $data['total_completed'] = count($data['completed_trainings_list']);
            $data['total_pending'] = count($data['pending_trainings_list']);
            
            log_message('debug', 'Total Upcoming: ' . $data['total_upcoming']);
            log_message('debug', 'Total Ongoing: ' . $data['total_ongoing']);
            log_message('debug', 'Total Completed: ' . $data['total_completed']);
            log_message('debug', 'Total Pending: ' . $data['total_pending']);
            log_message('debug', '=== MY_TRAININGS METHOD END ===');
            
        } catch (\Exception $e) {
            log_message('error', 'CRITICAL ERROR in my_trainings: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            // Display error on screen for debugging AND stop execution
            echo '<div style="background:red; color:white; padding:20px; margin:20px; font-family: monospace;">';
            echo '<h3>ERROR in my_trainings():</h3>';
            echo '<p><strong>' . esc($e->getMessage()) . '</strong></p>';
            echo '<pre>' . esc($e->getTraceAsString()) . '</pre>';
            echo '</div>';
            
            $data['upcoming_trainings_list'] = [];
            $data['ongoing_trainings_list'] = [];
            $data['completed_trainings_list'] = [];
            $data['pending_trainings_list'] = [];
            $data['total_upcoming'] = 0;
            $data['total_ongoing'] = 0;
            $data['total_completed'] = 0;
            $data['total_pending'] = 0;
        }
        
            // Set flag to hide quick actions and welcome message on My Trainings page
            $data['hide_quick_actions'] = true;
            $data['hide_welcome_message'] = true;
            $data['hide_header_count'] = true;
            
            // Set custom header titles for My Trainings page
            $data['page_title'] = 'My Trainings';
            $data['portal_name'] = 'Dashboard';
            $data['show_green_header'] = true;
            
            log_message('debug', 'About to return my_trainings view');
            return view('trainings/my_trainings', $data);
    }
    
    public function add()
    {
        if ($this->request->getMethod() === 'post') {
            // Validation rules
            $rules = [
                'training_name' => [
                    'label' => 'Training Title',
                    'rules' => 'required|trim|min_length[5]|max_length[200]'
                ],
                'training_category_id' => [
                    'label' => 'Category',
                    'rules' => 'required|numeric'
                ],
                'training_datefrom' => [
                    'label' => 'Start Date',
                    'rules' => 'required|valid_date'
                ],
                'training_dateto' => [
                    'label' => 'End Date',
                    'rules' => 'required|valid_date|check_end_date[' . $this->request->getPost('training_datefrom') . ']'
                ],
                'training_deadline' => [
                    'label' => 'Enrollment Deadline',
                    'rules' => 'permit_empty|valid_date|check_deadline[' . $this->request->getPost('training_datefrom') . ',' . $this->request->getPost('training_dateto') . ']'
                ],
                'training_facilitator' => [
                    'label' => 'Facilitator',
                    'rules' => 'required|trim|min_length[3]|max_length[200]'
                ],
                'training_venue' => [
                    'label' => 'Venue',
                    'rules' => 'required|trim|min_length[3]|max_length[300]'
                ],
                'training_hours' => [
                    'label' => 'Training Hours',
                    'rules' => 'required|numeric|greater_than[0]'
                ],
                'training_attendees' => [
                    'label' => 'Number of Attendees',
                    'rules' => 'permit_empty|numeric|greater_than[0]'
                ],
                'training_description.*' => [
                    'label' => 'Training Description Item',
                    'rules' => 'permit_empty|max_length[2000]'
                ],
                'training_learnings.*' => [
                    'label' => 'Training Learning Item',
                    'rules' => 'permit_empty|max_length[2000]'
                ],
                'training_is_local' => [
                    'label' => 'Is Local',
                    'rules' => 'permit_empty|in_list[0,1]'
                ],
                'training_require_upload' => [
                    'label' => 'Require Upload',
                    'rules' => 'permit_empty|in_list[0,1]'
                ],
                'training_require_feedback' => [
                    'label' => 'Require Feedback',
                    'rules' => 'permit_empty|in_list[0,1]'
                ]
            ];
            
            if ($this->validate($rules)) {
                try {
                    $db = \Config\Database::connect();
                    $db->transStart();
                    
                    // Generate reference number (15-20 characters)
                    $refno = 'TRN-' . strtoupper(substr(uniqid(), -8)) . '-' . date('Ymd');
                    
                    // Insert into lib_trainings
                    $training_data = [
                        'training_name' => $this->request->getPost('training_name'),
                        'training_category_id' => $this->request->getPost('training_category_id'),
                        'training_datefrom' => $this->request->getPost('training_datefrom'),
                        'training_dateto' => $this->request->getPost('training_dateto'),
                        'training_deadline' => $this->request->getPost('training_deadline') ?: null,
                        'training_facilitator' => $this->request->getPost('training_facilitator'),
                        'training_venue' => $this->request->getPost('training_venue'),
                        'training_hours' => $this->request->getPost('training_hours'),
                        'no_of_attendees' => $this->request->getPost('training_attendees') ?: null,
                        'training_is_local' => $this->request->getPost('training_is_local') ?? 0,
                        'training_require_upload' => $this->request->getPost('training_require_upload') ?? 0,
                        'training_require_feedback' => $this->request->getPost('training_require_feedback') ?? 0,
                        'training_refno' => $refno,
                        'status_id' => 1, // Default to "upcoming" status
                        'training_added_date' => date('Y-m-d H:i:s'),
                        'training_added_by' => session()->get('empid')
                    ];
                    
                    log_message('info', 'Inserting training data: ' . json_encode($training_data));
                    
                    $db->table('lib_trainings')->insert($training_data);
                    $training_id = $db->insertID();
                    
                    log_message('info', 'Training inserted with ID: ' . $training_id);
                    
                    // Insert multiple training descriptions if provided
                    $descriptions = $this->request->getPost('training_description');
                    if (is_array($descriptions) && !empty($descriptions)) {
                        foreach ($descriptions as $desc) {
                            if (!empty(trim($desc))) {
                                $db->table('lib_trainings_des')->insert([
                                    'training_id' => $training_id,
                                    'training_des' => trim($desc)
                                ]);
                            }
                        }
                    }
                    
                    // Insert multiple training learnings if provided
                    $learnings = $this->request->getPost('training_learnings');
                    if (is_array($learnings) && !empty($learnings)) {
                        foreach ($learnings as $learning) {
                            if (!empty(trim($learning))) {
                                $db->table('lib_trainings_learnings')->insert([
                                    'training_id' => $training_id,
                                    'training_learning' => trim($learning)
                                ]);
                            }
                        }
                    }
                    
                    $db->transComplete();
                    
                    if ($db->transStatus() > 0) {
                        log_message('info', 'Training created successfully with Ref No: ' . $refno);
                        session()->setFlashdata('success', 'Training successfully created! Reference No: ' . $refno);
                        return redirect()->to('trainings');
                    } else {
                        log_message('error', 'Transaction failed for training creation');
                        session()->setFlashdata('error', 'Failed to create training. Please try again.');
                    }
                    
                } catch (\Exception $e) {
                    log_message('error', 'Error creating training: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                    session()->setFlashdata('error', 'An error occurred while creating the training: ' . $e->getMessage());
                }
            } else {
                // Validation failed
                log_message('warning', 'Validation failed for training creation');
                $data['validation'] = $this->validator;
                
                // Log validation errors
                $errors = $this->validator->getErrors();
                log_message('warning', 'Validation errors count: ' . count($errors));
                foreach ($errors as $field => $error) {
                    log_message('warning', 'Validation error on ' . $field . ': ' . $error);
                }
            }
        }
        
        // Load categories for dropdown
        $this->mdl_categories = new \App\Models\trainings\mdl_lib_training_categories();
        $data['categories'] = $this->mdl_categories->getAllCategories();
        
        return view('trainings/add_form', $data);
    }
    
    /**
     * Load approved trainings
     */
    public function load_approved_trainings()
    {
        try {
            $emp_idno = session()->get('emp_idno'); 
            
            if (!$emp_idno) {
                $data = [
                    'trainings' => [],
                    'event_count' => 0,
                    'max_page' => 1,
                    'page' => 1,
                    'aa' => 0,
                    'num' => 0
                ];
                return view('trainings/approved_table', $data);
            }
            
            $limit = $this->request->getPost('limit') ?? 10;
            $search = $this->request->getPost('search') ?? '';
            $page = $this->request->getPost('page') ?? 1;
            
            $offset = ($page - 1) * $limit;

            $db = \Config\Database::connect('default');
            
            if (!$db) {
                $data = [
                    'trainings' => [],
                    'event_count' => 0,
                    'max_page' => 1,
                    'page' => 1,
                    'aa' => 0,
                    'num' => 0
                ];
                return view('trainings/approved_table', $data);
            }
            
            try {
                $table_exists = $db->query("SHOW TABLES LIKE 'pending_trainings'");
                if ($table_exists && $table_exists->getNumRows() === 0) {
                    echo '<div class="alert alert-warning">';
                    echo '<i class="fas fa-exclamation-triangle"></i> Training module is not configured. Please contact the system administrator.';
                    echo '</div>';
                    return;
                }
                
                $query = $db->query("SELECT 1 FROM information_schema.tables WHERE table_schema = 'hr_lnd' LIMIT 1");
                if (!$query) {
                    $data = [
                        'trainings' => [],
                        'event_count' => 0,
                        'max_page' => 1,
                        'page' => 1,
                        'aa' => 0,
                        'num' => 0
                    ];
                    return view('trainings/approved_table', $data);
                }
                
                $builder = $db->table('pending_trainings pt')
                                   ->select('pt.*, ltc.training_category_name')
                                   ->join('lib_training_category ltc', 'ltc.id_training_category = pt.training_category_id', 'left')
                                   ->where('pt.emp_idno', $emp_idno)
                                   ->where('pt.is_approved', 1); 
                
                if (!empty($search)) {
                    $builder->groupStart()
                            ->like('lt.training_name', $search)
                            ->orLike('pt.emp_fullname', $search)
                            ->orLike('lt.training_venue', $search)
                            ->orLike('lt.training_facilitator', $search)
                            ->groupEnd();
                }
                
                $total_query = clone $builder;
                $total_count = $total_query->countAllResults();
                
                $trainings = $builder->orderBy('pt.added_date', 'DESC')
                                     ->limit($limit, $offset)
                                     ->get()
                                     ->getResultArray();
                
                if (!empty($trainings)) {
                    log_message('debug', 'First approved training sample: ' . print_r($trainings[0], true));
                }
                
                $max_page = ceil($total_count / $limit);
                if ($max_page == 0) $max_page = 1;
                
                try {
                    $categories_model = new \App\Models\trainings\mdl_lib_training_categories();
                    $training_categories = $categories_model->getAllCategories();
                } catch (\Exception $e) {
                    log_message('error', 'Error loading training categories in load_approved_trainings: ' . $e->getMessage());
                    $training_categories = []; 
                }
                
                $data = [
                    'trainings' => $trainings,
                    'training_categories' => $training_categories,
                    'event_count' => $total_count,
                    'max_page' => $max_page,
                    'page' => $page,
                    'aa' => $offset,
                    'num' => count($trainings)
                ];

                return view('trainings/approved_table', $data);
            } catch (\Exception $qe) {
                $data = [
                    'trainings' => [],
                    'event_count' => 0,
                    'max_page' => 1,
                    'page' => 1,
                    'aa' => 0,
                    'num' => 0
                ];
                return view('trainings/approved_table', $data);
            }
        } catch (\Exception $e) {
            log_message('debug', 'Error in load_approved_trainings: ' . $e->getMessage());
            $data = [
                'trainings' => [],
                'event_count' => 0,
                'max_page' => 1,
                'page' => 1,
                'aa' => 0,
                'num' => 0
            ];
            return view('trainings/approved_table', $data);
        }
    }
    
    public function view($id)
    {
        try {
            log_message('debug', 'Loading training details for ID: ' . $id);
            
            $db = \Config\Database::connect('default');
            
            if (!$db) {
                log_message('error', 'Unable to connect to training database');
                return '<div class="alert alert-danger">Unable to connect to training database</div>';
            }
            
            // First, try to find in pending_trainings (for pending/approved trainings)
            log_message('debug', 'Checking pending_trainings table...');
            $training = $db->table('pending_trainings pt')
                          ->select('pt.*, ltc.training_category_name')
                          ->join('lib_training_category ltc', 'ltc.id_training_category = pt.training_category_id', 'left')
                          ->where('pt.id_pending_training', $id)
                          ->get()
                          ->getRowArray();
            
            if ($training) {
                log_message('debug', 'Found in pending_trainings');
                $training['is_pending'] = empty($training['is_approved']) || $training['is_approved'] == 0;
                $training['from_lib'] = false;
            } else {
                // If not found in pending_trainings, check lib_trainings (main library)
                log_message('debug', 'Not found in pending_trainings, checking lib_trainings...');
                $training = $db->table('lib_trainings lt')
                              ->select('lt.*, ltc.training_category_name, ts.status as status_name')
                              ->join('lib_training_category ltc', 'lt.training_category_id = ltc.id_training_category', 'left')
                              ->join('training_status ts', 'ts.id = lt.status_id', 'left')
                              ->where('lt.id_training', $id)
                              ->get()
                              ->getRowArray();
                
                if ($training) {
                    log_message('debug', 'Found in lib_trainings');
                    $training['is_pending'] = false;
                    $training['from_lib'] = true;
                }
            }
            
            log_message('debug', 'Training query result: ' . ($training ? 'FOUND' : 'NOT FOUND'));
            
            if (!$training) {
                log_message('debug', 'Training record not found for ID: ' . $id);
                return '<div class="alert alert-warning">Training record not found</div>';
            }
            
            log_message('debug', 'Training data: ' . print_r($training, true));
            
            $training_type = 'General'; 

            if (isset($training['training_category_name']) && !empty($training['training_category_name'])) {
                $training_type = $training['training_category_name'];
            } 
            elseif (isset($training['training_category_id']) && !empty($training['training_category_id'])) {
                try {
                    $category_model = new \App\Models\trainings\mdl_lib_training_categories();
                    $category = $category_model->getCategoryById($training['training_category_id']);
                    if ($category && isset($category['category_name'])) {
                        $training_type = $category['category_name'];
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error fetching training category: ' . $e->getMessage());
                    switch($training['training_category_id']) {
                        case 1: $training_type = 'Management'; break;
                        case 2: $training_type = 'Technical'; break;
                        case 3: $training_type = 'Leadership'; break;
                        case 4: $training_type = 'Professional Development'; break;
                        default: $training_type = 'General'; break;
                    }
                }
            }
            elseif (isset($training['training_type_id']) && !empty($training['training_type_id'])) {
                try {
                    $category_model = new \App\Models\trainings\mdl_lib_training_categories();
                    $category = $category_model->getCategoryById($training['training_type_id']);
                    if ($category && isset($category['category_name'])) {
                        $training_type = $category['category_name'];
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error fetching training category: ' . $e->getMessage());
                    switch($training['training_type_id']) {
                        case 1: $training_type = 'Management'; break;
                        case 2: $training_type = 'Technical'; break;
                        case 3: $training_type = 'Leadership'; break;
                        case 4: $training_type = 'Professional Development'; break;
                        default: $training_type = 'General'; break;
                    }
                }
            }
            
            log_message('debug', 'Training type: ' . $training_type);
            
            // Check if current user is already enrolled (for logged-in users)
            $userid = session()->get('userid');
            if (!empty($userid)) {
                $enrolled = $db->table('training_attendees ta')
                    ->where('ta.training_id', $id)
                    ->where('ta.user_id', $userid)
                    ->get()
                    ->getRowArray();
                $training['is_enrolled'] = !empty($enrolled);
                log_message('debug', 'User enrollment status for training ' . $id . ': ' . ($training['is_enrolled'] ? 'ENROLLED' : 'NOT ENROLLED'));
            } else {
                $training['is_enrolled'] = false;
            }
            
            $data = [
                'training' => $training,
                'training_type' => $training_type
            ];
            
            log_message('debug', 'Loading training_details view');
            
            // Check if user is logged in - use different views for public vs authenticated users
            if (session()->get('logged_in')) {
                // Authenticated users see the admin/internal view with sidebar
                return view('trainings/training_details', $data);
            } else {
                // Public users see the simplified public view
                return view('trainings/public_training_details', $data);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading training details: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return '<div class="alert alert-danger">Error loading training details: ' . $e->getMessage() . '</div>';
        }
    }

    public function delete($id)
    {
        try {
            $db = \Config\Database::connect();
            $db->transStart();
            
            // Check if training exists
            $training = $db->table('lib_trainings')
                ->where('id_training', $id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training not found.'
                ]);
            }
            
            log_message('info', 'Deleting training ID: ' . $id . ' - Name: ' . $training['training_name']);
            
            // Delete related records first (foreign key constraints)
            $db->table('lib_trainings_des')->delete(['training_id' => $id]);
            $db->table('lib_trainings_learnings')->delete(['training_id' => $id]);
            
            // Delete the training
            $db->table('lib_trainings')->delete(['id_training' => $id]);
            
            $db->transComplete();
            
            if ($db->transStatus() > 0) {
                log_message('info', 'Training deleted successfully: ' . $training['training_name']);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Training deleted successfully.'
                ]);
            } else {
                log_message('error', 'Transaction failed for deleting training ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete training. Transaction error.'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'An error occurred while deleting the training: ' . $e->getMessage()
            ]);
        }
    }
    
    public function edit($id)
    {
        try {
            $db = \Config\Database::connect();
            
            // Get training data
            $data['training'] = $db->table('lib_trainings')
                ->where('id_training', $id)
                ->get()
                ->getRowArray();
            
            if (!$data['training']) {
                session()->setFlashdata('error', 'Training not found.');
                return redirect()->to('trainings');
            }
            
            // Get descriptions
            $data['descriptions'] = $db->table('lib_trainings_des')
                ->where('training_id', $id)
                ->get()
                ->getResultArray();
            
            // Get learnings
            $data['learnings'] = $db->table('lib_trainings_learnings')
                ->where('training_id', $id)
                ->get()
                ->getResultArray();
            
            // Get registered attendee count
            $data['registered_count'] = $db->table('training_attendees')
                ->where('training_id', $id)
                ->countAllResults();
            
            // Load categories for dropdown
            $this->mdl_categories = new \App\Models\trainings\mdl_lib_training_categories();
            $data['categories'] = $this->mdl_categories->getAllCategories();
            
            return view('trainings/edit_form', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading edit form: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            session()->setFlashdata('error', 'Error loading training data.');
            return redirect()->to('trainings');
        }
    }
    
    public function update()
    {
        if ($this->request->getMethod() === 'post') {
            $id = $this->request->getPost('id_training');
            
            // Validation rules (same as add)
            $rules = [
                'training_name' => [
                    'label' => 'Training Title',
                    'rules' => 'required|trim|min_length[5]|max_length[200]'
                ],
                'training_category_id' => [
                    'label' => 'Category',
                    'rules' => 'required|numeric'
                ],
                'training_datefrom' => [
                    'label' => 'Start Date',
                    'rules' => 'required|valid_date'
                ],
                'training_dateto' => [
                    'label' => 'End Date',
                    'rules' => 'required|valid_date|check_end_date[' . $this->request->getPost('training_datefrom') . ']'
                ],
                'training_deadline' => [
                    'label' => 'Enrollment Deadline',
                    'rules' => 'permit_empty|valid_date|check_deadline[' . $this->request->getPost('training_datefrom') . ',' . $this->request->getPost('training_dateto') . ']'
                ],
                'training_facilitator' => [
                    'label' => 'Facilitator',
                    'rules' => 'required|trim|min_length[3]|max_length[200]'
                ],
                'training_venue' => [
                    'label' => 'Venue',
                    'rules' => 'required|trim|min_length[3]|max_length[300]'
                ],
                'training_hours' => [
                    'label' => 'Training Hours',
                    'rules' => 'required|numeric|greater_than[0]'
                ],
                'training_attendees' => [
                    'label' => 'Number of Attendees',
                    'rules' => 'permit_empty|numeric|greater_than[0]'
                ],
                'training_description.*' => [
                    'label' => 'Training Description Item',
                    'rules' => 'permit_empty|max_length[2000]'
                ],
                'training_learnings.*' => [
                    'label' => 'Training Learning Item',
                    'rules' => 'permit_empty|max_length[2000]'
                ],
                'training_is_local' => [
                    'label' => 'Is Local',
                    'rules' => 'permit_empty|in_list[0,1]'
                ],
                'training_require_upload' => [
                    'label' => 'Require Upload',
                    'rules' => 'permit_empty|in_list[0,1]'
                ],
                'training_require_feedback' => [
                    'label' => 'Require Feedback',
                    'rules' => 'permit_empty|in_list[0,1]'
                ]
            ];
            
            if ($this->validate($rules)) {
                try {
                    $db = \Config\Database::connect();
                    $db->transStart();
                    
                    // Update lib_trainings
                    $training_data = [
                        'training_name' => $this->request->getPost('training_name'),
                        'training_category_id' => $this->request->getPost('training_category_id'),
                        'training_datefrom' => $this->request->getPost('training_datefrom'),
                        'training_dateto' => $this->request->getPost('training_dateto'),
                        'training_deadline' => $this->request->getPost('training_deadline') ?: null,
                        'training_facilitator' => $this->request->getPost('training_facilitator'),
                        'training_venue' => $this->request->getPost('training_venue'),
                        'training_hours' => $this->request->getPost('training_hours'),
                        'no_of_attendees' => $this->request->getPost('training_attendees') ?: null,
                        'training_is_local' => $this->request->getPost('training_is_local') ?? 0,
                        'training_require_upload' => $this->request->getPost('training_require_upload') ?? 0,
                        'training_require_feedback' => $this->request->getPost('training_require_feedback') ?? 0,
                        'training_added_by' => session()->get('empid')
                    ];
                    
                    $db->table('lib_trainings')->update($training_data, ['id_training' => $id]);
                    
                    // Delete existing descriptions and learnings
                    $db->table('lib_trainings_des')->delete(['training_id' => $id]);
                    $db->table('lib_trainings_learnings')->delete(['training_id' => $id]);
                    
                    // Insert new descriptions
                    $descriptions = $this->request->getPost('training_description');
                    if (is_array($descriptions) && !empty($descriptions)) {
                        foreach ($descriptions as $desc) {
                            if (!empty(trim($desc))) {
                                $db->table('lib_trainings_des')->insert([
                                    'training_id' => $id,
                                    'training_des' => trim($desc)
                                ]);
                            }
                        }
                    }
                    
                    // Insert new learnings
                    $learnings = $this->request->getPost('training_learnings');
                    if (is_array($learnings) && !empty($learnings)) {
                        foreach ($learnings as $learning) {
                            if (!empty(trim($learning))) {
                                $db->table('lib_trainings_learnings')->insert([
                                    'training_id' => $id,
                                    'training_learning' => trim($learning)
                                ]);
                            }
                        }
                    }
                    
                    $db->transComplete();
                    
                    if ($db->transStatus() > 0) {
                        log_message('info', 'Training updated successfully ID: ' . $id);
                        session()->setFlashdata('success', 'Training successfully updated!');
                        return redirect()->to('trainings');
                    } else {
                        log_message('error', 'Transaction failed for updating training ID: ' . $id);
                        session()->setFlashdata('error', 'Failed to update training. Please try again.');
                    }
                    
                } catch (\Exception $e) {
                    log_message('error', 'Error updating training: ' . $e->getMessage());
                    log_message('error', 'Stack trace: ' . $e->getTraceAsString());
                    session()->setFlashdata('error', 'An error occurred while updating the training: ' . $e->getMessage());
                }
            } else {
                // Validation failed
                log_message('warning', 'Validation failed for training update ID: ' . $id);
                $data['validation'] = $this->validator;
                
                // Get existing data for repopulating form
                $db = \Config\Database::connect();
                $data['training'] = $db->table('lib_trainings')
                    ->where('id_training', $id)
                    ->get()
                    ->getRowArray();
                    
                $data['descriptions'] = $db->table('lib_trainings_des')
                    ->where('training_id', $id)
                    ->get()
                    ->getResultArray();
                    
                $data['learnings'] = $db->table('lib_trainings_learnings')
                    ->where('training_id', $id)
                    ->get()
                    ->getResultArray();
                    
                $this->mdl_categories = new \App\Models\trainings\mdl_lib_training_categories();
                $data['categories'] = $this->mdl_categories->getAllCategories();
                
                return view('trainings/edit_form', $data);
            }
        }
    }

    public function load_pending_trainings()
    {
        ob_start();
        
        try {
            $emp_idno = session()->get('emp_idno');

            log_message('debug', 'load_pending_trainings called with emp_idno: ' . ($emp_idno ? $emp_idno : 'NULL'));
            
            if (!$emp_idno) {
                log_message('debug', 'No emp_idno found in session');
                $empid = session()->get('empid');
                log_message('debug', 'empid from session: ' . ($empid ? $empid : 'NULL'));
                
                if (!$empid) {
                    log_message('debug', 'No employee identifier found in session');
                    ob_end_clean();
                    echo '<div class="alert alert-warning">';
                    echo '<i class="fas fa-exclamation-triangle"></i> No employee ID found in session. Please login again.';
                    echo '</div>';
                    return;
                }

                $emp_idno = $empid;
                log_message('debug', 'Using empid as emp_idno: ' . $emp_idno);
            }
            
            try {
                $limit = $this->request->getPost('limit') ?? 10;
                $page = $this->request->getPost('page') ?? 1;
                $search = $this->request->getPost('search') ?? '';

                log_message('debug', 'Pending trainings request - limit: ' . $limit . ', page: ' . $page . ', search: ' . $search);

                $offset = ($page - 1) * $limit;

                $db = \Config\Database::connect('default');
                log_message('debug', 'Database connection status: ' . ($db ? 'SUCCESS' : 'FAILED'));
                
                if (!$db) {
                    log_message('error', 'Cannot connect to training database');
                    ob_end_clean();
                    echo '<div class="alert alert-warning">';
                    echo '<i class="fas fa-exclamation-triangle"></i> Training module is not configured. Please contact the system administrator.';
                    echo '</div>';
                    return;
                }
                
                try {
                    $tableExists = $db->query("SHOW TABLES LIKE 'pending_trainings'");
                    log_message('debug', 'pending_trainings table exists: ' . ($tableExists && $tableExists->getNumRows() > 0 ? 'YES' : 'NO'));
                    
                    if (!$tableExists || $tableExists->getNumRows() === 0) {
                        ob_end_clean();
                        echo '<div class="alert alert-warning">';
                        echo '<i class="fas fa-exclamation-triangle"></i> Training module is not configured. Please contact the system administrator.';
                        echo '</div>';
                        return;
                    }
                } catch (\Exception $tableEx) {
                    log_message('error', 'Error checking table existence: ' . $tableEx->getMessage());
                    ob_end_clean();
                    echo '<div class="alert alert-warning">';
                    echo '<i class="fas fa-exclamation-triangle"></i> Training module is not configured. Please contact the system administrator.';
                    echo '</div>';
                    return;
                }
                
                $baseQuery = $db->table('pending_trainings')
                    ->where('emp_idno', $emp_idno)
                    ->groupStart()
                        ->groupStart()
                            ->where('is_approved', 0)
                            ->orWhere('is_approved', null)
                        ->groupEnd()
                    ->groupEnd()
                    ->where('is_disapproved', 0);
                
                if (!empty($search)) {
                    $baseQuery->groupStart()
                        ->like('training_name', $search)
                        ->orLike('emp_fullname', $search)
                        ->orLike('training_venue', $search)
                        ->orLike('training_facilitator', $search)
                        ->groupEnd();
                }

                $countQuery = clone $baseQuery;
                $event_count = $countQuery->countAllResults(false); 
                log_message('debug', 'Total pending trainings found with filters: ' . $event_count);

                $max_page = ceil($event_count / $limit);
                
                log_message('debug', 'Applying limit: ' . $limit . ', offset: ' . $offset);
                log_message('debug', 'Query before execution: ' . $baseQuery->getCompiledSelect(false));
                
                $queryResult = $baseQuery->limit($limit, $offset)->orderBy('id_pending_training', 'DESC')->get();
                $pending_trainings = $queryResult->getResultArray();
                
                log_message('debug', 'Pending trainings retrieved: ' . count($pending_trainings));
                log_message('debug', 'Query SQL: ' . $baseQuery->getCompiledSelect());
                log_message('debug', '=== PENDING QUERY RESULT ===');
                log_message('debug', 'Records found: ' . count($pending_trainings));
                
                if (!empty($pending_trainings)) {
                    log_message('debug', 'First record emp_idno: ' . ($pending_trainings[0]['emp_idno'] ?? 'N/A'));
                    log_message('debug', 'Search emp_idno: ' . $emp_idno);
                } else {
                    log_message('debug', 'No records found for emp_idno: ' . $emp_idno);
                    try {
                        $allRecords = $db->table('pending_trainings')->limit(5)->get()->getResultArray();
                        log_message('debug', 'Total records in table (unfiltered): ' . count($allRecords));
                        if (!empty($allRecords)) {
                            log_message('debug', 'Sample record emp_idno: ' . ($allRecords[0]['emp_idno'] ?? 'N/A'));
                        }
                    } catch (\Exception $allEx) {
                        log_message('error', 'Error getting all records: ' . $allEx->getMessage());
                    }
                }
                if (!empty($pending_trainings)) {
                    log_message('debug', 'First training record: ' . print_r($pending_trainings[0], true));
                }

                try {
                    $categories_model = new \App\Models\trainings\mdl_lib_training_categories();
                    $training_categories = $categories_model->getAllCategories();
                } catch (\Exception $e) {
                    log_message('error', 'Error loading training categories in load_pending_trainings: ' . $e->getMessage());
                    $training_categories = []; 
                }
                
                $data = [
                    'pending_trainings' => $pending_trainings,
                    'training_categories' => $training_categories,
                    'event_count' => $event_count,
                    'max_page' => $max_page,
                    'page' => $page,
                    'aa' => ($page - 1) * $limit,
                    'num' => count($pending_trainings)
                ];

                ob_end_clean();
                return view('trainings/pending_table', $data);
            } catch (\Exception $qe) {
                log_message('error', 'Database/model query error in load_pending_trainings: ' . $qe->getMessage());
                log_message('error', 'Query trace: ' . $qe->getTraceAsString());
                ob_end_clean();
                echo '<div class="alert alert-danger">';
                echo '<i class="fas fa-exclamation-triangle"></i> Error loading trainings: ' . esc($qe->getMessage());
                echo '</div>';
                return;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in load_pending_trainings: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            ob_end_clean();
            echo '<div class="alert alert-danger">';
            echo '<i class="fas fa-exclamation-triangle"></i> Error loading trainings: ' . esc($e->getMessage());
            echo '</div>';
        }
    }
    
    public function debug_pending_trainings()
    {
        try {
            $session_data = session()->get();
            $emp_idno = session()->get('emp_idno');
            $empid = session()->get('empid');
            
            $result = [
                'session_data' => $session_data,
                'emp_idno' => $emp_idno,
                'empid' => $empid,
                'database_connection' => false,
                'database_exists' => false,
                'table_exists' => false,
                'record_count' => 0,
                'sample_records' => []
            ];
            
            $db = \Config\Database::connect('default');
            if ($db) {
                $result['database_connection'] = true;
                
                $dbCheck = $db->query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = 'hr_lnd'");
                if ($dbCheck && $dbCheck->getNumRows() > 0) {
                    $result['database_exists'] = true;
                    
                    $tableCheck = $db->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'hr_lnd' AND TABLE_NAME = 'pending_trainings'");
                    if ($tableCheck && $tableCheck->getNumRows() > 0) {
                        $result['table_exists'] = true;
                        
                        if ($emp_idno) {
                            $count = $db->table('hr_lnd.pending_trainings')
                                      ->where('emp_idno', $emp_idno)
                                      ->countAllResults();
                            $result['record_count'] = $count;
                            
                            $records = $db->table('hr_lnd.pending_trainings pt')
                                        ->select('pt.*, pt.training_name, pt.training_datefrom, pt.training_dateto')
                                        ->where('pt.emp_idno', $emp_idno)
                                        ->limit(5)
                                        ->orderBy('pt.training_datefrom', 'DESC')
                                        ->get()
                                        ->getResultArray();
                            $result['sample_records'] = $records;
                        }
                    }
                }
            }
            
            return '<pre>' . print_r($result, true) . '</pre>';
            
        } catch (\Exception $e) {
            return '<pre>Error in debug: ' . $e->getMessage() . '\nTrace: ' . $e->getTraceAsString() . '</pre>';
        }
    }
    
    public function save_pending()
    {
        $response = [];
        
        try {
            $training_name = $this->request->getPost('training_name');
            $training_category_id = $this->request->getPost('training_category_id');
            $training_datefrom = $this->request->getPost('training_datefrom');
            $training_dateto = $this->request->getPost('training_dateto');
            $training_hours = $this->request->getPost('training_hours');
            $training_venue = $this->request->getPost('training_venue');
            $training_facilitator = $this->request->getPost('training_facilitator');
            $emp_idno = session()->get('emp_idno');
            
            if (empty($training_name) || empty($training_datefrom) || 
                empty($training_dateto) || empty($training_hours) || empty($training_venue) || empty($training_facilitator) || empty($emp_idno)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Missing required fields']);
            }
            
            if ($training_datefrom > $training_dateto) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Start date cannot be later than end date']);
            }
            
            if ($training_hours <= 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Hours must be greater than 0']);
            }
            
            $default_db = \Config\Database::connect('default');
            
            if (!$default_db) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database connection failed']);
            }
            
            $category_check = $default_db->table('lib_training_category')
                           ->where('id_training_category', $training_category_id)
                           ->where('is_deleted', 0)
                           ->where('is_visible', 1)
                           ->get()
                           ->getRow();
        
            if (!$category_check) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid training type selected']);
            }
            
            $default_db = \Config\Database::connect();
            
            $employee = $default_db->table('employees')
                      ->select('id_employee, emp_fname, emp_lname')
                      ->where('emp_idno', $emp_idno)
                      ->get()
                      ->getRowArray();
        
            $certificate_file = '';  
            if ($this->request->getFile('training_certificate') && $this->request->getFile('training_certificate')->isValid()) {
                $file = $this->request->getFile('training_certificate');
            
                $allowed_types = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
                $file_type = $file->getMimeType();
                
                if (!in_array($file_type, $allowed_types)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid file type. Only PDF, PNG, JPG, and JPEG files are allowed.']);
                }

                $file_size = $file->getSize() / 1024 / 1024; 
                if ($file_size > 5) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'File size exceeds 5MB. Please upload a smaller file.']);
                }
                                    
                $new_name = time() . '_' . $file->getName();
                                    
                $file->move(ROOTPATH . 'public/uploads/trainings/certificates', $new_name);
                                    
                if ($file->hasMoved()) {
                    $certificate_file = $new_name;
                    log_message('info', 'Certificate file uploaded successfully: ' . $new_name);
                } else {
                    log_message('error', 'Failed to move uploaded file. Error: ' . $file->getErrorString());
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload certificate file.']);
                }
            }
            
            $data = [
                'emp_idno' => $emp_idno,
                'employee_id' => $employee ? $employee['id_employee'] : null,
                'emp_fullname' => $employee ? trim(($employee['emp_fname'] ?? '') . ' ' . ($employee['emp_lname'] ?? '')) : '',
                'emp_lname' => $employee ? $employee['emp_lname'] : '',
                'training_name' => $training_name,
                'training_category_id' => $training_category_id,
                'training_datefrom' => $training_datefrom,
                'training_dateto' => $training_dateto,
                'training_hours' => $training_hours,
                'training_venue' => $training_venue,
                'training_facilitator' => $training_facilitator,
                'training_certificate_file' => $certificate_file,
                'is_approved' => 0,  
                'is_disapproved' => 0,  
                'added_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s')
            ];
            
            $result = $default_db->table('pending_trainings')->insert($data);
            
            if ($result) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Training submitted successfully']);
            } else {
                $error = $default_db->error();
                log_message('error', 'Failed to save training: ' . print_r($error, true));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save training: ' . $error['message']]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving pending training: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'An error occurred while saving the training: ' . $e->getMessage()]);
        }
    }
    
    public function get_pending_details($id)
    {
        try {
            log_message('debug', 'Getting pending training details for ID: ' . $id);
            
            $db = \Config\Database::connect('default');
            
            if (!$db) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database connection failed']);
            }
            
            $training = $db->table('pending_trainings')
                          ->where('id_pending_training', $id)
                          ->get()
                          ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Training not found']);
            }
            
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $training
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting pending training details: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    public function update_pending()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            $training_name = $this->request->getPost('training_name');
            $training_category_id = $this->request->getPost('training_category_id');
            $training_datefrom = $this->request->getPost('training_datefrom');
            $training_dateto = $this->request->getPost('training_dateto');
            $training_hours = $this->request->getPost('training_hours');
            $training_venue = $this->request->getPost('training_venue');
            $training_facilitator = $this->request->getPost('training_facilitator');
            $training_sponsor = $this->request->getPost('training_sponsor');
            $emp_idno = session()->get('emp_idno');
            
            if (empty($training_id) || empty($training_name) || empty($training_datefrom) || 
                empty($training_dateto) || empty($training_hours) || empty($training_venue) || empty($training_facilitator)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Missing required fields']);
            }
            
            if ($training_datefrom > $training_dateto) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Start date cannot be later than end date']);
            }
            
            if ($training_hours <= 0) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Hours must be greater than 0']);
            }
            
            $default_db = \Config\Database::connect('default');
            
            if (!$default_db) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database connection failed']);
            }
            
            $existing_training = $default_db->table('pending_trainings')
                                   ->where('id_pending_training', $training_id)
                                   ->where('emp_idno', $emp_idno)
                                   ->get()
                                   ->getRowArray();
            
            if (!$existing_training) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Training not found or unauthorized']);
            }
            
            $certificate_file = $existing_training['training_certificate_file'] ?? '';  
            if ($this->request->getFile('training_certificate') && $this->request->getFile('training_certificate')->isValid()) {
                $file = $this->request->getFile('training_certificate');
                
                $allowed_types = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
                $file_type = $file->getMimeType();
                
                if (!in_array($file_type, $allowed_types)) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid file type. Only PDF, PNG, JPG, and JPEG files are allowed.']);
                }
                
                $file_size = $file->getSize() / 1024 / 1024; 
                if ($file_size > 5) {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'File size exceeds 5MB. Please upload a smaller file.']);
                }
                
                if (!empty($certificate_file)) {
                    $old_file_path = ROOTPATH . 'public/uploads/trainings/certificates/' . $certificate_file;
                    if (file_exists($old_file_path)) {
                        unlink($old_file_path);
                        log_message('info', 'Old certificate file deleted: ' . $certificate_file);
                    }
                }
                                    
                $new_name = time() . '_' . $file->getName();
                                    
                $file->move(ROOTPATH . 'public/uploads/trainings/certificates', $new_name);
                                    
                if ($file->hasMoved()) {
                    $certificate_file = $new_name;
                    log_message('info', 'Certificate file uploaded successfully: ' . $new_name);
                } else {
                    log_message('error', 'Failed to move uploaded file. Error: ' . $file->getErrorString());
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload certificate file.']);
                }
            }
            
            $data = [
                'training_name' => $training_name,
                'training_category_id' => $training_category_id,
                'training_datefrom' => $training_datefrom,
                'training_dateto' => $training_dateto,
                'training_hours' => $training_hours,
                'training_venue' => $training_venue,
                'training_facilitator' => $training_facilitator,
                'training_sponsor' => $training_sponsor,
                'training_certificate_file' => $certificate_file,
                'updated_date' => date('Y-m-d H:i:s')
            ];
            
            $result = $default_db->table('pending_trainings')
                        ->where('id_pending_training', $training_id)
                        ->update($data);
            
            if ($result) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Training updated successfully']);
            } else {
                $error = $default_db->error();
                log_message('error', 'Failed to update training: ' . print_r($error, true));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update training: ' . $error['message']]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error updating pending training: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'An error occurred while updating the training: ' . $e->getMessage()]);
        }
    }
    
    public function delete_pending($id)
    {
        try {
            log_message('debug', 'Deleting pending training ID: ' . $id);
            
            $db = \Config\Database::connect('default');
            
            if (!$db) {
                log_message('error', 'Cannot connect to training database');
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database connection failed']);
            }
            
            $training = $db->table('pending_trainings')
                          ->where('id_pending_training', $id)
                          ->get()
                          ->getRowArray();
            
            if (!$training) {
                log_message('debug', 'Pending training not found for ID: ' . $id);
                return $this->response->setJSON(['status' => 'error', 'message' => 'Training not found']);
            }
            
            $emp_idno = session()->get('emp_idno');
            if ($emp_idno && $training['emp_idno'] != $emp_idno) {
                log_message('debug', 'User not authorized to delete training ID: ' . $id);
                return $this->response->setJSON(['status' => 'error', 'message' => 'Not authorized to delete this training']);
            }
            
            $result = $db->table('pending_trainings')
                        ->where('id_pending_training', $id)
                        ->delete();
            
            if ($result) {
                log_message('debug', 'Pending training deleted successfully: ' . $id);
                return $this->response->setJSON(['status' => 'success', 'message' => 'Training deleted successfully']);
            } else {
                log_message('error', 'Failed to delete pending training ID: ' . $id);
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete training']);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting pending training: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error deleting training: ' . $e->getMessage()]);
        }
    }
    
    public function get_pending_training($id)
    {
        try {
            log_message('debug', 'Getting pending training ID: ' . $id);
            
            $db = \Config\Database::connect('default');
            
            if (!$db) {
                log_message('error', 'Cannot connect to training database');
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database connection failed']);
            }
            
            $training = $db->table('pending_trainings')
                          ->select('id_pending_training, training_name, training_category_id as training_type_id, training_datefrom, training_dateto, training_hours, training_venue, training_facilitator, training_certificate_file')
                          ->where('id_pending_training', $id)
                          ->get()
                          ->getRowArray();
            
            if (!$training) {
                log_message('debug', 'Pending training not found for ID: ' . $id);
                return $this->response->setJSON(['status' => 'error', 'message' => 'Training not found']);
            }
            
            return $this->response->setJSON(['status' => 'success', 'training' => $training]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting pending training: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error getting training details: ' . $e->getMessage()]);
        }
    }
    
    public function serve_certificate($filename)
    {
        $filename = basename($filename);
        $filePath = ROOTPATH . 'public/uploads/trainings/certificates/' . $filename;
        
        if (!file_exists($filePath)) {
            log_message('error', 'Certificate file not found: ' . $filePath);
            return $this->response->setStatusCode(404)->setBody('File not found');
        }
        
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
        ];
        
        $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';
        
        return $this->response
            ->setContentType($contentType)
            ->setHeader('Content-Length', filesize($filePath))
            ->setHeader('Cache-Control', 'private, max-age=3600')
            ->setBody(file_get_contents($filePath));
    }
}