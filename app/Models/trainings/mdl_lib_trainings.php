<?php

namespace App\Models\trainings;

use CodeIgniter\Model;

class mdl_lib_trainings extends Model
{
    protected $table = 'lib_trainings';
    protected $primaryKey = 'id_training';
    protected $DBGroup = 'training'; 
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
}