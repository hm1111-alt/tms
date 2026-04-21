<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Other_Training_Info extends Model
{
    protected $table = 'other_training_info';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'training_id',
        'status_id',
        'registration_deadline',
        'no_of_attendees',
        'max_no_of_attendees'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'training_id' => 'required|numeric|is_unique[other_training_info.training_id,id,{id}]',
        'status_id' => 'permit_empty|numeric',
        'registration_deadline' => 'permit_empty|valid_date',
        'no_of_attendees' => 'permit_empty|numeric',
        'max_no_of_attendees' => 'permit_empty|numeric'
    ];
    
    protected $validationMessages = [
        'training_id' => [
            'required' => 'Training ID is required',
            'numeric' => 'Training ID must be a number',
            'is_unique' => 'This training already has additional info'
        ],
        'registration_deadline' => [
            'valid_date' => 'Please enter a valid registration deadline'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getInfoById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training info by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function getInfoByTrainingId($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training info: ' . $e->getMessage());
            return null;
        }
    }

    public function getTrainingWithInfo($trainingId)
    {
        try {
            return $this->select('other_training_info.*, lib_trainings.training_name, training_status.status as status_name')
                ->join('lib_trainings', 'lib_trainings.id_training = other_training_info.training_id', 'left')
                ->join('training_status', 'training_status.id = other_training_info.status_id', 'left')
                ->where('other_training_info.training_id', $trainingId)
                ->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training with info: ' . $e->getMessage());
            return null;
        }
    }


    public function createInfo($data)
    {
        try {
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating training info: ' . $e->getMessage());
            return false;
        }
    }

    public function updateInfo($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating training info: ' . $e->getMessage());
            return false;
        }
    }

  
    public function deleteInfo($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training info: ' . $e->getMessage());
            return false;
        }
    }


    public function trainingHasInfo($trainingId, $excludeId = null)
    {
        try {
            $query = $this->where('training_id', $trainingId);
            
            if ($excludeId !== null) {
                $query->where('id !=', $excludeId);
            }
            
            return $query->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking if training has info: ' . $e->getMessage());
            return false;
        }
    }


    public function getAllInfoWithTraining()
    {
        try {
            return $this->select('other_training_info.*, lib_trainings.training_name, training_status.status_name as status_name')
                ->join('lib_trainings', 'lib_trainings.id_training = other_training_info.training_id', 'inner')
                ->join('training_status', 'training_status.id = other_training_info.status_id', 'left')
                ->orderBy('lib_trainings.training_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all training info: ' . $e->getMessage());
            return [];
        }
    }


    public function getTrainingsWithAvailableSlots()
    {
        try {
            return $this->select('other_training_info.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = other_training_info.training_id', 'inner')
                ->where('other_training_info.max_no_of_attendees IS NOT NULL')
                ->groupStart()
                ->where('other_training_info.no_of_attendees < other_training_info.max_no_of_attendees')
                ->orWhere('other_training_info.no_of_attendees IS NULL')
                ->groupEnd()
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching trainings with available slots: ' . $e->getMessage());
            return [];
        }
    }


    public function incrementAttendees($trainingId)
    {
        try {
            $info = $this->getInfoByTrainingId($trainingId);
            
            if (!$info) {
                return false;
            }
            
            $currentAttendees = $info->no_of_attendees ?? 0;
            return $this->update($info->id, ['no_of_attendees' => $currentAttendees + 1]);
        } catch (\Exception $e) {
            log_message('error', 'Error incrementing attendees: ' . $e->getMessage());
            return false;
        }
    }


    public function decrementAttendees($trainingId)
    {
        try {
            $info = $this->getInfoByTrainingId($trainingId);
            
            if (!$info || !$info->no_of_attendees) {
                return false;
            }
            
            $newCount = max(0, $info->no_of_attendees - 1);
            return $this->update($info->id, ['no_of_attendees' => $newCount]);
        } catch (\Exception $e) {
            log_message('error', 'Error decrementing attendees: ' . $e->getMessage());
            return false;
        }
    }


    public function hasAvailableSlots($trainingId)
    {
        try {
            $info = $this->getInfoByTrainingId($trainingId);
            
            if (!$info || !$info->max_no_of_attendees) {
                return true; // No limit set, so slots are available
            }
            
            $currentAttendees = $info->no_of_attendees ?? 0;
            return $currentAttendees < $info->max_no_of_attendees;
        } catch (\Exception $e) {
            log_message('error', 'Error checking available slots: ' . $e->getMessage());
            return false;
        }
    }

    public function isRegistrationOpen($trainingId)
    {
        try {
            $info = $this->getInfoByTrainingId($trainingId);
            
            if (!$info || !$info->registration_deadline) {
                return true; // No deadline set, so registration is open
            }
            
            return strtotime($info->registration_deadline) >= time();
        } catch (\Exception $e) {
            log_message('error', 'Error checking registration status: ' . $e->getMessage());
            return false;
        }
    }

 
    public function searchTrainingInfo($keyword)
    {
        try {
            return $this->select('other_training_info.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = other_training_info.training_id', 'inner')
                ->groupStart()
                ->like('lib_trainings.training_name', $keyword)
                ->groupEnd()
                ->orderBy('lib_trainings.training_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching training info: ' . $e->getMessage());
            return [];
        }
    }

    public function countTrainingInfo()
    {
        try {
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting training info: ' . $e->getMessage());
            return 0;
        }
    }


    public function getTrainingsByStatus($statusId)
    {
        try {
            return $this->where('status_id', $statusId)
                ->orderBy('id', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching trainings by status: ' . $e->getMessage());
            return [];
        }
    }
}
