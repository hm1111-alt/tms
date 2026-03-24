<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthAdmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
//        $auth = service('auth');
//
//        if (! $auth->isLoggedIn()) {
//            return redirect()->to(site_url('login'));
//        }
        $request = \Config\Services::request();
        
        if(!session()->get('logged_in')){
            return redirect()->to('login');
        } else if (session()->get('logged_in')==TRUE && session()->get('first_login')==1 && $request->uri->getSegment(1)!='updateprofile') {
            
            return redirect()->to('updateprofile');
        } else if (session()->get('password_reset')==1 && $request->uri->getSegment(1)!='dashboard'  && $request->uri->getSegment(1)!='updateprofile' &&
                ($request->uri->getSegment(1)!='profile' && $request->uri->getSegment(2)!='update_password')
            ) {
            return redirect()->to('dashboard');
        }
        
                if((!session()->get('esign_file') || session()->get('esign_file')=='') 
                        && ($request->uri->getSegment(1)=='leaves' || $request->uri->getSegment(2)=='leaves'
                             || $request->uri->getSegment(1)=='credits')
                        ){

                    //$request = \Config\Services::request();
                    if($request->uri->getSegment(1)!='update_esign'){
                        return redirect()->to('update_esign');

                    }
                }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}