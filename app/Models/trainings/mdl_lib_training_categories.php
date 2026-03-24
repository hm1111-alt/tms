<?php

namespace App\Models\trainings;

use CodeIgniter\Model;

class mdl_lib_training_categories extends Model
{
    public function getAllCategories()
    {
        try {
            $db = \Config\Database::connect('training');
            
            if ($db) {
                $sql = "SELECT id_training_category AS id, training_category_name AS category_name 
                        FROM lib_training_category 
                        WHERE is_deleted = 0 AND is_visible = 1 
                        ORDER BY training_category_name ASC";
                
                $query = $db->query($sql);
                $result = $query->getResultArray();
                
                if (!empty($result)) {
                    return $result;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Database fetch failed: ' . $e->getMessage());
        }

        return [
            ['id' => 1, 'category_name' => 'Management'],
            ['id' => 2, 'category_name' => 'Technical'], 
            ['id' => 3, 'category_name' => 'Leadership'],
            ['id' => 4, 'category_name' => 'Professional Development'],
            ['id' => 5, 'category_name' => 'General']
        ];
    }
    
    public function getCategoryById($id)
    {
        try {
            $db = \Config\Database::connect('training');
            
            if ($db) {
                $sql = "SELECT id_training_category AS id, training_category_name AS category_name 
                        FROM lib_training_category 
                        WHERE id_training_category = ? AND is_deleted = 0 AND is_visible = 1";
                
                $query = $db->query($sql, [$id]);
                $result = $query->getRowArray();
                
                return $result;
            }
        } catch (\Exception $e) {
            log_message('error', 'Database fetch by ID failed: ' . $e->getMessage());
        }
        
        $categories = [
            ['id' => 1, 'category_name' => 'Management'],
            ['id' => 2, 'category_name' => 'Technical'], 
            ['id' => 3, 'category_name' => 'Leadership'],
            ['id' => 4, 'category_name' => 'Professional Development'],
            ['id' => 5, 'category_name' => 'General']
        ];
        
        foreach ($categories as $category) {
            if ($category['id'] == $id) {
                return $category;
            }
        }
        
        return null;
    }
}