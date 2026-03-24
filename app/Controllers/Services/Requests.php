<?php

namespace App\Controllers\Services;

use App\Controllers\BaseController;
use App\Models\Service\Mdl_requests;
use App\Models\Service\Mdl_request_documents;
use App\Models\Service\Mdl_request_purposes;
use App\Models\Service\Mdl_documents;
use App\Models\Service\Mdl_purposes;
use App\Models\Service\Mdl_purpose_groups;
use App\Models\Service\Mdl_feedback_validation;

class Requests extends BaseController
{
    protected $mdl_requests;
    protected $mdl_request_docs;
    protected $mdl_request_purposes;
    protected $mdl_documents;
    protected $mdl_purposes;
    protected $mdl_purpose_groups;
    protected $mdl_setting;
    protected $mdl_feedback_validation;
    protected $class_name;

    public function __construct()
    {
        $this->mdl_requests = new Mdl_requests();
        $this->mdl_request_docs = new Mdl_request_documents();
        $this->mdl_request_purposes = new Mdl_request_purposes();
        $this->mdl_documents = new Mdl_documents();
        $this->mdl_purposes = new Mdl_purposes();
        $this->mdl_purpose_groups = new Mdl_purpose_groups();
        $this->mdl_setting = new \App\Models\preferences\mdl_setting();
        $this->mdl_feedback_validation = new Mdl_feedback_validation();
        $this->class_name = basename(str_replace('\\', '/', get_class($this)));
    }

    public function index()
    {
        // Check if we should show feedback error message
        if ($this->request->getGet('show_feedback_error')) {
            $session = session();
            $user_id = $session->get('userid');
            
            if ($user_id) {
                $db = \Config\Database::connect();
                $user = $db->table('users')->select('employee_idno')->where('userid', $user_id)->get()->getRowArray();
                
                if ($user) {
                    $employee = $db->table('employees')->where('emp_idno', $user['employee_idno'])->get()->getRowArray();
                    
                    if ($employee) {
                        $pending_count = $this->mdl_feedback_validation->checkPendingFeedback($employee['id_employee']);
                        
                        if ($pending_count > 0) {
                            session()->setFlashdata('error', 'You have ' . $pending_count . ' completed document(s) without feedback. Please provide feedback before adding a new request.');
                        }
                    }
                }
            }
        }
        
        $page = $this->mdl_setting->get_page_details($this->class_name);
        return view('services/requests/requests', ['page' => $page]);
    }

