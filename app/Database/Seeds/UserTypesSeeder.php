<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_user_type' => 1,
                'user_type_name' => 'Admin',
                'user_type_description' => 'System administrator with full access to all features and settings',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_user_type' => 2,
                'user_type_name' => 'Employee',
                'user_type_description' => 'Regular employee with access to training enrollment and personal records',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_user_type' => 3,
                'user_type_name' => 'Guest',
                'user_type_description' => 'Guest user with limited access to view public training information only',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('user_types')->insertBatch($data);
    }
}
