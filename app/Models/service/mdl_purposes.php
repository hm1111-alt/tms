<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_purposes extends Model
{
    protected $DBGroup = 'service';
    protected $table = 'lib_purposes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'purpose_name',
        'purpose_group_id',
        'with_details',
    ];
}