    public function load_table_requests()
    {
        $session = session();
        $user_id = $session->get('userid');
        
        log_message('debug', 'User ID: ' . ($user_id ? $user_id : 'Not logged in'));
        
        if (!$user_id) {
            $empty_details = [
                'event_count' => 0,
                'aa' => 0,
                'num_list' => 10,
                'num' => 0,
                'page' => 1,
                'max_page' => 1,
                'search' => '',
                'order_by' => 'date_requested',
                'sort_by' => 'DESC'
            ];
            return view('services/requests/requests_table', [
                'requests' => [],
                'details' => $empty_details
            ]);
        }

        $db = \Config\Database::connect();
        $dbService = \Config\Database::connect('service');

        $user = $db->table('users')->select('employee_idno')->where('userid', $user_id)->get()->getRowArray();
        if (!$user) {
            $empty_details = [
                'event_count' => 0,
                'aa' => 0,
                'num_list' => 10,
                'num' => 0,
                'page' => 1,
                'max_page' => 1,
                'search' => '',
                'order_by' => 'date_requested',
                'sort_by' => 'DESC'
            ];
            return view('services/requests/requests_table', [
                'requests' => [],
                'details' => $empty_details
            ]);
        }

        $employee = $db->table('employees')->where('emp_idno', $user['employee_idno'])->get()->getRowArray();
        if (!$employee) {
            $empty_details = [
                'event_count' => 0,
                'aa' => 0,
                'num_list' => 10,
                'num' => 0,
                'page' => 1,
                'max_page' => 1,
                'search' => '',
                'order_by' => 'date_requested',
                'sort_by' => 'DESC'
            ];
            return view('services/requests/requests_table', [
                'requests' => [],
                'details' => $empty_details
            ]);
        }

        $page = (int) ($this->request->getPost('page') ?? 1);
        $limit_post = $this->request->getPost('limit') ?? 10;
        $limit = $limit_post === 'all' ? 'all' : (int)$limit_post;
        $search = trim($this->request->getPost('search') ?? '');
        $status = trim($this->request->getPost('record_status') ?? '');
        $offset = ($page - 1) * ($limit === 'all' ? 0 : $limit);

        $request_columns = $dbService->getFieldNames('request');
        $emp_id_field = 'emp_id';
        if (!in_array('emp_id', $request_columns)) {
            if (in_array('employee_id', $request_columns)) {
                $emp_id_field = 'employee_id';
            } elseif (in_array('emp_no', $request_columns)) {
                $emp_id_field = 'emp_no';
            } elseif (in_array('employee_no', $request_columns)) {
                $emp_id_field = 'employee_no';
            } elseif (in_array('id_employee', $request_columns)) {
                $emp_id_field = 'id_employee';
            }
        }

        $has_request_docs = in_array('request_documents', $dbService->listTables());
        $has_lib_docs = in_array('lib_documents', $dbService->listTables());
        $has_status_table = in_array('status', $dbService->listTables());
        $has_status_field = in_array('status', $request_columns);
        
        $date_field = in_array('date_time_requested', $request_columns) ? 'date_time_requested' : 'date_requested';
        
        if ($has_request_docs && $has_lib_docs) {
            $request_query = $dbService->table('request r')
                ->select("r.*, r.$date_field AS date_requested") 
                ->join('request_documents rd', 'r.id_request = rd.request_id', 'left');
                
            if ($has_status_table) {
                $request_query->join('status s', 'rd.status_id = s.id', 'left');
            }
            
            $request_query->where("r.{$emp_id_field}", $employee['id_employee']);
        } else {
            $request_query = $dbService->table('request r')
                ->select("r.*, r.$date_field AS date_requested")
                ->where("r.{$emp_id_field}", $employee['id_employee']);
        }

        if ($search != '') {
            $request_query->groupStart()
                ->like('r.emp_name', $search)
                ->orLike('r.unit_college', $search)
                ->orLike('r.req_num', $search)
                ->groupEnd();
        }

        if ($status !== '') {
            $all_records_query = clone $request_query;
            $all_records = $all_records_query->get()->getResultArray();
            
            $filtered_count = 0;
            foreach ($all_records as $request) {
                if ($has_request_docs) {
                    $doc_query = $dbService->table('request_documents rd')
                        ->where('rd.request_id', $request['id_request']);

                    if ($has_lib_docs) {
                        $doc_query->join('lib_documents ld', 'rd.document_id = ld.id', 'left')
                            ->select('rd.*');
                    }

                    if ($has_status_table) {
                        $doc_query->join('status s', 'rd.status_id = s.id', 'left')
                            ->select('rd.*, s.status_name');
                    }

                    $request_docs = $doc_query->get()->getResultArray();

                    if (!empty($request_docs)) {
                        $first_doc = $request_docs[0];
                        $actual_status = $first_doc['status_name'] ?? $first_doc['status'] ?? $request['status'] ?? 'Pending';
                        
                        if (strtolower($actual_status) === strtolower($status)) {
                            $filtered_count++;
                        }
                    } else {
                        $actual_status = $request['status'] ?? 'Pending';
                        $actual_lower = strtolower(trim($actual_status));
                        $filter_lower = strtolower(trim($status));
                        
                        $matches = false;
                        if ($filter_lower === 'pending') {
                            $matches = ($actual_lower === 'pending');
                        } else if ($filter_lower === 'on process') {
                            $matches = ($actual_lower === 'on process' || $actual_lower === 'on-process' || $actual_lower === 'onprocess');
                        } else if ($filter_lower === 'completed') {
                            $matches = ($actual_lower === 'completed' || $actual_lower === 'complete');
                        }
                        
                        if ($matches) {
                            $filtered_count++;
                        }
                    }
                } else {
                    $actual_status = 'Pending';
                    $actual_lower = strtolower(trim($actual_status));
                    $filter_lower = strtolower(trim($status));
                    
                    $matches = false;
                    if ($filter_lower === 'pending') {
                        $matches = ($actual_lower === 'pending');
                    } else if ($filter_lower === 'on process') {
                        $matches = ($actual_lower === 'on process' || $actual_lower === 'on-process' || $actual_lower === 'onprocess');
                    } else if ($filter_lower === 'completed') {
                        $matches = ($actual_lower === 'completed' || $actual_lower === 'complete');
                    }
                    
                    if ($matches) {
                        $filtered_count++;
                    }
                }
            }
            $event_count = $filtered_count;
        } else {
            $count_query = clone $request_query;
            $event_count = $count_query->countAllResults(false);
        }

        $data_query = clone $request_query;

        $request_columns = $dbService->getFieldNames('request');
        if (in_array('date_time_requested', $request_columns)) {
            $data_query->orderBy('r.date_time_requested', 'DESC');
        } else {
            $data_query->orderBy('r.date_requested', 'DESC');
        }
        if ($limit !== 'all' && $limit > 0) {
            $data_query->limit($limit, $offset);
        }

        if ($has_request_docs && $has_lib_docs) {
            $data_query->groupBy('r.id_request');
        }

        $requests = $data_query->get()->getResultArray();

        log_message('debug', 'Requests data: ' . json_encode($requests));

        if ($status !== '') {
            $filtered_requests = [];
            foreach ($requests as $request) {
                if ($has_request_docs) {
                    $doc_query = $dbService->table('request_documents rd')
                        ->where('rd.request_id', $request['id_request']);
        
                    if ($has_lib_docs) {
                        $doc_query->join('lib_documents ld', 'rd.document_id = ld.id', 'left')
                            ->select('rd.*, ld.document_name');
                    } else {
                        $doc_query->select('rd.*, rd.document_id as document_name');
                    }
        
                    if ($has_status_table) {
                        $doc_query->join('status s', 'rd.status_id = s.id', 'left')
                            ->select('rd.*, s.status_name');
                    }
        
                    $request_docs = $doc_query->get()->getResultArray();
        
                    if (!empty($request_docs)) {
                        $first_doc = $request_docs[0];
                        $actual_status = $first_doc['status_name'] ?? $first_doc['status'] ?? $request['status'] ?? 'Pending';
  
                        $actual_lower = strtolower(trim($actual_status));
                        $filter_lower = strtolower(trim($status));
                                        
                        $matches = false;
                        if ($filter_lower === 'pending') {
                            $matches = ($actual_lower === 'pending');
                        } else if ($filter_lower === 'on process') {
                            $matches = ($actual_lower === 'on process' || $actual_lower === 'on-process' || $actual_lower === 'onprocess');
                        } else if ($filter_lower === 'completed') {
                            $matches = ($actual_lower === 'completed' || $actual_lower === 'complete');
                        }
                                        
                        if ($matches) {
                            $request['status'] = $actual_status;
                            $filtered_requests[] = $request;
                        }
                    } else {
                        $actual_status = 'Pending';
                        if (strtolower($actual_status) === strtolower($status)) {
                            $filtered_requests[] = $request;
                        }
                    }
                } else {
                    $actual_status = 'Pending';
                    if (strtolower($actual_status) === strtolower($status)) {
                        $filtered_requests[] = $request;
                    }
                }
            }
            $requests = $filtered_requests;
        }
        
        foreach ($requests as &$request) {
            $request['document_name'] = 'General Request';
            $request['other_documents'] = '';
            $request['document_id'] = $request['id_request'];
            $request['rd_id'] = $request['id_request'];
            $request['status'] = 'Pending';
            $request['purposes'] = 'No purpose specified';

            if ($has_request_docs) {
                $doc_query = $dbService->table('request_documents rd')
                    ->where('rd.request_id', $request['id_request']);

                if ($has_lib_docs) {
                    $doc_query->join('lib_documents ld', 'rd.document_id = ld.id', 'left')
                        ->select('rd.*, ld.document_name');
                } else {
                    $doc_query->select('rd.*, rd.document_id as document_name');
                }

                $request_docs = $doc_query->get()->getResultArray();

                if (!empty($request_docs)) {
                    $first_doc = $request_docs[0];
                    $request['document_name'] = $first_doc['document_name'] ?? 'General Document';
                    $request['other_documents'] = $first_doc['other_documents'] ?? '';
                    $request['document_id'] = $first_doc['id'];
                    $request['rd_id'] = $first_doc['id'];

                    if ($has_status_table && !empty($first_doc['status_id'])) {
                        $status_row = $dbService->table('status')->where('id', $first_doc['status_id'])->get()->getRowArray();
                        $request['status'] = $status_row['status_name'] ?? $first_doc['status'] ?? 'Pending';
                    } else {
                        $request['status'] = $first_doc['status'] ?? $request['status'];
                    }
                }
            }
        }

        $has_purposes = in_array('request_purposes', $dbService->listTables()) && 
                       in_array('lib_purposes', $dbService->listTables());

        if ($has_purposes && !empty($requests)) {
            $request_ids = array_column($requests, 'id_request');
            
            $purpose_data = $dbService->table('request_purposes rp')
                ->select('rp.request_id, p.purpose_name, rp.purpose_details')
                ->join('lib_purposes p', 'rp.purpose_id = p.id', 'left')
                ->whereIn('rp.request_id', $request_ids)
                ->get()->getResultArray();

            log_message('debug', 'Found ' . count($purpose_data) . ' purposes for requests: ' . json_encode($request_ids));
            foreach ($purpose_data as $pd) {
                log_message('debug', 'Purpose data: ' . json_encode($pd));
            }

            $purposes = [];
            foreach ($purpose_data as $purpose_row) {
                $request_id = $purpose_row['request_id'];
                if (!isset($purposes[$request_id])) {
                    $purposes[$request_id] = [];
                }

                $purpose_str = $purpose_row['purpose_name'] ?? '';
                if (!empty($purpose_row['purpose_details'])) {
                    $purpose_str .= ': ' . $purpose_row['purpose_details'];
                }
                $purposes[$request_id][] = $purpose_str;
            }

            foreach ($requests as &$request) {
                $request_id = $request['id_request'];
                if (isset($purposes[$request_id]) && !empty($purposes[$request_id])) {
                    $request['purposes'] = implode('; ', array_unique($purposes[$request_id])); // Remove duplicates
                } else {
                    $request['purposes'] = 'No purpose specified';
                }
            }
        }

        $max_page = ($limit === 'all' || $limit == 0) ? 1 : (int) ceil($event_count / $limit);

        $details = [
            'event_count' => $event_count,
            'aa' => $offset,
            'num_list' => $limit,
            'num' => count($requests),
            'page' => $page,
            'max_page' => $max_page,
            'search' => $search,
            'order_by' => in_array('date_time_requested', $request_columns) ? 'date_time_requested' : 'date_requested',
            'sort_by' => 'DESC'
        ];

        return view('services/requests/requests_table', [
            'requests' => $requests,
            'details' => $details
        ]);

    }

