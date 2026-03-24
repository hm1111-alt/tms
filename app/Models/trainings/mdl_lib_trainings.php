<?php

namespace App\Models\trainings;

use CodeIgniter\Model;

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
    protected $useTimestamps = true;
    protected $createdField = 'training_added_date';
    protected $updatedField = 'training_added_date';

    public function getTrainingById($id)
    {
        return $this->find($id);
    }

    public function getAllTrainings()
    {
        return $this->orderBy('training_name', 'ASC')->findAll();
    }

    public function getPublicTrainings()
    {
        try {
            $db = \Config\Database::connect();
            
            if ($db) {
                $sql = "SELECT 
                            lt.id_training,
                            lt.training_name,
                            lt.training_category_id,
                            ltc.training_category_name,
                            lt.training_datefrom,
                            lt.training_dateto,
                            lt.training_deadline as joining_deadline,
                            lt.training_facilitator as facilitator,
                            lt.training_venue as venue,
                            lt.training_hours,
                            lt.objective,
                            lt.expertise,
                            lt.training_is_local,
                            lt.status_id,
                            ls.status as status_name,
                            lt.training_added_date,
                            COUNT(ta.user_id) as attendee_count
                        FROM lib_trainings lt
                        LEFT JOIN lib_training_category ltc ON lt.training_category_id = ltc.id_training_category
                        LEFT JOIN training_status ls ON lt.status_id = ls.id
                        LEFT JOIN training_attendees ta ON lt.id_training = ta.training_id
                        GROUP BY lt.id_training
                        ORDER BY lt.training_datefrom DESC";
                
                $query = $db->query($sql);
                return $query->getResultArray();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching public trainings: ' . $e->getMessage());
        }
        
        return [];
    }
}