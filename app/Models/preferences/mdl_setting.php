<?php

namespace App\Models\preferences;

use CodeIgniter\Model;

class Mdl_Setting extends Model
{
        
        function get_page_details($class_name)
        {
                $query = $this->db->query("SELECT main.* , 
                                                    parent.class_name parent_class_name , parent.page_name parent_page_name  , parent.page_name2 parent_page_name2 
                                                FROM pages main
                                                LEFT JOIN pages parent ON parent.id_page=main.page_parent
                                                WHERE main.class_name='".strtolower($class_name)."' 
                                                 ");
                return $query->getRow();
                
        }
        
        function get_page_children($class_name)
        {
                $query = $this->db->query("SELECT children.*, parent.class_name parent_class_name 
                                                FROM pages children
                                                LEFT JOIN pages parent ON parent.id_page=children.page_parent
                                                WHERE parent.class_name='".strtolower($class_name)."' and parent.has_children=1
                                                ORDER BY children.position ASC ");
                return $query->getResult();
        }
        
        function get_settings($setting_key)
        {
                $query = $this->db->query("SELECT value from app_config WHERE config_key='".$setting_key."'");
                return @$query->getRow()->value;
        }
        
        
}

