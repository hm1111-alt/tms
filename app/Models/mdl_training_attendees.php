<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Training_Attendees extends Model
{
    protected $table = 'training_attendees';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'training_id',
        'user_id'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'training_id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'training_id' => 'is_unique[training_attendees.training_id,user_id,{user_id},id,{id}]'
    ];
    
    protected $validationMessages = [
        'training_id' => [
            'required' => 'Training ID is required'
        ],
        'user_id' => [
            'required' => 'User ID is required'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAttendeeById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching attendee by ID: ' . $e->getMessage());
            return null;
        }
    }


    public function isUserRegistered($trainingId, $userId)
    {
        try {
            return $this->where('training_id', $trainingId)
                ->where('user_id', $userId)
                ->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking user registration: ' . $e->getMessage());
            return false;
        }
    }

 
    public function getAttendeeByTrainingAndUser($trainingId, $userId)
    {
        try {
            return $this->where('training_id', $trainingId)
                ->where('user_id', $userId)
                ->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching attendee record: ' . $e->getMessage());
            return null;
        }
    }

    public function getAttendeesByTrainingId($trainingId)
    {
        try {
            return $this->select('training_attendees.*, users.username, users.user_email')
                ->join('users', 'users.userid = training_attendees.user_id', 'left')
                ->where('training_attendees.training_id', $trainingId)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training attendees: ' . $e->getMessage());
            return [];
        }
    }


    public function getTrainingsByUserId($userId)
    {
        try {
            return $this->select('training_attendees.*, lib_trainings.training_name, lib_trainings.training_datefrom, lib_trainings.training_dateto')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendees.training_id', 'left')
                ->where('training_attendees.user_id', $userId)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user trainings: ' . $e->getMessage());
            return [];
        }
    }


    public function createAttendee($data)
    {
        try {
            // Check if already registered
            if ($this->isUserRegistered($data['training_id'], $data['user_id'])) {
                return false; // Already registered
            }
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating attendee: ' . $e->getMessage());
            return false;
        }
    }


    public function updateAttendee($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating attendee: ' . $e->getMessage());
            return false;
        }
    }

 
    public function deleteAttendee($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting attendee: ' . $e->getMessage());
            return false;
        }
    }

    public function removeUserFromTraining($trainingId, $userId)
    {
        try {
            return $this->where('training_id', $trainingId)
                ->where('user_id', $userId)
                ->delete();
        } catch (\Exception $e) {
            log_message('error', 'Error removing user from training: ' . $e->getMessage());
            return false;
        }
    }

 
    public function countAttendeesByTraining($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting attendees: ' . $e->getMessage());
            return 0;
        }
    }

    public function countTrainingsByUser($userId)
    {
        try {
            return $this->where('user_id', $userId)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting user trainings: ' . $e->getMessage());
            return 0;
        }
    }

 
    public function getAllAttendeesWithDetails()
    {
        try {
            return $this->select('training_attendees.*, 
                                        lib_trainings.training_name,
                                        users.username,
                                        users.user_email')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendees.training_id', 'inner')
                ->join('users', 'users.userid = training_attendees.user_id', 'inner')
                ->orderBy('training_attendees.id', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all attendees: ' . $e->getMessage());
            return [];
        }
    }


    public function batchRegisterUsers($trainingId, $userIds)
    {
        try {
            $insertData = [];
            foreach ($userIds as $userId) {
                // Skip if already registered
                if (!$this->isUserRegistered($trainingId, $userId)) {
                    $insertData[] = [
                        'training_id' => $trainingId,
                        'user_id' => $userId
                    ];
                }
            }
            
            return !empty($insertData) ? $this->insertBatch($insertData) : true;
        } catch (\Exception $e) {
            log_message('error', 'Error batch registering users: ' . $e->getMessage());
            return false;
        }
    }


    public function getUniqueUsers()
    {
        try {
            return $this->select('DISTINCT user_id')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching unique users: ' . $e->getMessage());
            return [];
        }
    }

 
    public function getUserTrainingsInDateRange($userId, $startDate, $endDate)
    {
        try {
            return $this->select('training_attendees.*, lib_trainings.training_name, lib_trainings.training_datefrom, lib_trainings.training_dateto')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendees.training_id', 'left')
                ->where('training_attendees.user_id', $userId)
                ->groupStart()
                ->where('lib_trainings.training_datefrom >=', $startDate)
                ->where('lib_trainings.training_dateto <=', $endDate)
                ->groupEnd()
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching user trainings in date range: ' . $e->getMessage());
            return [];
        }
    }


    public function searchAttendees($keyword)
    {
        try {
            return $this->select('training_attendees.*, lib_trainings.training_name, users.username, users.user_email')
                ->join('lib_trainings', 'lib_trainings.id_training = training_attendees.training_id', 'inner')
                ->join('users', 'users.userid = training_attendees.user_id', 'inner')
                ->groupStart()
                ->like('users.username', $keyword)
                ->orLike('users.user_email', $keyword)
                ->groupEnd()
                ->orderBy('users.username', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching attendees: ' . $e->getMessage());
            return [];
        }
    }

    public function countTotalAttendees()
    {
        try {
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting total attendees: ' . $e->getMessage());
            return 0;
        }
    }
}
