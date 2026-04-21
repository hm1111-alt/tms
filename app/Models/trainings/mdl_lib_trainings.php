<?php

namespace App\Models\trainings;

use CodeIgniter\Model;
use Config\Database;

class mdl_lib_trainings extends Model
{
    protected $table = 'lib_trainings';
    protected $primaryKey = 'id_training';
    protected $DBGroup = 'default'; 
    protected $allowedFields = [
        'training_name',
        'objective',
        'expertise',
        'training_degree',
        'training_type_id',
        'training_category_id',
        'training_datefrom',
        'training_dateto',
        'training_hours',
        'training_facilitator',
        'training_venue',
        'training_is_local',
        'training_return',
        'training_return_ratio',
        'training_return_months',
        'training_require_upload',
        'training_require_feedback',
        'training_with_cert',
        'training_refno',
        'training_cert_content',
        'training_cert_bg',
        'training_cert_emailbody',
        'training_chart_file',
        'training_added_date',
        'training_added_by'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'training_name' => 'required|min_length[3]|max_length[255]',
        'training_degree' => 'permit_empty|in_list[Basic,Intermediate,Advanced,Expert]',
        'training_type_id' => 'required|numeric',
        'training_category_id' => 'required|numeric',
        'training_datefrom' => 'required|valid_date',
        'training_dateto' => 'required|valid_date',
        'training_is_local' => 'permit_empty|in_list[0,1]',
        'training_return' => 'permit_empty|in_list[0,1]',
        'training_require_upload' => 'permit_empty|in_list[0,1]',
        'training_require_feedback' => 'permit_empty|in_list[0,1]',
        'training_with_cert' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'training_name' => [
            'required' => 'Training name is required',
            'min_length' => 'Training name must be at least 3 characters'
        ],
        'training_datefrom' => [
            'required' => 'Start date is required',
            'valid_date' => 'Please enter a valid start date'
        ],
        'training_dateto' => [
            'required' => 'End date is required',
            'valid_date' => 'Please enter a valid end date'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }


    public function getTrainingById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training by ID: ' . $e->getMessage());
            return null;
        }
    }


    public function getAllTrainings($filters = [])
    {
        try {
            $query = $this->select('lib_trainings.*, 
                                        lib_training_category.training_category_name,
                                        lib_trainings_types.training_type_name')
                ->join('lib_training_category', 'lib_training_category.id_training_category = lib_trainings.training_category_id', 'left')
                ->join('lib_trainings_types', 'lib_trainings_types.id_training_type = lib_trainings.training_type_id', 'left');
            
            if (!empty($filters['training_category_id'])) {
                $query->where('lib_trainings.training_category_id', $filters['training_category_id']);
            }
            
            if (!empty($filters['training_type_id'])) {
                $query->where('lib_trainings.training_type_id', $filters['training_type_id']);
            }
            
            if (!empty($filters['training_degree'])) {
                $query->where('lib_trainings.training_degree', $filters['training_degree']);
            }
            
            if (!empty($filters['is_local'])) {
                $query->where('lib_trainings.training_is_local', $filters['is_local']);
            }
            
            if (!empty($filters['date_range'])) {
                $query->groupStart()
                    ->where('training_datefrom <=', $filters['date_range']['end'])
                    ->where('training_dateto >=', $filters['date_range']['start'])
                    ->groupEnd();
            }
            
            if (!empty($filters['search'])) {
                $query->groupStart()
                    ->like('training_name', $filters['search'])
                    ->orLike('training_facilitator', $filters['search'])
                    ->orLike('training_venue', $filters['search'])
                    ->groupEnd();
            }
            
            return $query->orderBy('training_datefrom', 'DESC')->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching all trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function createTraining($data)
    {
        try {
            $data['training_added_date'] = date('Y-m-d H:i:s');
            $data['training_refno'] = $data['training_refno'] ?? $this->generateTrainingRefNo();
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating training: ' . $e->getMessage());
            return false;
        }
    }


    public function updateTraining($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating training: ' . $e->getMessage());
            return false;
        }
    }


    public function deleteTraining($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training: ' . $e->getMessage());
            return false;
        }
    }


    private function generateTrainingRefNo()
    {
        $prefix = 'TRN-' . date('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        return $prefix . '-' . $random;
    }


    public function trainingRefNoExists($refNo)
    {
        try {
            return $this->where('training_refno', $refNo)->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking training refno: ' . $e->getMessage());
            return false;
        }
    }


    public function getPublicTrainings()
    {
        try {
            return $this->select('lt.*, 
                                    ltc.training_category_name,
                                    ts.status_name,
                                    oti.status_id,
                                    oti.registration_deadline,
                                    oti.no_of_attendees,
                                    oti.max_no_of_attendees')
                ->from('lib_trainings lt')
                ->join('lib_training_category ltc', 'ltc.id_training_category = lt.training_category_id', 'left')
                ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'inner')
                ->join('training_status ts', 'ts.id = oti.status_id', 'left')
                ->groupBy('lt.id_training')
                ->orderBy('lt.training_datefrom', 'ASC')
                ->get()
                ->getResultArray();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching public trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function getTrainingsByCategory($categoryId)
    {
        try {
            return $this->where('training_category_id', $categoryId)
                ->orderBy('training_datefrom', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching trainings by category: ' . $e->getMessage());
            return [];
        }
    }

    public function getTrainingsByType($typeId)
    {
        try {
            return $this->where('training_type_id', $typeId)
                ->orderBy('training_datefrom', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching trainings by type: ' . $e->getMessage());
            return [];
        }
    }

    public function getUpcomingTrainings()
    {
        try {
            return $this->where('training_datefrom >=', date('Y-m-d'))
                ->orderBy('training_datefrom', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching upcoming trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function getOngoingTrainings()
    {
        try {
            $today = date('Y-m-d');
            return $this->where('training_datefrom <=', $today)
                ->where('training_dateto >=', $today)
                ->orderBy('training_dateto', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching ongoing trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function getPastTrainings()
    {
        try {
            return $this->where('training_dateto <', date('Y-m-d'))
                ->orderBy('training_dateto', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching past trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function requiresReturnService($id)
    {
        try {
            $training = $this->find($id);
            return $training && $training->training_return == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking return service requirement: ' . $e->getMessage());
            return false;
        }
    }

    public function requiresDocumentUpload($id)
    {
        try {
            $training = $this->find($id);
            return $training && $training->training_require_upload == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking document upload requirement: ' . $e->getMessage());
            return false;
        }
    }


    public function requiresFeedback($id)
    {
        try {
            $training = $this->find($id);
            return $training && $training->training_require_feedback == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking feedback requirement: ' . $e->getMessage());
            return false;
        }
    }


    public function hasCertificate($id)
    {
        try {
            $training = $this->find($id);
            return $training && $training->training_with_cert == 1;
        } catch (\Exception $e) {
            log_message('error', 'Error checking certificate availability: ' . $e->getMessage());
            return false;
        }
    }

    public function searchTrainings($keyword)
    {
        try {
            return $this->groupStart()
                ->like('training_name', $keyword)
                ->orLike('training_facilitator', $keyword)
                ->orLike('training_venue', $keyword)
                ->orLike('objective', $keyword)
                ->groupEnd()
                ->orderBy('training_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching trainings: ' . $e->getMessage());
            return [];
        }
    }

    public function countTrainings($filters = [])
    {
        try {
            $query = $this;
            
            if (!empty($filters['training_category_id'])) {
                $query->where('training_category_id', $filters['training_category_id']);
            }
            
            if (!empty($filters['training_type_id'])) {
                $query->where('training_type_id', $filters['training_type_id']);
            }
            
            return $query->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting trainings: ' . $e->getMessage());
            return 0;
        }
    }
}