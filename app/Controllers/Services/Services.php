<?php

namespace App\Controllers\Services;

use App\Controllers\BaseController;

class Services extends BaseController
{
        public function __construct()
        {
                $this->mdl_setting = new \App\Models\preferences\mdl_setting();
                $this->class_name = basename(str_replace('\\', '/', get_class($this)));
        }
        
        public function index()
        {
                $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                $data['children'] = $this->mdl_setting->get_page_children($this->class_name);
                $data['module_name'] = $data['page']->page_name2;
                
                return view('services/services',$data);
        }     
}
