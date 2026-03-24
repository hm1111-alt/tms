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

    public function index()
    {
        $employee_id = session()->get('empid');
        
        try {
            $db = \Config\Database::connect('training');
            $table_exists = $db->query("SHOW TABLES LIKE 'employees_trainings'");
            
            if ($table_exists && $table_exists->getNumRows() > 0) {
                $data['trainings'] = $this->mdl_employees_trainings->getApprovedTrainingsByEmployee($employee_id);
            } else {
                $data['trainings'] = [];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error loading trainings: ' . $e->getMessage());
            $data['trainings'] = [];
        }
        
        return view('trainings/training_tabs', $data);
    }

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

            $db = \Config\Database::connect('training');
            
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
            
            $db = \Config\Database::connect('training');
            
            if (!$db) {
                log_message('error', 'Unable to connect to training database');
                return '<div class="alert alert-danger">Unable to connect to training database</div>';
            }
            
            log_message('debug', 'Connected to training database, querying pending_trainings for approved records');
            
            $training = $db->table('pending_trainings pt')
                          ->select('pt.*, ltc.training_category_name')
                          ->join('lib_training_category ltc', 'ltc.id_training_category = pt.training_category_id', 'left')
                          ->where('pt.id_pending_training', $id)
                          ->get()
                          ->getRowArray();
            
            log_message('debug', 'Pending/approved training query result: ' . ($training ? 'FOUND' : 'NOT FOUND'));
            
            if (!$training) {
                log_message('debug', 'Training not found in pending_trainings');
                return '<div class="alert alert-warning">Training record not found</div>';
            } else {
                $training['is_pending'] = empty($training['is_approved']) || $training['is_approved'] == 0;
                log_message('debug', 'Training status - is_pending: ' . ($training['is_pending'] ? 'true' : 'false') . ', is_approved: ' . ($training['is_approved'] ?? 'null'));
            }
            
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
            
            $data = [
                'training' => $training,
                'training_type' => $training_type
            ];
            
            log_message('debug', 'Loading training_details view');
            return view('trainings/training_details', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading training details: ' . $e->getMessage());
            log_message('error', 'Exception trace: ' . $e->getTraceAsString());
            return '<div class="alert alert-danger">Error loading training details: ' . $e->getMessage() . '</div>';
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

                $db = \Config\Database::connect('training');
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
            
            $db = \Config\Database::connect('training');
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
            
            $training_db = \Config\Database::connect('training');
            
            if (!$training_db) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database connection failed']);
            }
            
            $category_check = $training_db->table('lib_training_category')
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
            
            $result = $training_db->table('pending_trainings')->insert($data);
            
            if ($result) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Training submitted successfully']);
            } else {
                $error = $training_db->error();
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
            
            $db = \Config\Database::connect('training');
            
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
            
            $training_db = \Config\Database::connect('training');
            
            if (!$training_db) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Database connection failed']);
            }
            
            $existing_training = $training_db->table('pending_trainings')
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
            
            $result = $training_db->table('pending_trainings')
                        ->where('id_pending_training', $training_id)
                        ->update($data);
            
            if ($result) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Training updated successfully']);
            } else {
                $error = $training_db->error();
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
            
            $db = \Config\Database::connect('training');
            
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
            
            $db = \Config\Database::connect('training');
            
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