    public function view($request_id, $doc_id = null)
    {
        $page = $this->mdl_setting->get_page_details($this->class_name);
        $session = session();
        $user_id = $session->get('userid');

        $db = \Config\Database::connect();
        $dbService = \Config\Database::connect('service');

        $user = $db->table('users')->select('employee_idno')->where('userid', $user_id)->get()->getRowArray();
        $employee = $db->table('employees')->where('emp_idno', $user['employee_idno'])->get()->getRowArray();

        $request_columns = $dbService->getFieldNames('request');
        $date_field = in_array('date_time_requested', $request_columns) ? 'date_time_requested' : 'date_requested';
        
        $request = $dbService->table('request r')
            ->select("r.*, r.$date_field AS date_requested")
            ->where('r.id_request', $request_id)
            ->where('r.emp_id', $employee['id_employee'])
            ->get()
            ->getRowArray();

        if (!$request) throw new \CodeIgniter\Exceptions\PageNotFoundException('Request not found');

        try {
            $now = date('Y-m-d H:i:s');
            $logRow = $dbService->table('document_logs')->where('request_id', $request_id)->get()->getRowArray();
            if ($logRow) {
                $dbService->table('document_logs')->where('id', $logRow['id'])->update([
                    'opened_at' => $now,
                    'opened_by' => $user_id,
                ]);
            } else {
                $dbService->table('document_logs')->insert([
                    'request_id' => $request_id,
                    'opened_at'   => $now,
                    'opened_by'   => $user_id,
                ]);
            }
        } catch (\Exception $e) {
        }

        $request_documents = $dbService->table('request_documents rd')
            ->select('d.document_name, rd.other_documents, rd.id as rd_id, rd.file_path, rd.remarks, COALESCE(s.status_name, "Pending") as status, rd.hr_staff, rd.hr_staff_name')
            ->join('lib_documents d', 'rd.document_id = d.id', 'left')
            ->join('status s', 'rd.status_id = s.id', 'left')
            ->where('rd.request_id', $request_id)
            ->get()->getResultArray();

        $purpose_query = $dbService->table('request_purposes rp')
            ->select('p.purpose_name, g.purpose_group_name, rp.purpose_details, rp.document_id')
            ->join('lib_purposes p', 'rp.purpose_id = p.id', 'left')
            ->join('lib_purpose_groups g', 'p.purpose_group_id = g.id', 'left')
            ->where('rp.request_id', $request_id);
        
        if ($doc_id) {
            $specific_purposes = $dbService->table('request_purposes rp')
                ->select('p.purpose_name, g.purpose_group_name, rp.purpose_details')
                ->join('lib_purposes p', 'rp.purpose_id = p.id', 'left')
                ->join('lib_purpose_groups g', 'p.purpose_group_id = g.id', 'left')
                ->where('rp.request_id', $request_id)
                ->where('rp.document_id', $doc_id)
                ->get()->getResultArray();
            
            if (empty($specific_purposes)) {
                $general_purposes = $dbService->table('request_purposes rp')
                    ->select('p.purpose_name, g.purpose_group_name, rp.purpose_details')
                    ->join('lib_purposes p', 'rp.purpose_id = p.id', 'left')
                    ->join('lib_purpose_groups g', 'p.purpose_group_id = g.id', 'left')
                    ->where('rp.request_id', $request_id)
                    ->where('(rp.document_id IS NULL OR rp.document_id = 0)')
                    ->get()->getResultArray();
                $request_purposes = array_merge($specific_purposes, $general_purposes);
            } else {
                $request_purposes = $specific_purposes;
            }
        } else {
            $request_purposes = $purpose_query->get()->getResultArray();
        }

        $current_document = null;
        if ($doc_id) {
            foreach ($request_documents as $doc) {
                if (isset($doc['rd_id']) && $doc['rd_id'] == $doc_id) {
                    $current_document = $doc;
                    break;
                }
            }
        } else {
            if (!empty($request_documents)) {
                $current_document = $request_documents[0];
            }
        }

        if ($current_document) {
            $current_document['document_name'] = $current_document['document_name'] ?? 'Unknown Document';
            $current_document['status'] = $current_document['status'] ?? 'Pending';
            $current_document['remarks'] = $current_document['remarks'] ?? '';
            $current_document['file_path'] = $current_document['file_path'] ?? '';
        }

        $document_logs = [];
        try {
            $document_logs = $dbService->table('document_logs dl')
                ->select('dl.*')
                ->where('dl.request_id', $request_id)
                ->orderBy('dl.created_at', 'DESC')
                ->get()->getResultArray();
            
            foreach($document_logs as &$log) {
                if (!empty($log['opened_by'])) {
                    $user_info = $db->table('users')->where('userid', $log['opened_by'])->get()->getRowArray();
                    $log['performed_by'] = $user_info['username'] ?? 'System';
                } else {
                    $log['performed_by'] = 'System';
                }
            }
        } catch (\Exception $e) {
            $document_logs = [];
        }

        $feedback_submitted = false;
        try {
            $dbFeedback = \Config\Database::connect('feedback');
            $feedback = $dbFeedback->table('feedback')
                ->where('request_id', $request_id)
                ->where('document_id', $current_document['rd_id'] ?? 0)
                ->get()
                ->getRowArray();
            
            $feedback_submitted = !empty($feedback);
        } catch (\Exception $e) {
            log_message('error', 'Error checking feedback status: ' . $e->getMessage());
        }

        return view('services/requests/requests_view', [
            'request'           => $request,
            'document'          => $current_document,  
            'request_documents' => $request_documents,
            'purposes'          => $request_purposes,  
            'request_purposes'  => $request_purposes,
            'current_document'  => $current_document,
            'document_logs'     => $document_logs,
            'employee'          => $employee,
            'page'              => $page,
            'feedback_submitted' => $feedback_submitted
        ]);
    }

