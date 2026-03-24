<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_documents extends Model
{
    protected $DBGroup = 'service';
    protected $table = 'lib_documents';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'document_name',
    ];
}
