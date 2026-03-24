<?php

namespace App\Models\feedback;

use CodeIgniter\Model;

class Mdl_feedback extends Model
{
    protected $DBGroup = 'feedback'; 
    protected $table = 'feedback';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'request_id',
        'document_id',
        'overall_rating',
        'experienced_harassment',
        'harassment_details',
        'recommend_clsu',
        'suggestions',
        'created_at'
    ];

    public function tableExists()
    {
        try {
            $db = \Config\Database::connect($this->DBGroup);
            $tables = $db->listTables();

            $requiredTables = ['feedback', 'feedback_question', 'feedback_answer'];
            foreach ($requiredTables as $table) {
                if (!in_array($table, $tables)) {
                    log_message('error', "Required table '{$table}' does not exist in feedback database");
                    return false;
                }
            }
            
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error checking feedback tables: ' . $e->getMessage());
            return false;
        }
    }

    public function insertFeedback(array $data, array $answers = [])
    {
        try {
            if (!$this->tableExists()) {
                log_message('error', 'Cannot insert feedback: required tables do not exist');
                return 0;
            }
            
            $this->insert($data);
            $feedbackId = $this->getInsertID();

        if (!empty($answers)) {
            try {
                $builder = $this->db->table('feedback_answer');
                foreach ($answers as $questionId => $answer) {
                    $builder->insert([
                        'feedback_id' => $feedbackId,
                        'question_id' => $questionId,
                        'answer' => $answer
                    ]);
                }
            } catch (\Exception $e) {
                log_message('error', 'Error inserting feedback answers: ' . $e->getMessage());
            }
        }

        return $feedbackId;
        } catch (\Exception $e) {
            log_message('error', 'Error inserting feedback: ' . $e->getMessage());
            return 0;
        }
    }

    public function getQuestions()
    {
        try {
            $dbFeedback = \Config\Database::connect('feedback');
            $tables = $dbFeedback->listTables();
            if (!in_array('feedback_question', $tables)) {
                log_message('error', 'feedback_question table does not exist in feedback database');
                return [];
            }

            $questions = $dbFeedback->table('feedback_question')
                           ->select('id, code, question as question_text') 
                           ->get()
                           ->getResultArray();
            
            return $questions;
        } catch (\Exception $e) {
            log_message('error', 'Error in getQuestions: ' . $e->getMessage());
            return [];
        }
    }

    public function getFeedback($request_id, $document_id)
    {
        try {
            $dbFeedback = \Config\Database::connect('feedback');

            $tables = $dbFeedback->listTables();
            if (!in_array('feedback', $tables)) {
                log_message('error', 'feedback table does not exist in feedback database');
                return null;
            }
            
            return $dbFeedback->table('feedback')
                           ->where('request_id', $request_id)
                           ->where('document_id', $document_id)
                           ->get()
                           ->getRowArray();
        } catch (\Exception $e) {
            log_message('error', 'Error in getFeedback: ' . $e->getMessage());
            return null;
        }
    }


    public function getAnswers($request_id, $document_id)
    {
        try {
            $dbFeedback = \Config\Database::connect('feedback');

            $tables = $dbFeedback->listTables();
            if (!in_array('feedback_question', $tables)) {
                log_message('error', 'feedback_question table does not exist in feedback database');
                return [];
            }

            $columns = $dbFeedback->getFieldNames('feedback_question');

            $questionTextColumn = 'question'; 

            if (!in_array('feedback_answer', $tables)) {
                log_message('error', 'feedback_answer table does not exist in feedback database');
                return [];
            }
            
            return $dbFeedback->table('feedback_answer fa')
                           ->select("fa.*, fq.{$questionTextColumn} as question_text, fq.code")
                           ->join('feedback_question fq', 'fq.id = fa.question_id')
                           ->join('feedback f', 'f.id = fa.feedback_id')
                           ->where('f.request_id', $request_id)
                           ->where('f.document_id', $document_id)
                           ->get()
                           ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error in getAnswers: ' . $e->getMessage());
            return [];
        }
    }
}