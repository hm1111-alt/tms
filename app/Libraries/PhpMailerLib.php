<?php
namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PhpMailerLib
{
    public function load()
    {
        require_once APPPATH . 'ThirdParty/PHPMailer/src/Exception.php';
        require_once APPPATH . 'ThirdParty/PHPMailer/src/PHPMailer.php';
        require_once APPPATH . 'ThirdParty/PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        return $mail;
    }
}
