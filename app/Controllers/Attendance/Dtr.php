<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;

class Dtr extends BaseController
{
        
        public function __construct()
        {
                // Load the model in the constructor
                $this->mdl_dtr = new \App\Models\attendance\mdl_dtr();
                
                $this->mdl_setting = new \App\Models\preferences\mdl_setting();
                $this->class_name = basename(str_replace('\\', '/', get_class($this)));
                
        }
        
        public function index()
        {
                $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                return view('attendance/dtr/dtr',$data);
        }

        
        
}