    public function form()
    {
        $page = $this->mdl_setting->get_page_details($this->class_name);
        $session = session();
        $user_id = $session->get('userid'); 
        if (!$user_id) return redirect()->back()->with('error', 'No user logged in!');

        $dbEmployee = \Config\Database::connect();
        $dbHrmis = \Config\Database::connect('hrmis');
        $dbService = \Config\Database::connect('service');

        $user = $dbEmployee->table('users')->where('userid', $user_id)->get()->getRowArray();
        $employee = $dbEmployee->table('employees')
            ->select('id_employee, emp_idno, emp_fname, emp_mi, emp_lname, emp_fullname, emp_unit')
            ->where('emp_idno', $user['employee_idno'])
            ->get()->getRowArray();

        $hrmisEmployee = $dbHrmis->table('employees')
            ->select('emp_cpno')
            ->where('emp_idno', $employee['emp_idno'])
            ->get()
            ->getRowArray();
        $employee['emp_cpno'] = $hrmisEmployee['emp_cpno'] ?? '';

        $unit = $dbEmployee->table('lib_units')->select('unit_name')->where('id_unit', $employee['emp_unit'])->get()->getRow('unit_name');

        $documents = $dbService->table('lib_documents')->get()->getResultArray();
        $purposes_all = $dbService->table('lib_purposes')->get()->getResultArray();
        $purpose_groups = $dbService->table('lib_purpose_groups')->get()->getResultArray();

        $grouped_purposes = [];
        $ungrouped_purposes = [];
        foreach($purposes_all as $p) {
            if (!empty($p['purpose_group_id'])) $grouped_purposes[$p['purpose_group_id']][] = $p;
            else $ungrouped_purposes[] = $p;
        }

        return view('services/requests/requests_form', [
            'employee' => $employee,
            'unit_name' => $unit,
            'documents' => $documents,
            'purposes' => $ungrouped_purposes,   
            'purpose_groups' => $purpose_groups, 
            'grouped_purposes' => $grouped_purposes,
            'page' => $page
        ]);
    }

