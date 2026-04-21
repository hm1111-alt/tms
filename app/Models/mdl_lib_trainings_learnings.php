<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Lib_Trainings_Learnings extends Model
{
    protected $table = 'lib_trainings_learnings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'training_id',
        'training_learning'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'training_id' => 'required|numeric',
        'training_learning' => 'required|min_length[10]'
    ];
    
    protected $validationMessages = [
        'training_id' => [
            'required' => 'Training ID is required',
            'numeric' => 'Training ID must be a number'
        ],
        'training_learning' => [
            'required' => 'Training learning is required',
            'min_length' => 'Training learning must be at least 10 characters'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getLearningById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training learning by ID: ' . $e->getMessage());
            return null;
        }
    }

 
    public function getLearningsByTrainingId($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)
                ->orderBy('id', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training learnings: ' . $e->getMessage());
            return [];
        }
    }

 
    public function getTrainingWithLearnings($trainingId)
    {
        try {
            return $this->select('lib_trainings_learnings.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = lib_trainings_learnings.training_id', 'left')
                ->where('lib_trainings_learnings.training_id', $trainingId)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training with learnings: ' . $e->getMessage());
            return [];
        }
    }


    public function createLearning($data)
    {
        try {
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating training learning: ' . $e->getMessage());
            return false;
        }
    }

    public function updateLearning($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating training learning: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteLearning($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training learning: ' . $e->getMessage());
            return false;
        }
    }


    public function deleteLearningsByTraining($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->delete();
        } catch (\Exception $e) {
            log_message('error', 'Error deleting trainings learnings: ' . $e->getMessage());
            return false;
        }
    }

 
    public function getAllLearningsWithTraining()
    {
        try {
            return $this->select('lib_trainings_learnings.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = lib_trainings_learnings.training_id', 'inner')
                ->orderBy('lib_trainings.training_name', 'ASC')
                ->orderBy('lib_trainings_learnings.id', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all learnings: ' . $e->getMessage());
            return [];
        }
    }

    public function searchLearnings($keyword)
    {
        try {
            return $this->select('lib_trainings_learnings.*, lib_trainings.training_name')
                ->join('lib_trainings', 'lib_trainings.id_training = lib_trainings_learnings.training_id', 'inner')
                ->groupStart()
                ->like('lib_trainings_learnings.training_learning', $keyword)
                ->orLike('lib_trainings.training_name', $keyword)
                ->groupEnd()
                ->orderBy('lib_trainings.training_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching learnings: ' . $e->getMessage());
            return [];
        }
    }


    public function countLearningsByTraining($trainingId)
    {
        try {
            return $this->where('training_id', $trainingId)->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting learnings: ' . $e->getMessage());
            return 0;
        }
    }


    public function countTotalLearnings()
    {
        try {
            return $this->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting total learnings: ' . $e->getMessage());
            return 0;
        }
    }

  
    public function batchCreateLearnings($trainingId, $learnings)
    {
        try {
            $insertData = [];
            foreach ($learnings as $learning) {
                $insertData[] = [
                    'training_id' => $trainingId,
                    'training_learning' => $learning
                ];
            }
            
            return $this->insertBatch($insertData);
        } catch (\Exception $e) {
            log_message('error', 'Error batch creating learnings: ' . $e->getMessage());
            return false;
        }
    }

 
    public function updateLearningOrder($orderedIds)
    {
        try {
            foreach ($orderedIds as $order => $id) {
                $this->update($id, ['order' => $order + 1]);
            }
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Error updating learning order: ' . $e->getMessage());
            return false;
        }
    }
}
