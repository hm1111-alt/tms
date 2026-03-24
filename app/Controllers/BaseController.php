<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
     protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        
        // Set PHP Timezone
        date_default_timezone_set('Asia/Manila');
    
                // Initialize the session
        if($request->uri->getSegment(1)!='login'){
            $this->session = session();
        }
        

        // For testing: Log a message or echo output to check if initController() is called
//        log_message('info', 'initController() is being called!');
//        echo "initController() has been called!<br>";
        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
//        $this->session = session();
//        $this->session = \Config\Services::session();

        // Check if user is logged in
//        if (! $this->session->get('isLoggedIn')) {
            // Redirect to login if not logged in
//            return redirect()->to('/login')->send();
//            exit();
//        }
         
        // Detect MAC address
        //$macAddress = $this->getMacAddress();
        
        // Store MAC address in session
        //$this->session->set('mac_address', $macAddress);

        // Detect computer name
        // $computerName = $this->getComputerName();
        
        
    }
    
    /*
    private function getMacAddress()
    {
        $mac = 'Unknown MAC';

        if (PHP_OS_FAMILY === 'Windows') {
            $output = shell_exec('getmac');
            if ($output) {
                preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);
                $mac = $matches[0] ?? 'Unknown MAC';
            }
        } else {
            $output = shell_exec('ifconfig -a || ip link show');
            if ($output) {
                preg_match('/([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}/', $output, $matches);
                $mac = $matches[0] ?? 'Unknown MAC';
            }
        }

        return $mac;
    }
    
    private function getComputerName()
    {
        return gethostname() ?: (getenv('COMPUTERNAME') ?: getenv('HOSTNAME')) ?: 'Unknown';
    }
     * 
     */
    
    
}
