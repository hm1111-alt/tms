<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class Mdl_Lib_Training_Category extends Model
{
    protected $table = 'lib_training_category';
    protected $primaryKey = 'id_training_category';
    protected $allowedFields = [
        'training_category_name',
        'training_category_abbr',
        'degree',
        'is_deleted',
        'is_visible',
        'lnd_category_id'
    ];
    protected $returnType = 'object';
    protected $useTimestamps = false;
    
    // Add validation rules
    protected $validationRules = [
        'training_category_name' => 'required|min_length[3]|max_length[255]|is_unique[lib_training_category.training_category_name,id_training_category,{id_training_category}]',
        'training_category_abbr' => 'permit_empty|max_length[50]',
        'degree' => 'permit_empty|in_list[Basic,Intermediate,Advanced,Expert]',
        'is_deleted' => 'permit_empty|in_list[0,1]',
        'is_visible' => 'permit_empty|in_list[0,1]',
        'lnd_category_id' => 'permit_empty|numeric'
    ];
    
    protected $validationMessages = [
        'training_category_name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 3 characters',
            'max_length' => 'Category name cannot exceed 255 characters',
            'is_unique' => 'This category name already exists'
        ],
        'degree' => [
            'in_list' => 'Please select a valid degree level'
        ]
    ];

    function __construct()
    {
        $this->db = Database::connect();
    }

 
    public function getAllCategories($includeDeleted = false, $visibleOnly = true)
    {
        try {
            $query = $this->orderBy('training_category_name', 'ASC');
            
            if (!$includeDeleted) {
                $query->where('is_deleted', 0);
            }
            
            if ($visibleOnly) {
                $query->where('is_visible', 1);
            }
            
            return $query->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching training categories: ' . $e->getMessage());
            return [];
        }
    }

    public function getCategoryById($id)
    {
        try {
            return $this->find($id);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching category by ID: ' . $e->getMessage());
            return null;
        }
    }

    public function getCategoryName($id)
    {
        try {
            $result = $this->find($id);
            return $result ? $result->training_category_name : null;
        } catch (\Exception $e) {
            log_message('error', 'Error fetching category name: ' . $e->getMessage());
            return null;
        }
    }


    public function createCategory($data)
    {
        try {
            // Set default values
            $data['is_deleted'] = $data['is_deleted'] ?? 0;
            $data['is_visible'] = $data['is_visible'] ?? 1;
            
            return $this->insert($data) ? $this->getInsertID() : false;
        } catch (\Exception $e) {
            log_message('error', 'Error creating training category: ' . $e->getMessage());
            return false;
        }
    }


    public function updateCategory($id, $data)
    {
        try {
            return $this->update($id, $data);
        } catch (\Exception $e) {
            log_message('error', 'Error updating training category: ' . $e->getMessage());
            return false;
        }
    }


    public function deleteCategory($id)
    {
        try {
            return $this->update($id, ['is_deleted' => 1]);
        } catch (\Exception $e) {
            log_message('error', 'Error deleting training category: ' . $e->getMessage());
            return false;
        }
    }


    public function permanentlyDeleteCategory($id)
    {
        try {
            return $this->delete($id);
        } catch (\Exception $e) {
            log_message('error', 'Error permanently deleting training category: ' . $e->getMessage());
            return false;
        }
    }


    public function categoryNameExists($categoryName, $excludeId = null)
    {
        try {
            $query = $this->where('training_category_name', $categoryName);
            
            if ($excludeId !== null) {
                $query->where('id_training_category !=', $excludeId);
            }
            
            return $query->countAllResults() > 0;
        } catch (\Exception $e) {
            log_message('error', 'Error checking category name existence: ' . $e->getMessage());
            return false;
        }
    }


    public function getCategoriesWithCount()
    {
        try {
            return $this->select('lib_training_category.*, COUNT(lib_trainings.id_training) as training_count')
                ->join('lib_trainings', 'lib_trainings.training_category_id = lib_training_category.id_training_category', 'left')
                ->where('lib_training_category.is_deleted', 0)
                ->groupBy('lib_training_category.id_training_category')
                ->orderBy('lib_training_category.training_category_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching categories with count: ' . $e->getMessage());
            return [];
        }
    }

 
    public function activateCategory($id)
    {
        try {
            return $this->update($id, ['is_visible' => 1]);
        } catch (\Exception $e) {
            log_message('error', 'Error activating category: ' . $e->getMessage());
            return false;
        }
    }


    public function deactivateCategory($id)
    {
        try {
            return $this->update($id, ['is_visible' => 0]);
        } catch (\Exception $e) {
            log_message('error', 'Error deactivating category: ' . $e->getMessage());
            return false;
        }
    }


    public function getActiveCategoriesForDropdown()
    {
        try {
            $categories = $this->getAllCategories(false, true);
            $options = [];
            
            foreach ($categories as $category) {
                $options[$category->id_training_category] = $category->training_category_name;
            }
            
            return $options;
        } catch (\Exception $e) {
            log_message('error', 'Error getting categories for dropdown: ' . $e->getMessage());
            return [];
        }
    }


    public function searchCategories($keyword)
    {
        try {
            return $this->where('is_deleted', 0)
                ->groupStart()
                ->like('training_category_name', $keyword)
                ->orLike('training_category_abbr', $keyword)
                ->groupEnd()
                ->orderBy('training_category_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error searching categories: ' . $e->getMessage());
            return [];
        }
    }

    public function getCategoriesByDegree($degree)
    {
        try {
            return $this->where('degree', $degree)
                ->where('is_deleted', 0)
                ->where('is_visible', 1)
                ->orderBy('training_category_name', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching categories by degree: ' . $e->getMessage());
            return [];
        }
    }

 
    public function countCategories($filters = [])
    {
        try {
            $query = $this;
            
            if (!empty($filters['is_deleted'])) {
                $query->where('is_deleted', $filters['is_deleted']);
            }
            
            if (!empty($filters['is_visible'])) {
                $query->where('is_visible', $filters['is_visible']);
            }
            
            if (!empty($filters['degree'])) {
                $query->where('degree', $filters['degree']);
            }
            
            return $query->countAllResults();
        } catch (\Exception $e) {
            log_message('error', 'Error counting categories: ' . $e->getMessage());
            return 0;
        }
    }


    public function restoreCategory($id)
    {
        try {
            return $this->update($id, [
                'is_deleted' => 0,
                'is_visible' => 1
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error restoring category: ' . $e->getMessage());
            return false;
        }
    }
}
