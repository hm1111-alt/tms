<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Training_Sessions extends Model
{
    protected $table = 'training_sessions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'training_id',
        'session_date',
        'session_start_time',
        'session_end_time',
        'session_code',
        'started_at',
        'ended_at'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'training_id' => 'required|numeric',
        'session_date' => 'required|valid_date',
        'session_start_time' => 'required',
        'session_end_time' => 'required|differs[session_start_time]',
        'session_code' => 'permit_empty|max_length[50]'
    ];
    
    protected $validationMessages = [
        'training_id' => [
            'required' => 'Training ID is required'
        ],
        'session_date' => [
            'required' => 'Session date is required',
            'valid_date' => 'Please enter a valid session date'
        ],
        'session_start_time' => [
            'required' => 'Start time is required'
        ],
        'session_end_time' => [
            'required' => 'End time is required',
            'differs' => 'End time must be different from start time'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getSessionById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching session by ID: ' . $e->getMessage());
            return null;
        }
    }

 
    public function getSessionsByTrainingId($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)
                ->orderBy('session_date', 'ASC')
                ->orderBy('session_start_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training sessions: ' . $e->getMessage());
            return [];
        }
    }


    public function getTrainingWithSessions($trainingId)
    {
        try {
            return $this->select('training_sessions.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = training_sessions.training_id', 'left')
                ->where('training_sessions.training_id', $trainingId)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training with sessions: ' . $e->getMessage());
            return [];
        }
    }


    public function createSession($data)
    {
        try {
            $data['session_code'] = $data['session_code'] ?? $this->generateSessionCode();
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating training session: ' . $e->getMessage());
            return false;
        }
    }


    public function updateSession($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating training session: ' . $e->getMessage());
            return false;
        }
    }


    public function deleteSession($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training session: ' . $e->getMessage());
            return false;
        }
    }

 
    public function deleteSessionsByTraining($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->delete();
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training sessions: ' . $e->getMessage());
            return false;
        }
    }


    private function generateSessionCode()
    {
        $prefix = 'SES-' . date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return $prefix . '-' . $random;
    }


    public function sessionCodeExists($code)
    {
        try {
            return $this->where('session_code', $code)->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking session code: ' . $e->getMessage());
            return false;
        }
    }


    public function getSessionByCode($code)
    {
        try {
            return $this->where('session_code', $code)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching session by code: ' . $e->getMessage());
            return null;
        }
    }


    public function getUpcomingSessions()
    {
        try {
            return $this->where('session_date >=', date('Y-m-d'))
                ->orderBy('session_date', 'ASC')
                ->orderBy('session_start_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching upcoming sessions: ' . $e->getMessage());
            return [];
        }
    }

    public function getTodaySessions()
    {
        try {
            return $this->where('session_date', date('Y-m-d'))
                ->orderBy('session_start_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching today\'s sessions: ' . $e->getMessage());
            return [];
        }
    }


    public function getPastSessions()
    {
        try {
            return $this->where('session_date <', date('Y-m-d'))
                ->orderBy('session_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching past sessions: ' . $e->getMessage());
            return [];
        }
    }

    public function getAllSessionsWithTraining()
    {
        try {
            return $this->select('training_sessions.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = training_sessions.training_id', 'inner')
                ->orderBy('training_sessions.session_date', 'DESC')
                ->orderBy('training_sessions.session_start_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all sessions: ' . $e->getMessage());
            return [];
        }
    }

    public function searchSessions($keyword)
    {
        try {
            return $this->select('training_sessions.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = training_sessions.training_id', 'inner')
                ->groupStart()
                ->like('lib_trainings.training_name', $keyword)
                ->orLike('training_sessions.session_code', $keyword)
                ->groupEnd()
                ->orderBy('training_sessions.session_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching sessions: ' . $e->getMessage());
            return [];
        }
    }

 
    public function countSessionsByTraining($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting sessions: ' . $e->getMessage());
            return 0;
        }
    }


    public function countTotalSessions()
    {
        try {
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting total sessions: ' . $e->getMessage());
            return 0;
        }
    }

    public function batchCreateSessions($trainingId, $sessions)
    {
        try {
            $insertData = [];
            foreach ($sessions as $session) {
                $insertData[] = [
                    'training_id' => $trainingId,
                    'session_date' => $session['session_date'],
                    'session_start_time' => $session['session_start_time'],
                    'session_end_time' => $session['session_end_time'],
                    'session_code' => $session['session_code'] ?? $this->generateSessionCode()
                ];
            }
            
            return $this->insertBatch($insertData);
        } catch (\Exception $e) {
            log_message('error', 'Error batch creating sessions: ' . $e->getMessage());
            return false;
        }
    }


    public function isSessionOngoing($id)
    {
        try {
            $session = $this->find($id);
            
            if (!$session) {
                return false;
            }
            
            $today = date('Y-m-d');
            $now = date('H:i:s');
            
            if ($session->session_date != $today) {
                return false;
            }
            
            return $now >= $session->session_start_time && $now <= $session->session_end_time;
        } catch (\Exception $e) {
            log_message('error', 'Error checking session status: ' . $e->getMessage());
            return false;
        }
    }
}
