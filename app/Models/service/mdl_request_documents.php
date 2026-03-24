<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_request_documents extends Model
{
    protected $DBGroup = 'service';
    protected $table = 'request_documents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'request_id',
        'document_id',
        'other_documents'
    ];
}
