<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_purpose_groups extends Model
{
    protected $DBGroup = 'service';
    protected $table = 'lib_purpose_groups';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'purpose_group_name',
    ];

     
}
