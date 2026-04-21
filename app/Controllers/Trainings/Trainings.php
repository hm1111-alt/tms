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
            
            foreach ($data['trainings'] as &$training) {
                $training['is_ongoing'] = (isset($training['status_id']) && $training['status_id'] == 4);
            }
            unset($training); 
            
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
     * Register in a training (requires login)
     */
    /**
     * Automatically update training status based on slot availability and registration deadline
     * Returns the new status name if changed, or current status if no change
     */
    private function autoUpdateTrainingStatus($training_id, $db)
    {
        try {
            // Get training details with current status
            $training = $db->table('lib_trainings lt')
                ->select('lt.id_training, lt.training_name, oti.status_id, oti.no_of_attendees, oti.max_no_of_attendees, oti.registration_deadline, ts.status_name')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return null;
            }
            
            $current_status = strtolower($training['status_name'] ?? '');
            $status_id = $training['status_id'] ?? 0;
            $max_capacity = $training['max_no_of_attendees'] ?? 0;
            $current_attendees = $training['no_of_attendees'] ?? 0;
            $registration_deadline = $training['registration_deadline'] ?? null;
            
            // Only auto-update if status is 'open' or 'closed'
            // Don't auto-update if status is upcoming, ongoing, or completed
            if (!in_array($current_status, ['open', 'closed'])) {
                return $current_status;
            }
            
            $new_status_id = $status_id;
            $status_changed = false;
            
            $status_open = $db->table('training_status')->where('status_name', 'open')->get()->getRowArray();
            $status_closed = $db->table('training_status')->where('status_name', 'closed')->get()->getRowArray();
            
            if (!$status_open || !$status_closed) {
                log_message('error', 'Could not find open/closed status in training_status table');
                return $current_status;
            }
            
            // Check if registration deadline has passed
            if ($registration_deadline) {
                $deadline = strtotime($registration_deadline);
                $now = time();
                
                if ($now >= $deadline && $current_status === 'open') {
                    // Deadline passed, close registration
                    $new_status_id = $status_closed['id'];
                    $status_changed = true;
                    log_message('info', "Auto-closed training {$training_id} ({$training['training_name']}) - Registration deadline passed: {$registration_deadline}");
                }
            }
            
            // Check slot availability - close if full
            if ($max_capacity > 0 && $current_attendees >= $max_capacity && $current_status === 'open') {
                // No slots available, close registration
                $new_status_id = $status_closed['id'];
                $status_changed = true;
                log_message('info', "Auto-closed training {$training_id} ({$training['training_name']}) - No slots available ({$current_attendees}/{$max_capacity})");
            }
            
            if ($max_capacity > 0 && $current_attendees < $max_capacity && $current_status === 'closed') {
                // Check if there's a registration deadline that hasn't passed yet
                $can_reopen = true;
                if ($registration_deadline) {
                    $deadline = strtotime($registration_deadline);
                    $now = time();
                    if ($now >= $deadline) {
                        $can_reopen = false; // Deadline passed, keep closed
                        log_message('info', "Training {$training_id} ({$training['training_name']}) - Cannot reopen, deadline passed: {$registration_deadline}");
                    }
                }
                
                if ($can_reopen) {
                    $new_status_id = $status_open['id'];
                    $status_changed = true;
                    log_message('info', "Auto-reopened training {$training_id} ({$training['training_name']}) - Slots available ({$current_attendees}/{$max_capacity})");
                }
            }
            
            // Update status if changed
            if ($status_changed && $new_status_id != $status_id) {
                $db->table('other_training_info')
                    ->where('training_id', $training_id)
                    ->update(['status_id' => $new_status_id]);
                
                // Get new status name
                $new_status = $db->table('training_status')
                    ->where('id', $new_status_id)
                    ->get()
                    ->getRowArray();
                
                $new_status_name = $new_status['status_name'] ?? $current_status;
                log_message('info', "Training {$training_id} status changed from '{$current_status}' to '{$new_status_name}'");
                
                return $new_status_name;
            }
            
            return $current_status;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in autoUpdateTrainingStatus: ' . $e->getMessage());
            return null;
        }
    }
    
    public function register($id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to join trainings.'
                ]);
            }
            session()->setFlashdata('error', 'Please login to join trainings.');
            return redirect()->to('/login');
        }
        
        try {
            $db = \Config\Database::connect();
            
            // Get training details
            $training = $db->table('lib_trainings lt')
                ->select('lt.*, ltc.training_category_name, ts.status_name')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('lt.id_training', $id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Training not found.'
                    ]);
                }
                session()->setFlashdata('error', 'Training not found.');
                return redirect()->to('/');
            }
            
            // Check if already registered
            $userid = session()->get('userid');
            $existing = $db->table('training_attendees ta')
                ->where('ta.training_id', $id)
                ->where('ta.user_id', $userid)
                ->get()
                ->getRowArray();
            
            if ($existing) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'You are already registered for this training.'
                    ]);
                }
                session()->setFlashdata('info', 'You are already registered for this training.');
                return redirect()->to('trainings/view/' . $id);
            }
            
            // Check if training has available slots
            $max_capacity = $training['max_no_of_attendees'] ?? 0;
            if ($max_capacity > 0) {
                // Get current registration count
                $current_count = $db->table('training_attendees')
                    ->where('training_id', $id)
                    ->countAllResults();
                
                if ($current_count >= $max_capacity) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Sorry, this training is already full. No slots available.'
                        ]);
                    }
                    session()->setFlashdata('error', 'Sorry, this training is already full. No slots available.');
                    return redirect()->to('trainings/view/' . $id);
                }
            }
            
            // Insert registration
            $enrollment_data = [
                'training_id' => $id,
                'user_id' => $userid
            ];
            
            $db->table('training_attendees')->insert($enrollment_data);
            
            // Update no_of_attendees count in other_training_info
            $db->table('other_training_info')
                ->where('training_id', $id)
                ->increment('no_of_attendees', 1);
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Successfully registered for ' . esc($training['training_name']) . '!'
                ]);
            }
            
            session()->setFlashdata('success', 'Successfully registered for ' . esc($training['training_name']) . '!');
            return redirect()->to('trainings/view/' . $id);
            
        } catch (\Exception $e) {
            log_message('error', 'Error registering for training: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to register. Please try again.'
                ]);
            }
            session()->setFlashdata('error', 'Failed to register. Please try again.');
            return redirect()->to('trainings/view/' . $id);
        }
    }

    public function cancel_registration($id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Please login to cancel registration.'
                ]);
            }
            session()->setFlashdata('error', 'Please login to cancel registration.');
            return redirect()->to('/login');
        }
        
        try {
            $db = \Config\Database::connect();
            
            // Get training details
            $training = $db->table('lib_trainings lt')
                ->select('lt.*, ltc.training_category_name, ts.status_name')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('lt.id_training', $id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Training not found.'
                    ]);
                }
                session()->setFlashdata('error', 'Training not found.');
                return redirect()->to('/');
            }
            
            // Check if enrolled
            $userid = session()->get('userid');
            $existing = $db->table('training_attendees ta')
                ->where('ta.training_id', $id)
                ->where('ta.user_id', $userid)
                ->get()
                ->getRowArray();
            
            if (!$existing) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'You are not enrolled in this training.'
                    ]);
                }
                session()->setFlashdata('info', 'You are not enrolled in this training.');
                return redirect()->to('trainings/view/' . $id);
            }
            
            // Delete enrollment
            $db->table('training_attendees')
                ->where('training_id', $id)
                ->where('user_id', $userid)
                ->delete();
            
            // Update no_of_attendees count in other_training_info (ensure it doesn't go below 0)
            $current_count = $db->table('other_training_info')
                ->select('no_of_attendees')
                ->where('training_id', $id)
                ->get()
                ->getRowArray();
            
            if ($current_count && $current_count['no_of_attendees'] > 0) {
                $db->table('other_training_info')
                    ->where('training_id', $id)
                    ->decrement('no_of_attendees', 1);
            }
            
            // Auto-update training status based on slot availability
            // $this->autoUpdateTrainingStatus($id, $db); // DISABLED - only runs on register/cancel
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Successfully unjoined from ' . esc($training['training_name']) . '!'
                ]);
            }
            
            session()->setFlashdata('success', 'Successfully unjoined from ' . esc($training['training_name']) . '!');
            return redirect()->to('trainings/view/' . $id);
            
        } catch (\Exception $e) {
            log_message('error', 'Error unjoining training: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to unjoin. Please try again.'
                ]);
            }
            session()->setFlashdata('error', 'Failed to unjoin. Please try again.');
            return redirect()->to('trainings/view/' . $id);
        }
    }

    public function index()
    {
        // Check user type
        $user_type = session()->get('user_type_name');
        
        log_message('debug', '=== TRAININGS INDEX DEBUG ===');
        log_message('debug', 'Current user_type: ' . ($user_type ?? 'NULL'));
        log_message('debug', 'Is admin check result: ' . (stripos($user_type, 'admin') !== false ? 'TRUE' : 'FALSE'));
        log_message('debug', 'Session data: ' . json_encode(session()->get()));
        
        if (stripos($user_type, 'admin') !== false) {
            try {
                $db = \Config\Database::connect();
                
                log_message('debug', 'Loading admin trainings...');
                
                // Get all trainings with category and status 
                $data['trainings'] = $db->table('lib_trainings lt')
                    ->select('lt.*, ltc.training_category_name, ts.status_name, oti.status_id')
                    ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                    ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                    ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                    ->orderBy('lt.training_added_date', 'DESC')
                    ->get()
                    ->getResultArray();
                
                log_message('debug', 'Found ' . count($data['trainings']) . ' trainings');
                $data['total_trainings'] = count($data['trainings']);
                
                $employee_id = session()->get('empid');
                $userid = session()->get('userid');
                
                log_message('debug', 'Employee ID: ' . ($employee_id ?? 'NULL'));
                log_message('debug', 'User ID: ' . ($userid ?? 'NULL'));
                
                $data['trainings_this_month'] = 0;
                $data['upcoming_trainings'] = 0;
                $data['pending_trainings'] = 0;
                $data['recent_trainings'] = [];
                
                if (!empty($employee_id)) {
                    // Get pending trainings
                    $data['pending_trainings'] = (int) $db->table('pending_trainings')
                        ->countAllResults();
                    
                    // Get trainings this month (by training_added_date)
                    $current_month = date('m');
                    $current_year = date('Y');
                    $data['trainings_this_month'] = (int) $db->table('lib_trainings lt')
                        ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                        ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                        ->where('YEAR(lt.training_added_date)', $current_year)
                        ->where('MONTH(lt.training_added_date)', $current_month)
                        ->countAllResults();
                    
                    // Get upcoming trainings
                    $today = date('Y-m-d');
                    $data['upcoming_trainings'] = (int) $db->table('lib_trainings lt')
                        ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                        ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                        ->where('lt.training_datefrom >', $today)
                        ->countAllResults();
                    
                    // Get recent trainings
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
                
                log_message('debug', 'Returning admin_list view');
                
                $data['hide_dashboard_stats'] = true;
                $data['hide_messages'] = true;
                
                return view('trainings/admin_list', $data);
            } catch (\Exception $e) {
                log_message('error', 'Error loading admin trainings: ' . $e->getMessage());
                log_message('error', 'Exception trace: ' . $e->getTraceAsString());
                $data['trainings'] = [];
                $data['total_trainings'] = 0;
                $data['trainings_this_month'] = 0;
                $data['upcoming_trainings'] = 0;
                $data['pending_trainings'] = 0;
                $data['recent_trainings'] = [];
                $data['hide_messages'] = true;
                return view('trainings/admin_list', $data);
            }
        } else {
            log_message('warning', 'User is not admin, loading browse_all_trainings');
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
            $data['trainings_this_month'] = 0;
            $data['upcoming_trainings'] = 0;
            $data['completed_trainings'] = 0;
            $data['recent_trainings'] = [];
            
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
                    ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                    ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                    ->where('ts.status_name IS NOT NULL', null, null, false)
                    ->where('ts.status_name != ""', null, null, false)
                    ->get()
                    ->getRow();
                $data['total_trainings'] = (int) ($total_result->count ?? 0);
                
                // Get trainings this month
                $current_month = date('Y-m');
                $this_month_result = $db->table('lib_trainings')
                    ->selectCount('id_training', 'count')
                    ->where('MONTH(training_datefrom)', $current_month)
                    ->get()
                    ->getRow();
                $data['trainings_this_month'] = (int) ($this_month_result->count ?? 0);
                
                // Get upcoming trainings count
                $upcoming_result = $db->table('lib_trainings lt')
                    ->selectCount('lt.id_training', 'count')
                    ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                    ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                    ->where('ts.status_name', 'upcoming')
                    ->get()
                    ->getRow();
                $data['upcoming_trainings'] = (int) ($upcoming_result->count ?? 0);
                
                // Get completed trainings count
                $completed_result = $db->table('lib_trainings lt')
                    ->selectCount('lt.id_training', 'count')
                    ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                    ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                    ->where('ts.status_name', 'completed')
                    ->get()
                    ->getRow();
                $data['completed_trainings'] = (int) ($completed_result->count ?? 0);
                
                // Get recent trainings (only those with status)
                $recent_result = $db->table('lib_trainings lt')
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
                $data['recent_trainings'] = $recent_result;
            }
            
            // Get all trainings from lib_trainings that have status in other_training_info (INNER JOIN to exclude those without status)
            $data['trainings'] = $db->table('lib_trainings lt')
                ->select('lt.*, ltc.training_category_name, ts.status_name, oti.status_id, oti.no_of_attendees, oti.max_no_of_attendees')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                ->where('ts.status_name IS NOT NULL', null, null, false)
                ->orderBy('lt.training_added_date', 'DESC')
                ->get()
                ->getResultArray();
            
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
                    
                    // Check if user has already marked attendance
                    $attendance_check = $db->table('training_attendance')
                        ->where('user_id', $userid)
                        ->where('training_id', $training_id)
                        ->countAllResults();
                    $training['has_attended'] = ($attendance_check > 0);
                    log_message('debug', 'has_attended for training ' . $training_id . ': ' . ($training['has_attended'] ? 'TRUE' : 'FALSE'));
                } else {
                    $training['is_enrolled'] = false;
                    $training['enrollment_status'] = null;
                    $training['has_attended'] = false;
                }
                
                $training['is_ongoing'] = (isset($training['status_id']) && $training['status_id'] == 4);
                log_message('debug', 'Training ' . $training_id . ' is_ongoing: ' . ($training['is_ongoing'] ? 'TRUE' : 'FALSE'));
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
                    ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                    ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                    ->where('ts.status_name IS NOT NULL', null, null, false)
                    ->where('ts.status_name != ""', null, null, false)
                    ->get()
                    ->getRow();
                $data['total_trainings'] = (int) ($total_result->count ?? 0);
            }
            
            if (stripos($user_type, 'admin') !== false) {
                $pending_list = $db->table('pending_trainings pt')
                    ->select('pt.*, ltc.training_category_name')
                    ->join('lib_training_category ltc', 'ltc.id_training_category = pt.training_category_id', 'left')
                    ->orderBy('pt.added_date', 'DESC')
                    ->get()
                    ->getResultArray();
                    
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
        log_message('debug', '=== MY_TRAININGS METHOD CALLED ===');
        log_message('debug', 'Accessed URL: ' . current_url());
        log_message('debug', 'Session data - userid: ' . var_export(session()->get('userid'), true));
        log_message('debug', 'Session data - user_type: ' . var_export(session()->get('user_type_name'), true));
        
        try {
            $db = \Config\Database::connect();
            $userid = session()->get('userid');
            
            // Get user type to determine what to show
            $user_type = session()->get('user_type_name');
            
            log_message('critical', 'MY_TRAININGS DEBUG - userid=' . ($userid ?? 'NULL') . ', user_type=' . ($user_type ?? 'NULL'));
            
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
                    ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                    ->join('training_status ts', 'ts.id = oti.status_id', 'inner')
                    ->where('ts.status_name IS NOT NULL', null, null, false)
                    ->where('ts.status_name != ""', null, null, false)
                    ->get()
                    ->getRow();
                $data['total_trainings'] = (int) ($total_result->count ?? 0);
            }
            
            // Initialize list variables to avoid undefined errors
            $data['active_trainings_list'] = [];
            $data['completed_trainings_list'] = [];
            $data['pending_trainings_list'] = [];
            
            if (empty($userid) && stripos($user_type, 'guest') !== false) {
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
                log_message('info', 'User without userid accessing My Trainings - userid is: ' . var_export($userid, true));
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
            
            $test_all = $db->table('training_attendees ta')
                ->select('lt.training_name, lt.training_datefrom, lt.training_dateto, oti.status_id')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->where('ta.user_id', $userid)
                ->get()
                ->getResultArray();
            
            log_message('debug', 'TEST - ALL trainings for user (no date filter): ' . count($test_all));
            log_message('debug', 'TEST DATA: ' . json_encode($test_all));
            
            // Get UPCOMING trainings (status = 'upcoming' OR status = 'open')
            $data['upcoming_trainings_list'] = $db->table('training_attendees ta')
                ->select('lt.*, ltc.training_category_name, ts.status_name')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('ta.user_id', $userid)
                ->where("(ts.status_name = 'upcoming' OR ts.status_name = 'open')", null, false)
                ->orderBy('lt.training_datefrom', 'ASC')
                ->get()
                ->getResultArray();
            
            log_message('debug', 'Upcoming trainings count: ' . count($data['upcoming_trainings_list']));
            if (!empty($data['upcoming_trainings_list'])) {
                log_message('debug', 'Upcoming trainings data: ' . json_encode($data['upcoming_trainings_list']));
            } else {
                log_message('debug', 'No upcoming trainings found - checking if user has ANY trainings');
                $any_trainings = $db->table('training_attendees')
                    ->where('user_id', $userid)
                    ->countAllResults();
                log_message('debug', 'User has ' . $any_trainings . ' total trainings in attendees table');
            }
            
            // Get ONGOING trainings (status = 'ongoing')
            $data['ongoing_trainings_list'] = $db->table('training_attendees ta')
                ->select('lt.*, ltc.training_category_name, ts.status_name')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('ta.user_id', $userid)
                ->where('ts.status_name', 'ongoing')
                ->orderBy('lt.training_datefrom', 'ASC')
                ->get()
                ->getResultArray();
            
            // Check attendance status for each ongoing training
            foreach ($data['ongoing_trainings_list'] as &$ongoing) {
                // Check if user has already marked attendance
                $attendance_check = $db->table('training_attendance')
                    ->where('user_id', $userid)
                    ->where('training_id', $ongoing['id_training'])
                    ->countAllResults();
                
                $ongoing['has_attended'] = ($attendance_check > 0);
            }
            unset($ongoing); // Break reference
            
            log_message('debug', 'Ongoing trainings count: ' . count($data['ongoing_trainings_list']));
            if (!empty($data['ongoing_trainings_list'])) {
                log_message('debug', 'Ongoing trainings data: ' . json_encode($data['ongoing_trainings_list']));
            }
            
            // Get completed trainings (status = 'completed' OR date has passed AND status != 'ongoing'/'open'/'upcoming')
            log_message('debug', 'Querying completed trainings for user_id: ' . $userid);
            
            $data['completed_trainings_list'] = $db->table('training_attendees ta')
                ->select('lt.*, ltc.training_category_name, ts.status_name')
                ->join('lib_trainings lt', 'lt.id_training = ta.training_id')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('ta.user_id', $userid)
                ->where("(ts.status_name = 'completed' OR ts.status_name = 'closed')", null, false)
                ->orderBy('lt.training_dateto', 'DESC')
                ->get()
                ->getResultArray();
            
            // Check if user has already submitted feedback for each completed training
            foreach ($data['completed_trainings_list'] as &$training) {
                $feedback_check = $db->table('training_feedback')
                    ->where('user_id', $userid)
                    ->where('training_id', $training['id_training'])
                    ->countAllResults();
                
                $training['has_feedback'] = ($feedback_check > 0);
            }
            unset($training); // Break reference
            
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
            log_message('error', 'CRITICAL ERROR in my_trainings(): ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            $data['upcoming_trainings_list'] = [];
            $data['ongoing_trainings_list'] = [];
            $data['completed_trainings_list'] = [];
            $data['pending_trainings_list'] = [];
            $data['total_upcoming'] = 0;
            $data['total_ongoing'] = 0;
            $data['total_completed'] = 0;
            $data['total_pending'] = 0;
        }
        
        $data['hide_quick_actions'] = true;
        $data['hide_welcome_message'] = true;
        $data['hide_header_count'] = true;
        
        // Set custom header titles for My Trainings page
        $data['page_title'] = 'My Trainings';
        $data['portal_name'] = 'Dashboard';
        $data['show_green_header'] = true;
        
        log_message('critical', 'About to render my_trainings view');
        log_message('critical', 'Data keys: ' . implode(', ', array_keys($data)));
        log_message('critical', 'Upcoming count: ' . ($data['total_upcoming'] ?? 'NOT SET'));
        log_message('critical', 'Ongoing count: ' . ($data['total_ongoing'] ?? 'NOT SET'));
        log_message('critical', 'Completed count: ' . ($data['total_completed'] ?? 'NOT SET'));
        
        try {
            $view = view('trainings/my_trainings', $data);
            log_message('critical', 'View rendered successfully, length: ' . strlen($view) . ' bytes');
            
            if (strpos($view, '<!DOCTYPE html>') === false) {
                log_message('error', 'LAYOUT NOT APPLIED! View does not contain DOCTYPE');
            } else {
                log_message('info', 'Layout appears to be applied correctly');
            }
            
            return $view;
        } catch (\Exception $e) {
            log_message('critical', 'ERROR RENDERING VIEW: ' . $e->getMessage());
            log_message('critical', 'Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
    
    public function add()
    {
        if ($this->request->getMethod() === 'post') {
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
                ],
                'training_with_cert' => [
                    'label' => 'With Certificate',
                    'rules' => 'permit_empty|in_list[0,1]'
                ],
                'session_date.*' => [
                    'label' => 'Session Date',
                    'rules' => 'required|valid_date'
                ],
                'session_start_time.*' => [
                    'label' => 'Session Start Time',
                    'rules' => 'required'
                ],
                'session_end_time.*' => [
                    'label' => 'Session End Time',
                    'rules' => 'required'
                ]
            ];
            
            if ($this->validate($rules)) {
                try {
                    // Validate that training hours matches sum of session durations
                    $sessionDates = $this->request->getPost('session_date');
                    $sessionStartTimes = $this->request->getPost('session_start_time');
                    $sessionEndTimes = $this->request->getPost('session_end_time');
                    $trainingHours = $this->request->getPost('training_hours');
                    
                    if (is_array($sessionDates) && !empty($sessionDates)) {
                        $totalSessionMinutes = 0;
                        
                        for ($i = 0; $i < count($sessionDates); $i++) {
                            if (!empty($sessionStartTimes[$i]) && !empty($sessionEndTimes[$i])) {
                                $startParts = explode(':', $sessionStartTimes[$i]);
                                $startMinutes = intval($startParts[0]) * 60 + intval($startParts[1]);

                                $endParts = explode(':', $sessionEndTimes[$i]);
                                $endMinutes = intval($endParts[0]) * 60 + intval($endParts[1]);
                                
                                $durationMinutes = $endMinutes - $startMinutes;
                            
                                if ($durationMinutes < 0) {
                                    $durationMinutes += 24 * 60;
                                }
                                
                                $totalSessionMinutes += $durationMinutes;
                            }
                        }
                        
                        // Convert to hours
                        $totalSessionHours = $totalSessionMinutes / 60;
                        
                        // Check if training hours matches session hours 
                        if (abs($totalSessionHours - floatval($trainingHours)) > 0.1) {
                            session()->setFlashdata('error', 
                                sprintf('Training hours (%.2f hrs) does not match the sum of session durations (%.2f hrs). Please adjust either the training hours or session times.', 
                                    floatval($trainingHours), $totalSessionHours));
                            return redirect()->back()->withInput();
                        }
                    } else {
                        session()->setFlashdata('error', 'At least one session is required for the training.');
                        return redirect()->back()->withInput();
                    }
                    
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
                        'training_hours' => $this->request->getPost('training_hours'),
                        'training_facilitator' => $this->request->getPost('training_facilitator'),
                        'training_venue' => $this->request->getPost('training_venue'),
                        'training_is_local' => $this->request->getPost('training_is_local') ?? 0,
                        'training_require_upload' => $this->request->getPost('training_require_upload') ?? 0,
                        'training_require_feedback' => $this->request->getPost('training_require_feedback') ?? 0,
                        'training_with_cert' => $this->request->getPost('training_with_cert') ?? 0,
                        'training_refno' => $refno,
                        'training_added_date' => date('Y-m-d H:i:s'),
                        'training_added_by' => session()->get('empid')
                    ];
                    
                    log_message('info', 'Inserting training data: ' . json_encode($training_data));
                    
                    $db->table('lib_trainings')->insert($training_data);
                    $training_id = $db->insertID();
                    
                    log_message('info', 'Training inserted with ID: ' . $training_id);
                    
                    // Insert into other_training_info for status and attendee management
                    $other_info_data = [
                        'training_id' => $training_id,
                        'status_id' => 1, // Default to "upcoming" status
                        'registration_deadline' => $this->request->getPost('training_deadline') ?: null,
                        'no_of_attendees' => 0,
                        'max_no_of_attendees' => $this->request->getPost('training_attendees') ?: null
                    ];
                    
                    log_message('info', 'Inserting other_training_info data: ' . json_encode($other_info_data));
                    $db->table('other_training_info')->insert($other_info_data);
                    
                    log_message('debug', 'other_training_info inserted successfully');
                    
                    // Insert multiple training descriptions if provided
                    $descriptions = $this->request->getPost('training_description');
                    if (is_array($descriptions) && !empty($descriptions)) {
                        foreach ($descriptions as $desc) {
                            if (!empty(trim($desc))) {
                                $db->table('lib_training_des')->insert([
                                    'training_id' => $training_id,
                                    'training_description' => trim($desc)
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
                    
                    // Insert training sessions if provided
                    $sessionDates = $this->request->getPost('session_date');
                    $sessionStartTimes = $this->request->getPost('session_start_time');
                    $sessionEndTimes = $this->request->getPost('session_end_time');
                    
                    if (is_array($sessionDates) && !empty($sessionDates)) {
                        for ($i = 0; $i < count($sessionDates); $i++) {
                            if (!empty($sessionDates[$i]) && !empty($sessionStartTimes[$i]) && !empty($sessionEndTimes[$i])) {
                                $db->table('training_sessions')->insert([
                                    'training_id' => $training_id,
                                    'session_date' => $sessionDates[$i],
                                    'session_start_time' => $sessionStartTimes[$i],
                                    'session_end_time' => $sessionEndTimes[$i]
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
                log_message('warning', 'Validation failed for training creation');
                $data['validation'] = $this->validator;
                
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
                log_message('debug', 'Not found in pending_trainings, checking lib_trainings...');
                $training = $db->table('lib_trainings lt')
                              ->select('lt.*, ltc.training_category_name, ts.status_name, oti.*')
                              ->join('lib_training_category ltc', 'lt.training_category_id = ltc.id_training_category', 'left')
                              ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                              ->join('training_status ts', 'ts.id = oti.status_id', 'left')
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
            
            $training['is_ongoing'] = (isset($training['status_id']) && $training['status_id'] == 4);
            
            if (!$training['is_ongoing']) {
                $active_session_count = $db->table('training_sessions')
                    ->where('training_id', $id)
                    ->where('started_at IS NOT NULL')
                    ->countAllResults();
                
                if ($active_session_count > 0) {
                    $training['is_ongoing'] = true;
                    log_message('debug', 'Training ' . $id . ' marked as ongoing because ' . $active_session_count . ' session(s) have started');
                }
            }
            
            log_message('debug', 'Training ' . $id . ' is_ongoing: ' . ($training['is_ongoing'] ? 'TRUE' : 'FALSE'));
            
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
                
                // Check if user has already marked attendance
                $attendance_check = $db->table('training_attendance')
                    ->where('user_id', $userid)
                    ->where('training_id', $id)
                    ->countAllResults();
                $training['has_attended'] = ($attendance_check > 0);
                log_message('debug', 'User attendance status for training ' . $id . ': ' . ($training['has_attended'] ? 'ATTENDED' : 'NOT ATTENDED'));
                
                // Fetch sessions for enrolled users 
                if ($training['is_enrolled']) {
                    log_message('debug', 'User is enrolled, fetching sessions for training ' . $id);
                    $training['sessions'] = $db->table('training_sessions')
                        ->where('training_id', $id)
                        ->orderBy('session_date', 'ASC')
                        ->orderBy('session_start_time', 'ASC')
                        ->get()
                        ->getResultArray();
                    
                    // Check attendance for each session
                    foreach ($training['sessions'] as &$session) {
                        $session_attendance = $db->table('training_attendance')
                            ->where('user_id', $userid)
                            ->where('session_id', $session['id'])
                            ->get()
                            ->getRowArray();
                        $session['has_attended'] = !empty($session_attendance);
                        log_message('debug', 'Session ' . $session['id'] . ' attended: ' . ($session['has_attended'] ? 'YES' : 'NO'));
                    }
                }
            } else {
                $training['is_enrolled'] = false;
                $training['has_attended'] = false;
            }
            
            // Dynamically calculate the actual attendee count from training_attendees table
            $actual_attendee_count = $db->table('training_attendees')
                ->where('training_id', $id)
                ->countAllResults();
            $training['no_of_attendees'] = $actual_attendee_count;
            
            // Auto-update training status based on slot availability and registration deadline
            // This ensures the status is always current when viewing the training
            // $this->autoUpdateTrainingStatus($id, $db); // DISABLED 
            
            log_message('debug', 'Training ' . $id . ' - Stored attendees: ' . ($training['no_of_attendees'] ?? 'N/A') . ', Actual count: ' . $actual_attendee_count);
            
            $data = [
                'training' => $training,
                'training_type' => $training_type
            ];
            
            log_message('debug', 'Loading training_details view');
            
            // Check if user is logged in 
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
            
            // Delete related records first 
            // Delete attendance photos and records
            $db->table('training_attendance')->delete(['training_id' => $id]);
            
            // Delete training sessions
            $db->table('training_sessions')->delete(['training_id' => $id]);
            
            // Delete training attendees
            $db->table('training_attendees')->delete(['training_id' => $id]);
            
            // Delete other training info
            $db->table('other_training_info')->delete(['training_id' => $id]);
            
            // Delete descriptions and learnings
            $db->table('lib_training_des')->delete(['training_id' => $id]);
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
    
    /**
     * Check and update status for all trainings (can be called via cron or manually)
     */
    public function check_status_updates()
    {
        try {
            $db = \Config\Database::connect();
            
            // Get all trainings with open or closed status
            $trainings = $db->table('lib_trainings lt')
                ->select('lt.id_training, lt.training_name, oti.status_id, ts.status_name')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->whereIn('ts.status_name', ['open', 'closed'])
                ->get()
                ->getResultArray();
            
            $updated = [];
            
            foreach ($trainings as $training) {
                $new_status = $this->autoUpdateTrainingStatus($training['id_training'], $db);
                if ($new_status && $new_status !== strtolower($training['status_name'])) {
                    $updated[] = [
                        'training_id' => $training['id_training'],
                        'training_name' => $training['training_name'],
                        'old_status' => $training['status_name'],
                        'new_status' => $new_status
                    ];
                }
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status check completed',
                'updated_count' => count($updated),
                'updated' => $updated
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in check_status_updates: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to check status updates: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Toggle registration open/close for upcoming trainings
     */
    public function toggle_registration()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            $action = $this->request->getPost('action'); // 'open' or 'close'
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Check if training exists and get status
            $training = $db->table('lib_trainings lt')
                ->select('lt.id_training, lt.training_name, oti.status_id, ts.status_name')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training not found.'
                ]);
            }
            
            // Get status IDs from training_status table
            // Status mapping: 1=upcoming, 2=open, 3=closed, 4=ongoing, 5=completed
            $currentStatusId = (int)$training['status_id'];
            $currentStatusName = strtolower($training['status_name']);
            $newStatusId = null;
            $newStatusName = '';
            
            // Determine new status based on action
            if ($action === 'open') {
                // Change to "open" status (status_id = 2)
                $newStatusId = 2;
                $newStatusName = 'open';
            } else if ($action === 'close') {
                // Change to "closed" status (status_id = 3)
                $newStatusId = 3;
                $newStatusName = 'closed';
            }
            
            if ($newStatusId === null || !in_array($currentStatusName, ['upcoming', 'open', 'closed'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid action or training status.'
                ]);
            }
            
            // Update the training status in other_training_info table
            $update_data = [
                'status_id' => $newStatusId
            ];
            
            $db->table('other_training_info')
                ->where('training_id', $training_id)
                ->update($update_data);
            
            log_message('info', 'Registration ' . $action . 'ed for training: ' . $training['training_name'] . 
                       ' (Status changed from ' . $currentStatusName . ' to ' . $newStatusName . ')');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Registration successfully ' . $action . 'ed. Status is now ' . ucfirst($newStatusName) . '.',
                'new_status' => $newStatusName,
                'new_status_id' => $newStatusId
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error toggling registration: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update registration status: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Start training - change status from open to ongoing
     */
    public function start_training()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Check if training exists and is currently open
            $training = $db->table('lib_trainings lt')
                ->select('lt.id_training, lt.training_name, oti.status_id')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training not found.'
                ]);
            }
            
            // Verify training is currently open (status_id = 2)
            if ($training['status_id'] != 2) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Only trainings with "open" status can be started.'
                ]);
            }
            
            // Change status to ongoing (status_id = 4) in other_training_info table
            $update_data = [
                'status_id' => 4  // ongoing
            ];
            
            $db->table('other_training_info')
                ->where('training_id', $training_id)
                ->update($update_data);
            
            log_message('info', 'Training started: ' . $training['training_name'] . ' (changed from open to ongoing)');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Training successfully started. Status is now ongoing.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error starting training: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to start training: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Start a specific scheduled session
     */
    public function start_specific_session()
    {
        try {
            $session_id = $this->request->getPost('session_id');
            
            if (empty($session_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Get session details
            $session = $db->table('training_sessions')
                ->select('training_sessions.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = training_sessions.training_id', 'left')
                ->where('training_sessions.id', $session_id)
                ->get()
                ->getRowArray();
            
            if (!$session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session not found.'
                ]);
            }
            
            // Check if already started
            if (!empty($session['session_start_time']) && empty($session['session_end_time'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This session is already in progress.'
                ]);
            }
            
            // Check if there's another active session in the same training
            $active_session = $db->table('training_sessions')
                ->where('training_id', $session['training_id'])
                ->where('id !=', $session_id)  // Exclude current session
                ->where('session_start_time IS NOT NULL')
                ->where('session_end_time IS NULL')
                ->get()
                ->getRowArray();
            
            if ($active_session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot start this session. Another session in this training is still ongoing. Please end the active session first.'
                ]);
            }
            
            // Generate unique 8-character code (uppercase letters and numbers)
            $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Removed similar looking chars (I, O, 1, 0)
            $codeLength = 8;
            $randomCode = '';
            for ($i = 0; $i < $codeLength; $i++) {
                $randomCode .= $characters[random_int(0, strlen($characters) - 1)];
            }
            
            // Update session with attendance code and clear end times
            // DO NOT change session_start_time - that's the scheduled time
            // Only set started_at (actual start timestamp) and clear ended_at
            $update_data = [
                'session_code' => $randomCode,
                'started_at' => date('Y-m-d H:i:s')
            ];
            
            log_message('info', 'Before update - Session ID: ' . $session_id);
            
            // Use raw query to ensure NULL values are properly set
            $sql = "UPDATE training_sessions 
                    SET session_code = ?, 
                        started_at = ?, 
                        session_end_time = NULL, 
                        ended_at = NULL 
                    WHERE id = ?";
            
            $result = $db->query($sql, [$randomCode, date('Y-m-d H:i:s'), $session_id]);
            
            log_message('info', 'Update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            // Verify the update
            $verify = $db->table('training_sessions')
                ->where('id', $session_id)
                ->get()
                ->getRowArray();
            
            log_message('info', 'After update verification - Session ID: ' . $session_id . 
                       ', Scheduled Start: ' . $verify['session_start_time'] . 
                       ', Started At: ' . ($verify['started_at'] ?? 'NULL') . 
                       ', End Time: ' . ($verify['session_end_time'] ?? 'NULL') .
                       ', Ended At: ' . ($verify['ended_at'] ?? 'NULL') .
                       ', Code: ' . ($verify['session_code'] ?? 'NULL'));
            
            // Check current training status and update to ongoing if closed
            $training_status = $db->table('other_training_info oti')
                ->select('oti.status_id, ts.status_name')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('oti.training_id', $session['training_id'])
                ->get()
                ->getRowArray();
            
            if ($training_status && strtolower($training_status['status_name']) === 'closed') {
                $db->table('other_training_info')
                    ->where('training_id', $session['training_id'])
                    ->update(['status_id' => 4]);
                
                log_message('info', 'Training status changed from closed to ongoing for training ID: ' . $session['training_id']);
            }
            
            log_message('info', 'Session started: ' . $session_id . ' with code: ' . $randomCode);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session started successfully.',
                'session_code' => $randomCode,
                'session_date' => $session['session_date'],
                'start_time' => date('h:i A')
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error starting session: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to start session: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * End a specific active session
     */
    public function end_specific_session()
    {
        try {
            $session_id = $this->request->getPost('session_id');
            
            if (empty($session_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Get session details
            $session = $db->table('training_sessions')
                ->where('id', $session_id)
                ->get()
                ->getRowArray();
            
            if (!$session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session not found.'
                ]);
            }
            
            // Check if session is active (has been started but not ended)
            if (empty($session['started_at']) || !empty($session['ended_at'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This session is not active.'
                ]);
            }
            
            // Update session with actual end time
            $update_data = [
                'ended_at' => date('Y-m-d H:i:s')
            ];
            
            $db->table('training_sessions')
                ->where('id', $session_id)
                ->update($update_data);
            
            log_message('info', 'Session ended: ' . $session_id . ' at ' . $update_data['ended_at']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session ended successfully.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error ending session: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to end session: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Start a new session for an ongoing training (legacy - creates unscheduled session)
     */
    public function start_session()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Check if training exists and is ongoing
            $training = $db->table('lib_trainings lt')
                ->select('lt.id_training, lt.training_name, oti.status_id')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training not found.'
                ]);
            }
            
            // Verify training is ongoing (status_id = 4)
            if ($training['status_id'] != 4) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Only ongoing trainings can have sessions.'
                ]);
            }
            
            // Create a new session with current date and time
            $session_data = [
                'training_id' => $training_id,
                'session_date' => date('Y-m-d'),
                'session_start_time' => date('H:i:s'),
                'session_end_time' => null
            ];
            
            $db->table('training_sessions')->insert($session_data);
            $session_id = $db->insertID();
            
            log_message('info', 'Session started for training: ' . $training['training_name'] . ' (Session ID: ' . $session_id . ')');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session started successfully.',
                'session_id' => $session_id
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error starting session: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to start session: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Close the current active session for a training
     */
    public function close_session()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Find the latest active session (no end_time)
            $active_session = $db->table('training_sessions ts')
                ->select('ts.id, ts.session_date, ts.session_start_time')
                ->where('ts.training_id', $training_id)
                ->where('ts.session_end_time IS NULL', null, null, false)
                ->orderBy('ts.session_date', 'DESC')
                ->orderBy('ts.session_start_time', 'DESC')
                ->get()
                ->getRowArray();
            
            if (!$active_session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No active session found to close.'
                ]);
            }
            
            // Update the session with end time
            $update_data = [
                'session_end_time' => date('H:i:s')
            ];
            
            $db->table('training_sessions')
                ->where('id', $active_session['id'])
                ->update($update_data);
            
            log_message('info', 'Session closed: ' . $active_session['id'] . ' (Started: ' . $active_session['session_date'] . ' ' . $active_session['session_start_time'] . ')');
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session closed successfully.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error closing session: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to close session: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Save multiple sessions at once
     */
    public function save_multiple_sessions()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            $sessions_json = $this->request->getPost('sessions');
            
            // Decode JSON string to array
            $sessions = !empty($sessions_json) ? json_decode($sessions_json, true) : [];
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            if (empty($sessions) || !is_array($sessions)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'No sessions provided.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Verify training exists and is ongoing
            $training = $db->table('lib_trainings lt')
                ->select('lt.id_training, lt.training_name, oti.status_id')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training not found.'
                ]);
            }
            
            if ($training['status_id'] != 4) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Only ongoing trainings can have scheduled sessions.'
                ]);
            }
            
            // Validate and prepare sessions
            $insert_data = [];
            foreach ($sessions as $index => $session) {
                if (empty($session['session_date']) || empty($session['start_time']) || empty($session['end_time'])) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Session #' . ($index + 1) . ' has missing fields.'
                    ]);
                }
                
                // Validate end time is after start time
                if ($session['end_time'] <= $session['start_time']) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Session #' . ($index + 1) . ': End time must be after start time.'
                    ]);
                }
                
                $insert_data[] = [
                    'training_id' => $training_id,
                    'session_date' => $session['session_date'],
                    'session_start_time' => $session['start_time'],
                    'session_end_time' => $session['end_time']
                ];
            }
            
            // Insert all sessions using batch insert
            if (!empty($insert_data)) {
                $db->table('training_sessions')->insertBatch($insert_data);
            }
            
            $count = count($insert_data);
            log_message('info', 'Added ' . $count . ' session(s) for training: ' . $training['training_name']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Successfully added ' . $count . ' session(s).',
                'sessions_added' => $count
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving multiple sessions: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save sessions: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Save a single session
     */
    public function save_single_session()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            $session_date = $this->request->getPost('session_date');
            $start_time = $this->request->getPost('start_time');
            $end_time = $this->request->getPost('end_time');
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            if (empty($session_date) || empty($start_time) || empty($end_time)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Date, start time, and end time are required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Verify training exists
            $training = $db->table('lib_trainings lt')
                ->select('lt.id_training, lt.training_name, oti.status_id')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training not found.'
                ]);
            }
            
            // Validate times
            if ($start_time >= $end_time) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'End time must be after start time.'
                ]);
            }
            
            // Insert session
            $session_data = [
                'training_id' => $training_id,
                'session_date' => $session_date,
                'session_start_time' => $start_time,
                'session_end_time' => $end_time
            ];
            
            $db->table('training_sessions')->insert($session_data);
            
            log_message('info', 'Session added for training: ' . $training['training_name'] . ' on ' . $session_date);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session added successfully.',
                'session_id' => $db->insertID()
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving session: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save session: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Update a session
     */
    public function update_session()
    {
        try {
            $session_id = $this->request->getPost('session_id');
            $session_date = $this->request->getPost('session_date');
            $start_time = $this->request->getPost('start_time');
            $end_time = $this->request->getPost('end_time');
            
            if (empty($session_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session ID is required.'
                ]);
            }
            
            if (empty($session_date) || empty($start_time)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Date and start time are required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Verify session exists
            $session = $db->table('training_sessions')
                ->select('id, training_id')
                ->where('id', $session_id)
                ->get()
                ->getRowArray();
            
            if (!$session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session not found.'
                ]);
            }
            
            // If end time is provided, validate it
            if (!empty($end_time) && $start_time >= $end_time) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'End time must be after start time.'
                ]);
            }
            
            // Update session
            $update_data = [
                'session_date' => $session_date,
                'session_start_time' => $start_time
            ];
            
            if (!empty($end_time)) {
                $update_data['session_end_time'] = $end_time;
            }
            
            $db->table('training_sessions')
                ->where('id', $session_id)
                ->update($update_data);
            
            log_message('info', 'Session updated: ' . $session_id);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session updated successfully.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error updating session: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update session: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Delete a session
     */
    public function delete_session()
    {
        try {
            $session_id = $this->request->getPost('session_id');
            
            if (empty($session_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Verify session exists
            $session = $db->table('training_sessions')
                ->select('id, training_id')
                ->where('id', $session_id)
                ->get()
                ->getRowArray();
            
            if (!$session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session not found.'
                ]);
            }
            
            // Check if session has attendees (optional - you may want to prevent deletion if there are attendees)
            $attendee_count = $db->table('training_attendance')
                ->where('session_id', $session_id)
                ->countAllResults();
            
            if ($attendee_count > 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cannot delete session with ' . $attendee_count . ' attendance record(s). Please delete attendance records first.'
                ]);
            }
            
            // Delete session
            $db->table('training_sessions')
                ->where('id', $session_id)
                ->delete();
            
            log_message('info', 'Session deleted: ' . $session_id);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Session deleted successfully.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error deleting session: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to delete session: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get attendance for a session
     */
    public function get_session_attendance()
    {
        try {
            $session_id = $this->request->getPost('session_id');
            
            if (empty($session_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Get session details
            $session = $db->table('training_sessions')
                ->select('training_sessions.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = training_sessions.training_id', 'left')
                ->where('training_sessions.id', $session_id)
                ->get()
                ->getRowArray();
            
            if (!$session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session not found.'
                ]);
            }
            
            // Get ALL enrolled attendees for this training
            $enrolled_attendees = $db->table('training_attendees ta')
                ->select('ta.user_id, u.userid, u.username, u.employee_idno, u.emp_fullname')
                ->join('users u', 'u.userid = ta.user_id', 'left')
                ->where('ta.training_id', $session['training_id'])
                ->get()
                ->getResultArray();
            
            // Get attendance records for this specific session
            $attendance_records = $db->table('training_attendance')
                ->select('id, user_id, attendance_time, attendance_date, is_verified, attendance_file')
                ->where('session_id', $session_id)
                ->get()
                ->getResultArray();
            
            // Create a map of user_id to attendance record
            $attendance_map = [];
            foreach ($attendance_records as $record) {
                $attendance_map[$record['user_id']] = $record;
            }
            
            $attendees = [];
            foreach ($enrolled_attendees as $enrolled) {
                $user_id = $enrolled['user_id'];
                $attendance = isset($attendance_map[$user_id]) ? $attendance_map[$user_id] : null;
                
                $display_name = !empty($enrolled['emp_fullname']) ? $enrolled['emp_fullname'] : ($enrolled['username'] ?? 'User ' . $user_id);
                
                $attendee_data = [
                    'user_id' => $user_id,
                    'userid' => $enrolled['userid'],
                    'username' => $enrolled['username'],
                    'employee_idno' => $enrolled['employee_idno'],
                    'emp_fullname' => $enrolled['emp_fullname'],
                    'full_name' => $display_name,
                    'has_attendance' => !empty($attendance),
                    'attendance_time' => $attendance ? $attendance['attendance_time'] : null,
                    'attendance_date' => $attendance ? $attendance['attendance_date'] : null,
                    'is_verified' => $attendance ? $attendance['is_verified'] : null,
                    'attendance_id' => $attendance ? $attendance['id'] : null,
                    'attendance_file' => $attendance ? $attendance['attendance_file'] : null
                ];
                
                $attendees[] = $attendee_data;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'session' => $session,
                'attendees' => $attendees
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting attendance: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load attendance: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Display session attendees as a full page
     */
    public function session_attendees($training_id, $session_id)
    {
        // Check if user is logged in and is admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $user_type = session()->get('user_type_name');
        if (stripos($user_type, 'admin') === false) {
            session()->setFlashdata('error', 'Access denied. Admin privileges required.');
            return redirect()->to('dashboard');
        }
        
        try {
            $db = \Config\Database::connect();
            
            // Get training details
            $data['training'] = $db->table('lib_trainings lt')
                ->select('lt.*, oti.status_id, ts.status_name')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$data['training']) {
                session()->setFlashdata('error', 'Training not found.');
                return redirect()->to('trainings');
            }
            
            // Get session details
            $data['session'] = $db->table('training_sessions')
                ->where('id', $session_id)
                ->where('training_id', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$data['session']) {
                session()->setFlashdata('error', 'Session not found.');
                return redirect()->to('trainings/sessions/' . $training_id);
            }
            
            // Get all enrolled attendees for this training
            $enrolled_attendees = $db->table('training_attendees ta')
                ->select('ta.user_id, u.userid, u.username, u.employee_idno, u.emp_fullname')
                ->join('users u', 'u.userid = ta.user_id', 'left')
                ->where('ta.training_id', $training_id)
                ->get()
                ->getResultArray();
            
            // Get attendance records for this specific session
            $attendance_records = $db->table('training_attendance')
                ->select('id, user_id, attendance_time, attendance_date, is_verified, attendance_file')
                ->where('session_id', $session_id)
                ->get()
                ->getResultArray();
            
            // Create a map of user_id to attendance record
            $attendance_map = [];
            foreach ($attendance_records as $record) {
                $attendance_map[$record['user_id']] = $record;
            }
            
            // Build complete attendees list with attendance status
            $data['attendees'] = [];
            foreach ($enrolled_attendees as $enrolled) {
                $user_id = $enrolled['user_id'];
                $attendance = isset($attendance_map[$user_id]) ? $attendance_map[$user_id] : null;
                
                // Determine the display name - use emp_fullname, fallback to username
                $display_name = !empty($enrolled['emp_fullname']) ? $enrolled['emp_fullname'] : ($enrolled['username'] ?? 'User ' . $user_id);
                
                $attendee_data = [
                    'user_id' => $user_id,
                    'userid' => $enrolled['userid'],
                    'username' => $enrolled['username'],
                    'employee_idno' => $enrolled['employee_idno'],
                    'emp_fullname' => $enrolled['emp_fullname'],
                    'full_name' => $display_name,
                    'has_attendance' => !empty($attendance),
                    'attendance_time' => $attendance ? $attendance['attendance_time'] : null,
                    'attendance_date' => $attendance ? $attendance['attendance_date'] : null,
                    'is_verified' => $attendance ? $attendance['is_verified'] : null,
                    'attendance_id' => $attendance ? $attendance['id'] : null,
                    'attendance_file' => $attendance ? $attendance['attendance_file'] : null
                ];
                
                $data['attendees'][] = $attendee_data;
            }
            
            // Statistics
            $data['verified_count'] = count(array_filter($data['attendees'], function($a) {
                return $a['is_verified'] == 1;
            }));
            
            $data['pending_count'] = count(array_filter($data['attendees'], function($a) {
                return $a['has_attendance'] && $a['is_verified'] != 1;
            }));
            
            $data['not_attended_count'] = count(array_filter($data['attendees'], function($a) {
                return !$a['has_attendance'];
            }));
            
            $data['total_count'] = count($data['attendees']);
            $data['user_type'] = $user_type;
            $data['title'] = 'Session Attendees - ' . ($data['training']['training_name'] ?? 'Training');
            
            // Hide dashboard stats for this page
            $data['hide_dashboard_stats'] = true;
            $data['hide_quick_actions'] = true;
            
            return view('trainings/session_attendees', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading session attendees: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to load session attendees: ' . $e->getMessage());
            return redirect()->to('trainings/sessions/' . $training_id);
        }
    }
    
    /**
     * Get sessions for employee/guest view
     */
    public function get_employee_sessions()
    {
        try {
            $training_id = $this->request->getPost('training_id');
            $userid = session()->get('userid');
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Get all sessions for this training
            $sessions = $db->table('training_sessions')
                ->where('training_id', $training_id)
                ->orderBy('session_date', 'ASC')
                ->orderBy('session_start_time', 'ASC')
                ->get()
                ->getResultArray();
            
            // Get user's attendance for each session
            foreach ($sessions as &$session) {
                $attendance = $db->table('training_attendance')
                    ->where('session_id', $session['id'])
                    ->where('user_id', $userid)
                    ->get()
                    ->getRowArray();
                
                $session['user_attendance'] = $attendance;
            }
            
            return $this->response->setJSON([
                'success' => true,
                'sessions' => $sessions
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting sessions: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to load sessions: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Submit employee attendance with code and photo
     */
    public function submit_employee_attendance()
    {
        try {
            $session_id = $this->request->getPost('session_id');
            $training_id = $this->request->getPost('training_id');
            $session_code = strtoupper(trim($this->request->getPost('session_code')));
            $userid = session()->get('userid');
            
            if (empty($session_id) || empty($training_id) || empty($session_code)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Missing required information.'
                ]);
            }
            
            if (empty($userid)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'User not logged in.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Verify session exists and is active (has start time but no end time)
            $session = $db->table('training_sessions')
                ->where('id', $session_id)
                ->where('training_id', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$session) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Session not found.'
                ]);
            }
            
            // Check if session is active (started but not ended)
            if (empty($session['started_at']) || !empty($session['ended_at'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'This session is not currently active.'
                ]);
            }
            
            // Verify session code
            if (empty($session['session_code']) || $session['session_code'] !== $session_code) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Invalid session code. Please check and try again.'
                ]);
            }
            
            // Check if already submitted attendance
            $existing = $db->table('training_attendance')
                ->where('session_id', $session_id)
                ->where('user_id', $userid)
                ->get()
                ->getRowArray();
            
            if ($existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You have already submitted attendance for this session.'
                ]);
            }
            
            // Handle file upload
            $photoFile = $_FILES['photo'] ?? null;
            $filename = '';
            
            if ($photoFile && $photoFile['error'] === UPLOAD_ERR_OK) {
                $uploadDir = FCPATH . 'uploads/trainings/attendance/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Generate unique filename
                $ext = pathinfo($photoFile['name'], PATHINFO_EXTENSION);
                $filename = 'attendance_' . $userid . '_' . $session_id . '_' . time() . '.' . $ext;
                $filepath = $uploadDir . $filename;
                
                if (!move_uploaded_file($photoFile['tmp_name'], $filepath)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to upload photo.'
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Photo is required as proof of attendance.'
                ]);
            }
            
            // Insert attendance record with photo
            $attendance_data = [
                'training_id' => $training_id,
                'session_id' => $session_id,
                'user_id' => $userid,
                'attendance_date' => date('Y-m-d'),
                'attendance_time' => date('H:i:s'),
                'attendance_file' => $filename,
                'is_verified' => 0 // Default to unverified, admin will verify
            ];
            
            $db->table('training_attendance')->insert($attendance_data);
            
            log_message('info', 'Attendance submitted by user ' . $userid . ' for session ' . $session_id . ' with photo: ' . $filename);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Attendance submitted successfully! Waiting for admin verification.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error submitting attendance: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to submit attendance: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Verify or unverify attendance
     */
    public function verify_attendance()
    {
        try {
            $attendance_id = $this->request->getPost('attendance_id');
            $is_verified = $this->request->getPost('is_verified');
            
            if (empty($attendance_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Attendance ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Verify attendance record exists
            $attendance = $db->table('training_attendance')
                ->where('id', $attendance_id)
                ->get()
                ->getRowArray();
            
            if (!$attendance) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Attendance record not found.'
                ]);
            }
            
            // Update verification status
            $update_data = [
                'is_verified' => $is_verified ? 1 : 0
            ];
            
            $db->table('training_attendance')
                ->where('id', $attendance_id)
                ->update($update_data);
            
            log_message('info', 'Attendance ' . ($is_verified ? 'verified' : 'unverified') . ': ' . $attendance_id);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Attendance ' . ($is_verified ? 'verified' : 'unverified') . ' successfully.'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error verifying attendance: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to update attendance: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Complete a training (mark as completed)
     */
    public function complete_training()
    {
        // Check if user is logged in and is admin
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Please login to complete trainings.'
            ]);
        }
        
        $user_type = session()->get('user_type_name');
        if (stripos($user_type, 'admin') === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Access denied. Admin privileges required.'
            ]);
        }
        
        try {
            $training_id = $this->request->getPost('training_id');
            
            if (empty($training_id)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training ID is required.'
                ]);
            }
            
            $db = \Config\Database::connect();
            
            // Get training details
            $training = $db->table('lib_trainings')
                ->where('id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Training not found.'
                ]);
            }
            
            // Check if training is ongoing
            $status_info = $db->table('other_training_info oti')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('oti.training_id', $training_id)
                ->select('ts.status_name, ts.id as status_id')
                ->get()
                ->getRowArray();
            
            if (!$status_info || strtolower($status_info['status_name']) !== 'ongoing') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Only ongoing trainings can be marked as completed.'
                ]);
            }
            
            // Find the "Completed" status
            $completed_status = $db->table('training_status')
                ->where('LOWER(status_name)', 'completed')
                ->orWhere('LOWER(status_name)', 'finished')
                ->get()
                ->getRowArray();
            
            if (!$completed_status) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Completed status not found in database. Please contact system administrator.'
                ]);
            }
            
            // Hardcoded certificate template with image background support
            $bg_image_url = base_url('uploads/trainings/certificates/certificate_of_completion.jpg');
            
            log_message('debug', 'Certificate background URL: ' . $bg_image_url);
            
            $cert_content = '<div style="position: relative; width: 100%; height: 100%;">' . "\n" .
                '  <img src="' . $bg_image_url . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0;" alt="Certificate Background">' . "\n" .
                '  <!-- Name - Libre Franklin 40px -->' . "\n" .
                '  <div style="position: absolute; left: 550px; top: 400px; font-family: Libre Franklin, Arial, sans-serif; font-size: 40px; color: #000000; font-weight: bold; z-index: 1;">' . "\n" .
                '    [NAME]' . "\n" .
                '  </div>' . "\n" .
                '  ' . "\n" .
                '  <!-- Content - Libre Franklin 14px, Center Aligned -->' . "\n" .
                '  <div style="position: absolute; left: 62%; transform: translateX(-50%); top: 480px; font-family: Libre Franklin, Arial, sans-serif; font-size: 14px; color: #000000; line-height: 1.6; text-align: center; max-width: 700px; z-index: 1;">' . "\n" .
                '    has successfully completed the training entitled \'[TRAINING_TITLE]\' conducted on [DATE] at [VENUE] with [HOURS] training hours under the facilitation of [FACILITATOR].' . "\n" .
                '<br><br>Given this [DATE] at [VENUE].' . "\n" .
                '  </div>' . "\n" .
                '</div>';
            
            // Update the training with certificate content and status
            $update_data = [
                'training_cert_content' => $cert_content
            ];
            
            $db->table('lib_trainings')
                ->where('id_training', $training_id)
                ->update($update_data);
            
            $db->table('other_training_info')
                ->where('training_id', $training_id)
                ->update(['status_id' => $completed_status['id']]);
            
            log_message('info', 'Training completed by admin: ' . $training_id . ' - ' . $training['training_name']);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Training has been marked as completed successfully!'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error completing training: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to complete training: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Manage sessions for a training
     */
    public function manage_sessions($training_id)
    {
        try {
            $db = \Config\Database::connect();
            
            // Get training details
            $data['training'] = $db->table('lib_trainings lt')
                ->select('lt.*, oti.status_id, ts.status_name')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$data['training']) {
                session()->setFlashdata('error', 'Training not found.');
                return redirect()->to('trainings');
            }
            
            // Get all sessions for this training
            $data['sessions'] = $db->table('training_sessions')
                ->where('training_id', $training_id)
                ->orderBy('session_date', 'DESC')
                ->orderBy('session_start_time', 'DESC')
                ->get()
                ->getResultArray();
            
            // Check if there's an active session (started but not ended)
            $active_session = $db->table('training_sessions')
                ->where('training_id', $training_id)
                ->where('started_at IS NOT NULL')
                ->where('ended_at IS NULL')
                ->get()
                ->getRowArray();
            
            $data['has_active_session'] = !empty($active_session);
            $data['active_session_id'] = $active_session['id'] ?? null;
            
            $data['page_title'] = 'Manage Sessions - ' . $data['training']['training_name'];
            
            // Set cache control headers to prevent browser caching
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            // Load the sessions management view
            return view('trainings/manage_sessions', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error managing sessions: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to load sessions: ' . $e->getMessage());
            return redirect()->to('trainings');
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
            $data['descriptions'] = $db->table('lib_training_des')
                ->where('training_id', $id)
                ->get()
                ->getResultArray();
            
            // Get learnings
            $data['learnings'] = $db->table('lib_trainings_learnings')
                ->where('training_id', $id)
                ->get()
                ->getResultArray();

            // Get other training info from other_training_info table
            $other_info = $db->table('other_training_info')
                ->where('training_id', $id)
                ->get()
                ->getRowArray();

            // Merge other_info fields into training array with form-expected keys
            if ($other_info) {
                $data['training']['training_deadline'] = $other_info['registration_deadline'] ?? null;
                $data['training']['training_hours'] = $other_info['training_hours'] ?? null;
                $data['training']['no_of_attendees'] = $other_info['max_no_of_attendees'] ?? null;
                $data['training']['status_id'] = $other_info['status_id'] ?? null;
            }
            
            // Get registered attendee count
            $data['registered_count'] = $db->table('training_attendees')
                ->where('training_id', $id)
                ->countAllResults();
            
            // Get existing training sessions
            $data['sessions'] = $db->table('training_sessions')
                ->where('training_id', $id)
                ->orderBy('session_date', 'ASC')
                ->orderBy('session_start_time', 'ASC')
                ->get()
                ->getResultArray();
            
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
                ],
                'training_with_cert' => [
                    'label' => 'With Certificate',
                    'rules' => 'permit_empty|in_list[0,1]'
                ]
            ];
            
            if ($this->validate($rules)) {
                try {
                    // Validate that training hours matches sum of session durations (if sessions are being updated)
                    $sessionDates = $this->request->getPost('session_date');
                    $sessionStartTimes = $this->request->getPost('session_start_time');
                    $sessionEndTimes = $this->request->getPost('session_end_time');
                    $trainingHours = $this->request->getPost('training_hours');
                    
                    // Only validate if sessions are provided in the form
                    if (is_array($sessionDates) && !empty($sessionDates)) {
                        $totalSessionMinutes = 0;
                        
                        for ($i = 0; $i < count($sessionDates); $i++) {
                            if (!empty($sessionStartTimes[$i]) && !empty($sessionEndTimes[$i])) {
                                // Parse start time
                                $startParts = explode(':', $sessionStartTimes[$i]);
                                $startMinutes = intval($startParts[0]) * 60 + intval($startParts[1]);
                                
                                // Parse end time
                                $endParts = explode(':', $sessionEndTimes[$i]);
                                $endMinutes = intval($endParts[0]) * 60 + intval($endParts[1]);
                                
                                // Calculate duration
                                $durationMinutes = $endMinutes - $startMinutes;
                                
                                // Handle overnight sessions
                                if ($durationMinutes < 0) {
                                    $durationMinutes += 24 * 60;
                                }
                                
                                $totalSessionMinutes += $durationMinutes;
                            }
                        }
                        
                        // Convert to hours
                        $totalSessionHours = $totalSessionMinutes / 60;
                        
                        // Check if training hours matches session hours (allow 0.1 hour tolerance)
                        if (abs($totalSessionHours - floatval($trainingHours)) > 0.1) {
                            session()->setFlashdata('error', 
                                sprintf('Training hours (%.2f hrs) does not match the sum of session durations (%.2f hrs). Please adjust either the training hours or session times.', 
                                    floatval($trainingHours), $totalSessionHours));
                            return redirect()->back()->withInput();
                        }
                    }
                    
                    $db = \Config\Database::connect();
                    $db->transStart();
                    
                    // Update lib_trainings
                    $training_data = [
                        'training_name' => $this->request->getPost('training_name'),
                        'training_category_id' => $this->request->getPost('training_category_id'),
                        'training_datefrom' => $this->request->getPost('training_datefrom'),
                        'training_dateto' => $this->request->getPost('training_dateto'),
                        'training_hours' => $this->request->getPost('training_hours'),
                        'training_facilitator' => $this->request->getPost('training_facilitator'),
                        'training_venue' => $this->request->getPost('training_venue'),
                        'training_is_local' => $this->request->getPost('training_is_local') ?? 0,
                        'training_require_upload' => $this->request->getPost('training_require_upload') ?? 0,
                        'training_require_feedback' => $this->request->getPost('training_require_feedback') ?? 0,
                        'training_with_cert' => $this->request->getPost('training_with_cert') ?? 0,
                        'training_added_by' => session()->get('empid')
                    ];
                    
                    $db->table('lib_trainings')->update($training_data, ['id_training' => $id]);
                    
                    // Delete existing descriptions and learnings
                    $db->table('lib_training_des')->delete(['training_id' => $id]);
                    $db->table('lib_trainings_learnings')->delete(['training_id' => $id]);
                    
                    // Insert new descriptions
                    $descriptions = $this->request->getPost('training_description');
                    if (is_array($descriptions) && !empty($descriptions)) {
                        foreach ($descriptions as $desc) {
                            if (!empty(trim($desc))) {
                                $db->table('lib_training_des')->insert([
                                    'training_id' => $id,
                                    'training_description' => trim($desc)
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
                    
                    // Update other_training_info
                    $other_info_data = [
                        'max_no_of_attendees' => $this->request->getPost('training_attendees') ?: null
                    ];
                    $db->table('other_training_info')->update($other_info_data, ['training_id' => $id]);
                    
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
                    
                $data['descriptions'] = $db->table('lib_training_des')
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
    
    /**
     * Generate certificate for a completed training
     */
    public function generate_certificate($training_id)
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            session()->setFlashdata('error', 'Please login to view certificates.');
            return redirect()->to('/login');
        }
        
        $user_id = session()->get('userid');
        
        try {
            $db = \Config\Database::connect();
            
            // Get training details
            $training = $db->table('lib_trainings lt')
                ->select('lt.*, oti.status_id, ts.status_name, lt.training_venue, lt.training_facilitator')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->where('lt.id_training', $training_id)
                ->get()
                ->getRowArray();
            
            if (!$training) {
                session()->setFlashdata('error', 'Training not found.');
                return redirect()->to('mytrainings');
            }
            
            // Check if training is completed
            if (strtolower($training['status_name']) !== 'completed' && strtolower($training['status_name']) !== 'finished') {
                session()->setFlashdata('error', 'Certificate is not available. Training is not yet completed.');
                return redirect()->to('mytrainings');
            }
            
            // Check if user is enrolled in this training
            $is_enrolled = $db->table('training_attendees')
                ->where('training_id', $training_id)
                ->where('user_id', $user_id)
                ->countAllResults() > 0;
            
            if (!$is_enrolled) {
                session()->setFlashdata('error', 'You are not enrolled in this training.');
                return redirect()->to('mytrainings');
            }
            
            // Check if user has submitted feedback
            $has_feedback = $db->table('training_feedback')
                ->where('training_id', $training_id)
                ->where('user_id', $user_id)
                ->countAllResults() > 0;
            
            if (!$has_feedback) {
                session()->setFlashdata('error', 'Please submit your feedback before generating your certificate.');
                return redirect()->to('feedback/submit/' . $training_id);
            }
            
            // Get user details
            $user = $db->table('users')
                ->where('userid', $user_id)
                ->get()
                ->getRowArray();
            
            log_message('debug', 'User data for certificate: ' . json_encode([
                'userid' => $user['userid'] ?? 'N/A',
                'username' => $user['username'] ?? 'N/A',
                'emp_fullname' => $user['emp_fullname'] ?? 'N/A'
            ]));
            
            // Get attendance records to calculate total hours attended
            $attendance_records = $db->table('training_attendance ta')
                ->join('training_sessions ts', 'ts.id = ta.session_id', 'left')
                ->where('ta.training_id', $training_id)
                ->where('ta.user_id', $user_id)
                ->select('ts.session_date, ta.attendance_time')
                ->get()
                ->getResultArray();
            
            // Replace placeholders in certificate content
            $cert_content = $training['training_cert_content'] ?? '';
            
            log_message('debug', 'Raw cert_content from DB length: ' . strlen($cert_content));
            log_message('debug', 'Raw cert_content preview: ' . substr($cert_content, 0, 200));
            
            // ALWAYS regenerate to ensure proper z-index and complete template
            $needs_regeneration = true;
            
            // Only use DB content if it has proper z-index: 100 (new format)
            if (!empty($cert_content) && strpos($cert_content, 'z-index: 100') !== false) {
                $needs_regeneration = false;
                log_message('info', 'Using existing certificate template from database (has z-index: 100)');
            } else {
                log_message('info', 'Regenerating certificate template - old format detected or content too short');
            }
            
            if ($needs_regeneration) {
                $bg_image_url = base_url('uploads/trainings/certificates/certificate_of_completion.jpg');
                
                $cert_content = '<div style="position: relative; width: 100%; height: 100%;">' . "\n" .
                    '  <img src="' . $bg_image_url . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1;" alt="Certificate Background">' . "\n" .
                    '  <!-- Name - Libre Franklin 48px, All Caps -->' . "\n" .
                    '  <div style="position: absolute; left: 400px; top: 420px; font-family: Libre Franklin, Arial, sans-serif; font-size: 48px; color: #000000; font-weight: bold; text-transform: uppercase; z-index: 100; text-align: center; width: 600px;">' . "\n" .
                    '    [NAME]' . "\n" .
                    '  </div>' . "\n" .
                    '  ' . "\n" .
                    '  <!-- Content - Libre Franklin 14px, Center Aligned -->' . "\n" .
                    '  <div style="position: absolute; left: 60%; transform: translateX(-50%); top: 500px; font-family: Libre Franklin, Arial, sans-serif; font-size: 14px; color: #000000; line-height: 1.6; text-align: center; max-width: 700px; z-index: 100;">' . "\n" .
                    '    has successfully completed the training entitled \'[TRAINING_TITLE]\' conducted on [DATE] at [VENUE] with [HOURS] training hours under the facilitation of [FACILITATOR].' . "\n" .
                    '<br><br>Given this [DATE] at [VENUE].' . "\n" .
                    '  </div>' . "\n" .
                    '</div>';
                
                // Update the database with the new template
                $db->table('lib_trainings')
                    ->where('id_training', $training_id)
                    ->update(['training_cert_content' => $cert_content]);
                
                log_message('info', 'Database updated with new certificate template');
            }
            
            $cert_content = str_replace('[NAME]', strtoupper($user['emp_fullname'] ?? $user['username'] ?? 'Participant'), $cert_content);
            $cert_content = str_replace('[TRAINING_TITLE]', $training['training_name'], $cert_content);
            $cert_content = str_replace('[DATE]', date('F d, Y', strtotime($training['training_datefrom'])), $cert_content);
            $cert_content = str_replace('[HOURS]', $training['training_hours'] ?? 'N/A', $cert_content);
            $cert_content = str_replace('[FACILITATOR]', $training['training_facilitator'] ?? 'N/A', $cert_content);
            $cert_content = str_replace('[VENUE]', $training['training_venue'] ?? 'N/A', $cert_content);
            
            log_message('debug', 'Certificate name replaced with: ' . strtoupper($user['emp_fullname'] ?? $user['username'] ?? 'Participant'));
            log_message('debug', 'Final cert_content length: ' . strlen($cert_content));
            log_message('debug', 'Cert content preview: ' . substr($cert_content, 0, 500));
            
            // Prepare data for certificate view
            $data = [
                'training' => $training,
                'user' => $user,
                'cert_content' => $cert_content,
                'attendance_count' => count($attendance_records),
                'title' => 'Certificate - ' . $training['training_name']
            ];
            
            return view('trainings/certificate_view', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error generating certificate: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to generate certificate: ' . $e->getMessage());
            return redirect()->to('mytrainings');
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


