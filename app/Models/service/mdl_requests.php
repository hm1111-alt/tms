<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_requests extends Model
{
    protected $DBGroup = 'service';
    protected $table = 'request';
    protected $primaryKey = 'id_request';

    protected $allowedFields = [
        'emp_id',
        'emp_name',
        'unit_college',
        'contact_num',
        'submitted_by',
        'date_requested',
        'date_time_requested',
        'req_num',
    ];

    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect($this->DBGroup);
    }

   public function get_requests($condition = '', $page = 1, $limit = 'all', $order_by = 'date_requested', $sort_by = 'DESC')
{
    $order_by = $order_by ?: 'date_requested';
    $sort_by = strtoupper($sort_by) === 'ASC' ? 'ASC' : 'DESC';

    $limitQuery = '';
    if ($limit !== 'all' && is_numeric($limit)) {
        $offset = ($page - 1) * $limit;
        $limitQuery = " LIMIT {$offset}, {$limit}";
    }

    $sql = "SELECT * FROM {$this->table} WHERE id_request != '' {$condition} ORDER BY {$order_by} {$sort_by} {$limitQuery}";
    $query = $this->db->query($sql);

    return $query->getResult();
}

    public function count_requests($condition = '')
    {
        $query = $this->db->query("
            SELECT COUNT(*) as counts
            FROM {$this->table}
            WHERE id_request != '' {$condition}
        ");
            
        return $query->getRow('counts');
    }

    public function generate_request_number()
    {
        $year = date('Y');

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = '';
        for ($i = 0; $i < 4; $i++) {
            $random_string .= $characters[rand(0, strlen($characters) - 1)];
        }

        $req_num = sprintf('REQ-%s-%s', $year, $random_string);

        $existing = $this->db->table($this->table)
            ->where('req_num', $req_num)
            ->countAllResults();

        if ($existing > 0) {
            return $this->generate_request_number();
        }
        
        return $req_num;
    }
}
