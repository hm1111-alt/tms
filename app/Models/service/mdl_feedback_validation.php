<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_feedback_validation extends Model
{
    protected $DBGroup = 'service';
    protected $table = 'request';

    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect($this->DBGroup);
    }


    public function checkPendingFeedback($employeeId)
    {
        try {
            $dbFeedback = Database::connect('feedback');

            $completedDocs = $this->db->table('request r')
                ->join('request_documents rd', 'rd.request_id = r.id_request', 'left')
                ->select('rd.id as document_id')
                ->where('r.emp_id', $employeeId)
                ->where('rd.status_id', 3) 
                ->where('rd.id IS NOT NULL') 
                ->get()
                ->getResultArray();
            
            if (empty($completedDocs)) {
                return 0;
            }
            
            $completedDocIds = array_column($completedDocs, 'document_id');

            $tables = $dbFeedback->listTables();
            if (!in_array('feedback', $tables)) {
                return 0;
            }

            $feedbackDocs = $dbFeedback->table('feedback')
                ->select('document_id')
                ->whereIn('document_id', $completedDocIds)
                ->get()
                ->getResultArray();
            
            $feedbackDocIds = array_column($feedbackDocs, 'document_id');
 
            return count(array_diff($completedDocIds, $feedbackDocIds));
            
        } catch (\Exception $e) {
            log_message('error', 'Feedback validation error: ' . $e->getMessage());
            return 0;
        }
    }


    public function getDocumentFeedbackStatus($employeeId)
    {
        try {
            $dbFeedback = Database::connect('feedback');

            $query = $this->db->table('request r')
                ->join('status s', 's.id = r.status_id', 'left')
                ->join('request_documents rd', 'rd.request_id = r.id_request', 'left')
                ->select('rd.id as document_id, rd.document_id as doc_type_id, rd.file_path, d.document_name, r.id_request as req_id')
                ->join('lib_documents d', 'd.id = rd.document_id', 'left')
                ->where('r.emp_id', $employeeId)
                ->where("TRIM(LOWER(s.status_name))", "completed")
                ->where('rd.id IS NOT NULL')
                ->get();
            
            $documents = $query->getResultArray();
            
            if (empty($documents)) {
                return [];
            }
            
            $documentIds = array_column($documents, 'document_id');

            $tables = $dbFeedback->listTables();
            if (!in_array('feedback', $tables)) {
                foreach ($documents as &$document) {
                    $document['has_feedback'] = false;
                    $document['feedback_id'] = null;
                }
                return $documents;
            }

            $feedbackQuery = $dbFeedback->table('feedback')
                ->select('document_id, id as feedback_id')
                ->whereIn('document_id', $documentIds)
                ->get();
            
            $feedbackDocs = $feedbackQuery->getResultArray();
            $feedbackDocIds = array_column($feedbackDocs, 'document_id');

            foreach ($documents as &$document) {
                $document['has_feedback'] = in_array($document['document_id'], $feedbackDocIds);
                $document['feedback_id'] = null;

                foreach ($feedbackDocs as $feedback) {
                    if ($feedback['document_id'] == $document['document_id']) {
                        $document['feedback_id'] = $feedback['feedback_id'];
                        break;
                    }
                }
            }
            
            return $documents;
            
        } catch (\Exception $e) {
            log_message('error', 'Document feedback status error: ' . $e->getMessage());
            return [];
        }
    }
}