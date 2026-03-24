<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;

class Travelorders extends BaseController
{
        
        public function __construct()
        {
                // Load the model in the constructor
                $this->mdl_travelorder = new \App\Models\attendance\mdl_travelorder();
                
                
//                if (! $this->session->get('isLoggedIn')) {
                    // User is not logged in, redirect to login page
//                     redirect()->to('LoginController/login');
//                }
                
            
                // Load the session service
                //$this->session = \Config\Services::session();
        
                //$this->session->start();

//                session()->set([
//                    'userid' => '1',
//                    'username' => 'Admin',
//                    'logged_in' => true
//                ]);
        }
        
        public function index()
        {
                $data['module_main'] = 'Attendance';
                $data['module_sub'] = 'Travel Order';
                $data['module_name'] = 'Travel Order';
                return view('attendance/travelorder/travelorder',$data);
        }

        
        
}
