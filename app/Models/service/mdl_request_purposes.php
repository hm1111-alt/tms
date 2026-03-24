<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_request_purposes extends Model
{
    protected $DBGroup = 'service';
    protected $table = 'request_purposes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'request_id',
        'document_id',
        'purpose_id',
        'purpose_details',
    ];
}
