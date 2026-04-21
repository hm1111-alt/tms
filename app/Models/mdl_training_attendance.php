<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Training_Attendance extends Model
{
    protected $table = 'training_attendance';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'session_id',
        'training_id',
        'user_id',
        'attendance_time',
        'attendance_date',
        'attendance_file',
        'is_verified'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'session_id' => 'required|numeric',
        'training_id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'attendance_date' => 'required|valid_date',
        'attendance_time' => 'permit_empty',
        'is_verified' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'session_id' => [
            'required' => 'Session ID is required'
        ],
        'training_id' => [
            'required' => 'Training ID is required'
        ],
        'user_id' => [
            'required' => 'User ID is required'
        ],
        'attendance_date' => [
            'required' => 'Attendance date is required',
            'valid_date' => 'Please enter a valid date'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getAttendanceById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching attendance by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function hasAttendance($sessionId, $userId)
    {
        try {
            return $this->where('session_id', $sessionId)
                ->where('user_id', $userId)
                ->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking attendance: ' . $e->getMessage());
            return false;
        }
    }

  
    public function getAttendanceBySessionAndUser($sessionId, $userId)
    {
        try {
            return $this->where('session_id', $sessionId)
                ->where('user_id', $userId)
                ->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching attendance record: ' . $e->getMessage());
            return null;
        }
    }

 
    public function getAttendanceBySessionId($sessionId)
    {
        try {
            return $this->select('training_attendance.*, users.username, users.user_email')
                ->join('users', 'users.userid = training_attendance.user_id', 'left')
                ->where('training_attendance.session_id', $sessionId)
                ->orderBy('training_attendance.attendance_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching session attendance: ' . $e->getMessage());
            return [];
        }
    }

  
    public function getAttendanceByTrainingId($trainingId)
    {
        try {
            return $this->select('training_attendance.*, 
                                        users.username,
                                        users.user_email,
                                        training_sessions.session_date,
                                        training_sessions.session_start_time,
                                        training_sessions.session_end_time')
                ->join('users', 'users.userid = training_attendance.user_id', 'left')
                ->join('training_sessions', 'training_sessions.id = training_attendance.session_id', 'left')
                ->where('training_attendance.training_id', $trainingId)
                ->orderBy('training_attendance.attendance_date', 'DESC')
                ->orderBy('training_attendance.attendance_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training attendance: ' . $e->getMessage());
            return [];
        }
    }


    public function getAttendanceByUserId($userId)
    {
        try {
            return $this->select('training_attendance.*, 
                                        lib_trainings.training_name,
                                        training_sessions.session_date,
                                        training_sessions.session_code')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendance.training_id', 'left')
                ->join('training_sessions', 'training_sessions.id = training_attendance.session_id', 'left')
                ->where('training_attendance.user_id', $userId)
                ->orderBy('training_attendance.attendance_date', 'DESC')
                ->orderBy('training_attendance.attendance_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user attendance: ' . $e->getMessage());
            return [];
        }
    }

    public function createAttendance($data)
    {
        try {
            // Check if already marked
            if ($this->hasAttendance($data['session_id'], $data['user_id'])) {
                return false; // Already has attendance
            }
            
            $data['attendance_date'] = $data['attendance_date'] ?? date('Y-m-d');
            $data['attendance_time'] = $data['attendance_time'] ?? date('H:i:s');
            $data['is_verified'] = $data['is_verified'] ?? 0;
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating attendance: ' . $e->getMessage());
            return false;
        }
    }

    public function updateAttendance($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating attendance: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteAttendance($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting attendance: ' . $e->getMessage());
            return false;
        }
    }

 
    public function markAsVerified($id)
    {
        try {
            return $this->update($id, ['is_verified' => 1]);
        } catch (\Exception $e) {
            log_message('error', 'Error marking attendance as verified: ' . $e->getMessage());
            return false;
        }
    }

 
    public function isVerified($id)
    {
        try {
            $record = $this->find($id);
            return $record && $record->is_verified == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking verification status: ' . $e->getMessage());
            return false;
        }
    }

    public function countAttendanceBySession($sessionId)
    {
        try {
            return $this->where('session_id', $sessionId)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting session attendance: ' . $e->getMessage());
            return 0;
        }
    }


    public function countAttendanceByTraining($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting training attendance: ' . $e->getMessage());
            return 0;
        }
    }


    public function countUniqueAttendeesByTraining($trainingId)
    {
        try {
            return $this->select('DISTINCT user_id')
                ->where('training_id', $trainingId)
                ->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting unique attendees: ' . $e->getMessage());
            return 0;
        }
    }

 
    public function getTodayAttendance()
    {
        try {
            return $this->select('training_attendance.*, 
                                        users.username,
                                        lib_trainings.training_name,
                                        training_sessions.session_code')
                ->join('users', 'users.userid = training_attendance.user_id', 'left')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendance.training_id', 'left')
                ->join('training_sessions', 'training_sessions.id = training_attendance.session_id', 'left')
                ->where('training_attendance.attendance_date', date('Y-m-d'))
                ->orderBy('training_attendance.attendance_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching today\'s attendance: ' . $e->getMessage());
            return [];
        }
    }

 
    public function getAttendanceInDateRange($startDate, $endDate)
    {
        try {
            return $this->select('training_attendance.*, 
                                        users.username,
                                        lib_trainings.training_name,
                                        training_sessions.session_date')
                ->join('users', 'users.userid = training_attendance.user_id', 'left')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendance.training_id', 'left')
                ->join('training_sessions', 'training_sessions.id = training_attendance.session_id', 'left')
                ->where('training_attendance.attendance_date >=', $startDate)
                ->where('training_attendance.attendance_date <=', $endDate)
                ->orderBy('training_attendance.attendance_date', 'DESC')
                ->orderBy('training_attendance.attendance_time', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching attendance in date range: ' . $e->getMessage());
            return [];
        }
    }


    public function batchCreateAttendance($sessionId, $trainingId, $userIds)
    {
        try {
            $insertData = [];
            foreach ($userIds as $userId) {
                // Skip if already has attendance
                if (!$this->hasAttendance($sessionId, $userId)) {
                    $insertData[] = [
                        'session_id' => $sessionId,
                        'training_id' => $trainingId,
                        'user_id' => $userId,
                        'attendance_date' => date('Y-m-d'),
                        'attendance_time' => date('H:i:s'),
                        'is_verified' => 0
                    ];
                }
            }
            
            return !empty($insertData) ? $this->insertBatch($insertData) : true;
        } catch (\Exception $e) {
            log_message('error', 'Error batch creating attendance: ' . $e->getMessage());
            return false;
        }
    }


    public function searchAttendance($keyword)
    {
        try {
            return $this->select('training_attendance.*, 
                                        users.username,
                                        users.user_email,
                                        lib_trainings.training_name,
                                        training_sessions.session_code')
                ->join('users', 'users.userid = training_attendance.user_id', 'left')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendance.training_id', 'left')
                ->join('training_sessions', 'training_sessions.id = training_attendance.session_id', 'left')
                ->groupStart()
                ->like('users.username', $keyword)
                ->orLike('users.user_email', $keyword)
                ->orLike('lib_trainings.training_name', $keyword)
                ->groupEnd()
                ->orderBy('training_attendance.attendance_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching attendance: ' . $e->getMessage());
            return [];
        }
    }


    public function countTotalAttendance()
    {
        try {
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting total attendance: ' . $e->getMessage());
            return 0;
        }
    }

 
    public function getUnverifiedAttendance()
    {
        try {
            return $this->select('training_attendance.*, 
                                        users.username,
                                        lib_trainings.training_name,
                                        training_sessions.session_code')
                ->join('users', 'users.userid = training_attendance.user_id', 'left')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendance.training_id', 'left')
                ->join('training_sessions', 'training_sessions.id = training_attendance.session_id', 'left')
                ->where('training_attendance.is_verified', 0)
                ->orderBy('training_attendance.attendance_date', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching unverified attendance: ' . $e->getMessage());
            return [];
        }
    }
}
