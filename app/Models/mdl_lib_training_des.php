<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Lib_Training_Des extends Model
{
    protected $table = 'lib_training_des';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'training_id',
        'training_description'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'training_id' => 'required|numeric|is_unique[lib_training_des.training_id,id,{id}]',
        'training_description' => 'required|min_length[10]'
    ];
    
    protected $validationMessages = [
        'training_id' => [
            'required' => 'Training ID is required',
            'numeric' => 'Training ID must be a number',
            'is_unique' => 'This training already has a description'
        ],
        'training_description' => [
            'required' => 'Training description is required',
            'min_length' => 'Training description must be at least 10 characters'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }

    public function getDescriptionById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training description by ID: ' . $e->getMessage());
            return null;
        }
    }


    public function getDescriptionByTrainingId($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training description: ' . $e->getMessage());
            return null;
        }
    }


    public function getTrainingWithDescription($trainingId)
    {
        try {
            return $this->select('lib_training_des.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = lib_training_des.training_id', 'left')
                ->where('lib_training_des.training_id', $trainingId)
                ->first();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training with description: ' . $e->getMessage());
            return null;
        }
    }

    public function createDescription($data)
    {
        try {
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating training description: ' . $e->getMessage());
            return false;
        }
    }

    public function updateDescription($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating training description: ' . $e->getMessage());
            return false;
        }
    }


    public function deleteDescription($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training description: ' . $e->getMessage());
            return false;
        }
    }

    public function trainingHasDescription($trainingId, $excludeId = null)
    {
        try {
            $query = $this->where('training_id', $trainingId);
            
            if ($excludeId !== null) {
                $query->where('id !=', $excludeId);
            }
            
            return $query->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking if training has description: ' . $e->getMessage());
            return false;
        }
    }

 
    public function getAllDescriptionsWithTraining()
    {
        try {
            return $this->select('lib_training_des.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = lib_training_des.training_id', 'inner')
                ->orderBy('lib_trainings.training_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all descriptions: ' . $e->getMessage());
            return [];
        }
    }

    public function searchDescriptions($keyword)
    {
        try {
            return $this->select('lib_training_des.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = lib_training_des.training_id', 'inner')
                ->groupStart()
                ->like('lib_training_des.training_description', $keyword)
                ->orLike('lib_trainings.training_name', $keyword)
                ->groupEnd()
                ->orderBy('lib_trainings.training_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching descriptions: ' . $e->getMessage());
            return [];
        }
    }

 
    public function countDescriptions()
    {
        try {
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting descriptions: ' . $e->getMessage());
            return 0;
        }
    }
}