    public function submit()
    {
        $session = session();
        $user_id = $session->get('userid');
        if (!$user_id) return redirect()->back()->with('error', 'No user logged in!');

        $dbEmployee = \Config\Database::connect();
        $dbHrmis = \Config\Database::connect('hrmis');
        $dbService = \Config\Database::connect('service');

        $user = $dbEmployee->table('users')->where('userid', $user_id)->get()->getRowArray();
        $employee = $dbEmployee->table('employees')
            ->select('id_employee, emp_idno, emp_fname, emp_mi, emp_lname, emp_fullname, emp_unit')
            ->where('emp_idno', $user['employee_idno'])
            ->get()
            ->getRowArray();

        $hrmisEmployee = $dbHrmis->table('employees')
            ->select('emp_cpno')
            ->where('emp_idno', $employee['emp_idno'])
            ->get()
            ->getRowArray();
        $employee['emp_cpno'] = $hrmisEmployee['emp_cpno'] ?? '';

        $emp_name = trim($employee['emp_fname'] . ' ' . ($employee['emp_mi'] ?? '') . ' ' . $employee['emp_lname']);
        $unit = $dbEmployee->table('lib_units')->select('unit_name')->where('id_unit', $employee['emp_unit'])->get()->getRow('unit_name');
        $contact_num = $employee['emp_cpno'] ?? ''; 

        $req_num = $this->mdl_requests->generate_request_number();
        
        $requestData = [
            'emp_id'                => $employee['id_employee'],
            'emp_name'              => $emp_name,
            'unit_college'          => $unit,
            'contact_num'           => $contact_num,
            'submitted_by'          => $emp_name,
            'date_time_requested'   => date('Y-m-d H:i:s'),
            'req_num'               => $req_num,
        ];
        $this->mdl_requests->insert($requestData);
        $request_id = $this->mdl_requests->getInsertID();

        $docs = $this->request->getPost('documents');
        if ($docs && is_array($docs)) {
            foreach ($docs as $doc) {

                $this->mdl_request_docs->insert([
                    'request_id'      => $request_id,
                    'document_id'     => $doc['id'],
                    'other_documents' => $doc['other'] ?? null
                ]);
                
                $document_id = $this->mdl_request_docs->getInsertID();

                try {
                    $created_at = date('Y-m-d H:i:s');
                    $log_data = [
                        'request_id'    => $request_id,
                        'document_id'   => $document_id,
                        'created_at'    => $created_at,
                        'created_by'    => $user_id,
                        'updated_at'    => $created_at,  
                        'updated_by'    => $user_id,
                    ];
                    
                    log_message('debug', 'Inserting document log: ' . json_encode($log_data));
                    
                    $result = $dbService->table('document_logs')->insert($log_data);
                    
                    if ($result) {
                        $insert_id = $dbService->insertID();
                        log_message('debug', 'Document log inserted successfully with ID: ' . $insert_id);
                        
                        $verify_log = $dbService->table('document_logs')
                            ->where('id', $insert_id)
                            ->get()
                            ->getRowArray();
                            
                        if ($verify_log && $verify_log['created_at'] === $created_at) {
                            log_message('debug', 'Document log verification successful');
                        } else {
                            log_message('error', 'Document log verification failed. Expected: ' . $created_at . ', Got: ' . ($verify_log['created_at'] ?? 'null'));
                        }
                    } else {
                        log_message('error', 'Failed to insert document log');
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Failed to log document creation: ' . $e->getMessage());
                }

                if (!empty($doc['purpose'])) {

                    if (strpos($doc['purpose'], 'group-') === 0) {
                        $groupId = str_replace('group-', '', $doc['purpose']);
                        $group = $this->mdl_purpose_groups->where('id', $groupId)->first();

                        if ($group && strtolower($group['purpose_group_name']) === 'travel abroad') {

                            $details = '';
                            if (!empty($doc['travel_place'])) $details .= 'Place of Travel: '.$doc['travel_place'];
                            if (!empty($doc['travel_date']))  $details .= ($details ? ', ' : '') . 'Date of Travel: '.$doc['travel_date'];

                            $childPurpose = $this->mdl_purposes->where('purpose_group_id', $groupId)->first();
                            if ($childPurpose) {
                                $this->mdl_request_purposes->insert([
                                    'request_id'      => $request_id,
                                    'document_id'     => $document_id,
                                    'purpose_id'      => $childPurpose['id'],
                                    'purpose_details' => $details ?: ''
                                ]);
                            }

                            continue; 
                        }
                    }

                    $purposeRow = $this->mdl_purposes->where('id', $doc['purpose'])->first();
                    if (!$purposeRow) continue;

                    $details = trim($doc['purpose_details'] ?? '');

                    $this->mdl_request_purposes->insert([
                        'request_id'      => $request_id,
                        'document_id'     => $document_id,
                        'purpose_id'      => $purposeRow['id'],
                        'purpose_details' => $details ?: ''
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Request submitted successfully!');
    }

    
    public function edit_document($request_id, $document_id)
    {
        $page = $this->mdl_setting->get_page_details($this->class_name);
        $session = session();
        $user_id = $session->get('userid');
        if (!$user_id) return redirect()->to('/login');

        $db = \Config\Database::connect();
        $dbHrmis = \Config\Database::connect('hrmis');
        $dbService = \Config\Database::connect('service');

        $user = $db->table('users')->select('employee_idno')->where('userid', $user_id)->get()->getRowArray();
        $employee = $db->table('employees')->where('emp_idno', $user['employee_idno'])->get()->getRowArray();
        $unit_name = $db->table('lib_units')->select('unit_name')->where('id_unit', $employee['emp_unit'])->get()->getRow('unit_name');

        $hrmisEmployee = $dbHrmis->table('employees')
            ->select('emp_cpno')
            ->where('emp_idno', $employee['emp_idno'])
            ->get()
            ->getRowArray();
        $employee['emp_cpno'] = $hrmisEmployee['emp_cpno'] ?? '';

        $request = $this->mdl_requests->where('id_request', $request_id)
                                      ->where('emp_id', $employee['id_employee'])
                                      ->first();
        if (!$request) throw new \CodeIgniter\Exceptions\PageNotFoundException('Request not found');

        $status = strtolower(trim($request['status'] ?? 'pending'));
        $is_locked = ($status === 'on process' || $status === 'on-process' || 
                     $status === 'processing' || $status === 'completed' ||
                     $status === 'complete');
        
        if ($is_locked) {
            return redirect()->to('services/requests')->with('error', 'Cannot edit document when request status is ' . ucfirst($status));
        }

        $document = $dbService->table('request_documents rd')
            ->select('rd.*, ld.document_name')
            ->join('lib_documents ld', 'rd.document_id = ld.id', 'left')
            ->where('rd.id', $document_id)
            ->where('rd.request_id', $request_id)
            ->get()
            ->getRowArray();
            
        if (!$document) throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');

        $document_purpose = $dbService->table('request_purposes rp')
            ->select('rp.*, p.purpose_name, p.purpose_group_id, pg.purpose_group_name')
            ->join('lib_purposes p', 'rp.purpose_id = p.id', 'left')
            ->join('lib_purpose_groups pg', 'p.purpose_group_id = pg.id', 'left')
            ->where('rp.request_id', $request_id)
            ->where('rp.document_id', $document_id)
            ->get()
            ->getRowArray();

        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            
            $this->mdl_request_docs->update($document_id, [
                'document_id' => $data['document_id'],
                'other_documents' => $data['other_documents'] ?? null
            ]);

            $this->mdl_request_purposes->where('request_id', $request_id)
                                      ->where('document_id', $document_id)
                                      ->delete();

            if (!empty($data['purpose_id'])) {
                $purpose_details = '';
                if (!empty($data['travel_place']) && !empty($data['travel_date'])) {
                    $purpose_details = 'Place: ' . $data['travel_place'] . ', Date: ' . $data['travel_date'];
                } else {
                    $purpose_details = $data['purpose_details'] ?? '';
                }
                
                log_message('debug', 'Inserting purpose - Request ID: ' . $request_id . ', Document ID: ' . $document_id . ', Purpose ID: ' . $data['purpose_id'] . ', Details: ' . $purpose_details);
                
                $this->mdl_request_purposes->insert([
                    'request_id' => $request_id,
                    'document_id' => $document_id,
                    'purpose_id' => $data['purpose_id'],
                    'purpose_details' => $purpose_details
                ]);
                
                $insert_id = $this->mdl_request_purposes->getInsertID();
                log_message('debug', 'Purpose inserted with ID: ' . $insert_id);
            }

            try {
                $now = date('Y-m-d H:i:s');
                $logRow = $dbService->table('document_logs')
                    ->where('request_id', $request_id)
                    ->where('document_id', $document_id)
                    ->get()
                    ->getRowArray();
                
                if ($logRow) {
                    $dbService->table('document_logs')
                        ->where('id', $logRow['id'])
                        ->update([
                            'updated_at' => $now,
                            'updated_by' => $user_id,
                        ]);
                } else {
                    $dbService->table('document_logs')->insert([
                        'request_id' => $request_id,
                        'document_id' => $document_id,
                        'created_at' => $now,  
                        'created_by' => $user_id,
                        'updated_at' => $now,
                        'updated_by' => $user_id,
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Failed to update document log: ' . $e->getMessage());
            }

            return redirect()->to('services/requests/view/' . $request_id . '/' . $document_id)
                           ->with('success', 'Document updated successfully');
        }

        $documents = $dbService->table('lib_documents')->get()->getResultArray();
        $purposes_all = $dbService->table('lib_purposes')->get()->getResultArray();
        $purpose_groups = $dbService->table('lib_purpose_groups')->get()->getResultArray();

        $grouped_purposes = [];
        $ungrouped_purposes = [];
        foreach ($purposes_all as $p) {
            if (!empty($p['purpose_group_id'])) $grouped_purposes[$p['purpose_group_id']][] = $p;
            else $ungrouped_purposes[] = $p;
        }

        log_message('debug', 'Document purpose data: ' . json_encode($document_purpose));
        log_message('debug', 'Document data: ' . json_encode($document));
        log_message('debug', 'Request data: ' . json_encode($request));
        
        $edit_data = [
            'request' => $request,
            'document' => $document,
            'document_purpose' => $document_purpose,
            'employee' => $employee,
            'unit_name' => $unit_name,
            'documents' => $documents,
            'purposes' => $ungrouped_purposes,
            'purpose_groups' => $purpose_groups,
            'grouped_purposes' => $grouped_purposes,
            'page' => $page
        ];
        
        log_message('debug', 'Edit data keys: ' . json_encode(array_keys($edit_data)));
        log_message('debug', 'Document purpose details: ' . ($document_purpose['purpose_details'] ?? 'null'));

        return view('services/requests/requests_edit_document', $edit_data);
    }

    public function delete($id)
    {
        $session = session();
        $user_id = $session->get('userid');
        if (!$user_id) return redirect()->to('/login');

        $db = \Config\Database::connect();
        $user = $db->table('users')->select('employee_idno')->where('userid', $user_id)->get()->getRowArray();
        $employee = $db->table('employees')->where('emp_idno', $user['employee_idno'])->get()->getRowArray();

        $request = $this->mdl_requests->where('id_request', $id)->where('emp_id', $employee['id_employee'])->first();
        if (!$request) throw new \CodeIgniter\Exceptions\PageNotFoundException('Request not found');

        $status = strtolower(trim($request['status'] ?? 'pending'));
        $is_locked = ($status === 'on process' || $status === 'on-process' || 
                     $status === 'processing' || $status === 'completed' ||
                     $status === 'complete');
        
        if ($is_locked) {
            return redirect()->to('services/requests')->with('error', 'Cannot delete request when status is ' . ucfirst($status));
        }

        $dbService = \Config\Database::connect('service');
        $dbService->table('request_documents')->where('request_id', $id)->delete();
        $dbService->table('request_purposes')->where('request_id', $id)->delete();
        $this->mdl_requests->delete($id);

        return redirect()->to('services/requests')->with('success', 'Request deleted successfully');
    }

    public function view_pdf($request_id, $doc_id = null)
    {
        $session = session();
        $user_id = $session->get('userid');
        
        if (!$user_id) {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $dbService = \Config\Database::connect('service');

        $user = $db->table('users')->select('employee_idno')->where('userid', $user_id)->get()->getRowArray();
        $employee = $db->table('employees')->where('emp_idno', $user['employee_idno'])->get()->getRowArray();

        $request_columns = $dbService->getFieldNames('request');
        $date_field = in_array('date_time_requested', $request_columns) ? 'date_time_requested' : 'date_requested';
        $request = $dbService->table('request r')
            ->select("r.*, r.$date_field AS date_requested")
            ->where('r.id_request', $request_id)
            ->where('r.emp_id', $employee['id_employee'])
            ->get()
            ->getRowArray();

        if (!$request) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Request not found');
        }

        if (!empty($request['date_time_requested'])) {
            $request['date_requested'] = $request['date_time_requested'];
        } else {
            $request['date_requested'] = date('Y-m-d H:i:s');
        }

        log_message('debug', 'Request data for PDF: ' . json_encode($request));
        log_message('debug', 'Contact number in request: ' . ($request['contact_num'] ?? 'NOT FOUND'));
        log_message('debug', 'Date requested in request: ' . ($request['date_requested'] ?? 'NOT FOUND'));

        $document = [];
        if ($doc_id) {
            $document = $dbService->table('request_documents rd')
                ->select('ld.document_name, rd.other_documents, rd.id as rd_id, COALESCE(s.status_name, "Pending") as status')
                ->join('lib_documents ld', 'rd.document_id = ld.id', 'left')
                ->join('status s', 'rd.status_id = s.id', 'left')
                ->where('rd.id', $doc_id)
                ->where('rd.request_id', $request_id)
                ->get()->getRowArray();
        }

        $all_documents = $dbService->table('lib_documents ld')
            ->select('ld.id, ld.document_name')
            ->get()->getResultArray();
        
        $selected_documents = $dbService->table('request_documents rd')
            ->select('ld.document_name, rd.other_documents, rd.document_id')
            ->join('lib_documents ld', 'rd.document_id = ld.id', 'left')
            ->where('rd.request_id', $request_id)
            ->get()->getResultArray();
        
        $selected_doc_names = [];
        foreach ($selected_documents as $sel_doc) {
            $selected_doc_names[] = strtolower(trim($sel_doc['document_name']));
        }

        $request_purposes = $dbService->table('request_purposes rp')
            ->select('p.purpose_name, g.purpose_group_name, rp.purpose_details, p.with_details, p.purpose_group_id')
            ->join('lib_purposes p', 'rp.purpose_id = p.id', 'left')
            ->join('lib_purpose_groups g', 'p.purpose_group_id = g.id', 'left')
            ->where('rp.request_id', $request_id)
            ->get()->getResultArray();

        $ungrouped_purposes = [];
        $grouped_purposes = [];
        foreach ($request_purposes as $purpose) {
            if (!empty($purpose['purpose_group_id'])) {
                $grouped_purposes[$purpose['purpose_group_id']][] = $purpose;
            } else {
                $ungrouped_purposes[] = $purpose;
            }
        }

        $all_purposes_db = $dbService->table('lib_purposes')->get()->getResultArray();
        $purpose_groups_db = $dbService->table('lib_purpose_groups')->get()->getResultArray();

        $all_ungrouped_purposes = [];
        $all_grouped_purposes = [];
        foreach ($all_purposes_db as $purpose) {
            if (!empty($purpose['purpose_group_id'])) {
                $all_grouped_purposes[$purpose['purpose_group_id']][] = $purpose;
            } else {
                $all_ungrouped_purposes[] = $purpose;
            }
        }

        $all_purposes = [
            'ungrouped' => $all_ungrouped_purposes,
            'grouped' => $all_grouped_purposes
        ];

        $data = [
            'request' => $request,
            'document' => $document,
            'all_documents' => $all_documents,
            'selected_documents' => $selected_documents,
            'selected_doc_names' => $selected_doc_names,
            'document_purposes' => $request_purposes,
            'all_purposes' => $all_purposes,
            'purpose_groups' => $purpose_groups_db
        ];

        log_message('debug', 'Data passed to PDF view: ' . json_encode([
            'request_has_contact_num' => isset($request['contact_num']),
            'contact_num_value' => $request['contact_num'] ?? 'NULL',
            'request_keys' => array_keys($request ?? [])
        ]));

        $html = view('services/requests/requests_pdf', $data);

        $pdfService = new \App\Libraries\PdfService();
        $pdfService->loadHtml($html);
        
        $filename = 'Request_Slip_' . $request_id . '_' . ($doc_id ?? 'all') . '.pdf';
        $pdfService->pdf_create($filename, true, 'A4', 'landscape');
        
        exit;
    }

    public function view_file($filepath = null)
    {
        log_message('debug', '=== VIEW_FILE DEBUG ===');
        log_message('debug', 'view_file called with parameter: ' . ($filepath ?? 'NULL'));
        log_message('debug', 'Full URI: ' . service('request')->getUri());
        log_message('debug', 'Path info: ' . service('request')->getUri()->getPath());
        
        log_message('debug', 'ACTUAL PARAMETER RECEIVED: "' . $filepath . '"');
        
        if (!$filepath) {
            $fullPath = service('request')->getUri()->getPath();
            log_message('debug', 'Full path for extraction: ' . $fullPath);

            $parts = explode('/view_file/', $fullPath, 2);
            if (isset($parts[1])) {
                $filepath = $parts[1];
                log_message('debug', 'Extracted filepath from URI: ' . $filepath);
            } else {
                log_message('debug', 'No /view_file/ found in path');
            }
        }
        
        if (!$filepath) {
            log_message('error', 'No filepath provided to view_file method');
            throw new \CodeIgniter\Exceptions\PageNotFoundException('No file specified');
        }

        $filepath = str_replace('../', '', $filepath); 
        $filepath = str_replace('..\\', '', $filepath); 
        
        log_message('debug', 'Filepath after security processing: ' . $filepath);
        
        if (strpos($filepath, 'http://') === 0 || strpos($filepath, 'https://') === 0) {
            log_message('debug', 'Redirecting to external URL: ' . $filepath);
            return redirect()->to($filepath);
        }
        
        log_message('debug', 'Constructing HRMIS URL for: ' . $filepath);

        $filename = basename($filepath);
        log_message('debug', 'Extracted filename: ' . $filename);
        
        $hrmisUrl = 'http://localhost/hrmis-template/public/uploads/completed_documents/' . $filename;
        log_message('debug', 'Final HRMIS URL: ' . $hrmisUrl);
        
        return redirect()->to($hrmisUrl);
    }

    public function view_file_test_segments_single($filepath)
    {
        log_message('debug', '=== SINGLE PARAMETER TEST ===');
        log_message('debug', 'Received parameter: ' . $filepath);

        $originalFilepath = $filepath;
        log_message('debug', 'Original: ' . $originalFilepath);
        
        if (stripos($filepath, 'public/') === 0) {
            $filepath = substr($filepath, 7);
        } elseif (stripos($filepath, 'public\\') === 0) {
            $filepath = substr($filepath, 7);
        }
        log_message('debug', 'After public removal: ' . $filepath);

        $cleanFilepath = ltrim($filepath, 'uploads/');
        $cleanFilepath = ltrim($cleanFilepath, 'uploads\\');
        log_message('debug', 'After uploads removal: ' . $cleanFilepath);

        $hrmisUrl = 'http://localhost/hrmis-template/public/uploads/completed_documents/' . basename($cleanFilepath);
        log_message('debug', 'Final HRMIS URL: ' . $hrmisUrl);
        
        return 'Single parameter test: ' . $originalFilepath . ' -> ' . $hrmisUrl;
    }

    public function check_pending_feedback()
    {
        $session = session();
        $user_id = $session->get('userid');
        
        if (!$user_id) {
            return $this->response->setJSON([
                'success' => false,
                'has_pending' => false,
                'message' => 'Not logged in'
            ]);
        }

        $db = \Config\Database::connect();
        $user = $db->table('users')->select('employee_idno')->where('userid', $user_id)->get()->getRowArray();
        
        if (!$user) {
            return $this->response->setJSON([
                'success' => false,
                'has_pending' => false,
                'message' => 'User not found'
            ]);
        }

        $employee = $db->table('employees')->where('emp_idno', $user['employee_idno'])->get()->getRowArray();
        
        if (!$employee) {
            return $this->response->setJSON([
                'success' => false,
                'has_pending' => false,
                'message' => 'Employee not found'
            ]);
        }

        $pending_count = $this->mdl_feedback_validation->checkPendingFeedback($employee['id_employee']);
        
        return $this->response->setJSON([
            'success' => true,
            'has_pending' => $pending_count > 0,
            'pending_count' => $pending_count,
            'redirect_url' => $pending_count > 0 
                ? site_url('services/requests?show_feedback_error=1')
                : null
        ]);
    }

    public function view_file_test($filepath = null)
    {
        log_message('debug', '=== VIEW_FILE_TEST DEBUG ===');
        log_message('debug', 'Test method called with parameter: ' . ($filepath ?? 'NULL'));
        log_message('debug', 'Full parameter: "' . $filepath . '"');
        log_message('debug', 'Parameter length: ' . strlen($filepath ?? ''));

        return 'Test successful! Parameter: ' . ($filepath ?? 'NULL') . ' | Length: ' . strlen($filepath ?? '');
    }

    public function view_file_test_segments($seg1, $seg2, $seg3, $seg4, $seg5)
    {
        log_message('debug', '=== VIEW_FILE_TEST_SEGMENTS DEBUG ===');
        log_message('debug', 'Segments received:');
        log_message('debug', 'Seg1: ' . $seg1);
        log_message('debug', 'Seg2: ' . $seg2);
        log_message('debug', 'Seg3: ' . $seg3);
        log_message('debug', 'Seg4: ' . $seg4);
        log_message('debug', 'Seg5: ' . $seg5);
        
        $fullPath = $seg1 . '/' . $seg2 . '/' . $seg3 . '/' . $seg4 . '/' . $seg5;
        log_message('debug', 'Reconstructed path: ' . $fullPath);
   
        log_message('debug', 'Testing path processing on: ' . $fullPath);
 
        $filepath = $fullPath;
        $originalFilepath = $filepath;
        log_message('debug', 'Original filepath: ' . $originalFilepath);
        
        if (stripos($filepath, 'public/') === 0) {
            $filepath = substr($filepath, 7);
        } elseif (stripos($filepath, 'public\\') === 0) {
            $filepath = substr($filepath, 7);
        }
        log_message('debug', 'After public removal: ' . $filepath);

        $cleanFilepath = ltrim($filepath, 'uploads/');
        $cleanFilepath = ltrim($cleanFilepath, 'uploads\\');
        log_message('debug', 'After uploads removal: ' . $cleanFilepath);
        
        $hrmisUrl = 'http://localhost/hrmis-template/public/uploads/completed_documents/' . basename($cleanFilepath);
        log_message('debug', 'Final HRMIS URL: ' . $hrmisUrl);
        
        return 'Segments test successful! Reconstructed: ' . $fullPath . ' | Final URL: ' . $hrmisUrl;
    }
}